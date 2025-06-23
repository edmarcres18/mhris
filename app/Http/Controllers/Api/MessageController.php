<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use App\Events\MessageRead;
use App\Events\UserTyping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Get messages for a conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request, $conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with(['user', 'replyToMessage.user'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($user) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'attachment' => $message->attachment ? asset('storage/' . $message->attachment) : null,
                    'attachment_name' => $message->attachment_name,
                    'attachment_type' => $message->attachment_type,
                    'is_read' => $message->is_read,
                    'read_at' => $message->read_at,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->first_name . ' ' . $message->user->last_name,
                    'user_avatar' => $message->user->profile_image,
                    'is_mine' => $message->user_id === $user->id,
                    'reply_to' => $message->reply_to,
                    'reply_to_message' => $message->reply_to && $message->replyToMessage ? [
                        'id' => $message->replyToMessage->id,
                        'body' => $message->replyToMessage->body,
                        'attachment_name' => $message->replyToMessage->attachment_name,
                        'user_name' => $message->replyToMessage->user->first_name . ' ' . $message->replyToMessage->user->last_name,
                    ] : null,
                ];
            });

        return response()->json($messages);
    }

    /**
     * Send a new message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'body' => 'nullable|string',
            'attachment' => 'nullable|file',
            'reply_to' => 'nullable|exists:messages,id',
        ]);

        $user = Auth::user();
        $conversationId = $request->conversation_id;
        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = new Message();
        $message->conversation_id = $conversationId;
        $message->user_id = $user->id;
        $message->body = $request->body;
        
        // Set reply_to if provided
        if ($request->filled('reply_to')) {
            // Verify the replied-to message belongs to this conversation
            $replyToMessage = Message::where('id', $request->reply_to)
                ->where('conversation_id', $conversationId)
                ->first();
                
            if ($replyToMessage) {
                $message->reply_to = $request->reply_to;
            }
        }

        // Handle file attachment if present
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('chat_attachments', $fileName, 'public');
            
            $message->attachment = $filePath;
            $message->attachment_name = $file->getClientOriginalName();
            $message->attachment_type = $file->getMimeType();
        }

        $message->save();

        // Update conversation timestamp
        $conversation->touch();

        // Try to broadcast the message event but handle failures gracefully
        try {
            event(new MessageSent($message, $conversation, $user));
        } catch (\Exception $e) {
            // Log the error but don't let it break the entire message send flow
            Log::error('Failed to broadcast message event: ' . $e->getMessage(), [
                'message_id' => $message->id,
                'conversation_id' => $conversation->id,
                'exception' => $e
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'body' => $message->body,
                'attachment' => $message->attachment ? asset('storage/' . $message->attachment) : null,
                'attachment_name' => $message->attachment_name,
                'attachment_type' => $message->attachment_type,
                'is_read' => $message->is_read,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'user_id' => $user->id,
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'user_avatar' => $user->profile_image,
                'is_mine' => true,
                'reply_to' => $message->reply_to,
                'reply_to_message' => $message->reply_to ? [
                    'id' => $message->replyToMessage->id ?? null,
                    'body' => $message->replyToMessage->body ?? null,
                    'user_name' => $message->replyToMessage ? $message->replyToMessage->user->first_name . ' ' . $message->replyToMessage->user->last_name : null,
                ] : null,
            ]
        ]);
    }

    /**
     * Mark messages as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id',
        ]);

        $user = Auth::user();
        $messageIds = $request->message_ids;

        // Get messages and check permissions
        $messages = Message::whereIn('id', $messageIds)
            ->with('conversation')
            ->get();

        foreach ($messages as $message) {
            // Ensure the user is a participant of the conversation
            if ($message->conversation->hasParticipant($user->id)) {
                $message->markAsRead();
                
                // Try to broadcast the read receipt but handle failures gracefully
                try {
                    event(new MessageRead($message, $user));
                } catch (\Exception $e) {
                    Log::error('Failed to broadcast read receipt: ' . $e->getMessage(), [
                        'message_id' => $message->id,
                        'user_id' => $user->id,
                        'exception' => $e
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Delete a message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $id)
    {
        $user = Auth::user();
        $message = Message::findOrFail($id);

        // Only the message sender can delete their own messages
        if ($message->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If there's an attachment, delete it
        if ($message->attachment) {
            Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Broadcast typing indicator.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function typing(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'is_typing' => 'required|boolean',
        ]);

        $user = Auth::user();
        $conversationId = $request->conversation_id;
        $isTyping = $request->is_typing;

        $conversation = Conversation::findOrFail($conversationId);

        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Try to broadcast the typing event but handle failures gracefully
        try {
            event(new UserTyping($conversation, $user, $isTyping));
        } catch (\Exception $e) {
            Log::error('Failed to broadcast typing event: ' . $e->getMessage(), [
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'is_typing' => $isTyping,
                'exception' => $e
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get all conversations with unread counts and last message.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConversations()
    {
        $user = Auth::user();
        
        $conversations = Conversation::whereHas('participants', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['lastMessage', 'lastMessage.user', 'participants.user'])
        ->get()
        ->map(function ($conversation) use ($user) {
            // Get the display name based on conversation type
            $displayName = '';
            $avatarUrl = asset('images/default-avatar.png');
            
            if ($conversation->is_group) {
                $displayName = $conversation->name;
                if ($conversation->avatar) {
                    $avatarUrl = asset('storage/' . $conversation->avatar);
                }
            } else {
                // For direct messages, get the other participant
                $otherParticipant = $conversation->participants
                    ->where('user_id', '!=', $user->id)
                    ->first();
                
                if ($otherParticipant && $otherParticipant->user) {
                    $displayName = $otherParticipant->user->first_name . ' ' . $otherParticipant->user->last_name;
                    if ($otherParticipant->user->profile_image) {
                        $avatarUrl = asset('storage/' . $otherParticipant->user->profile_image);
                    } elseif (method_exists($otherParticipant->user, 'adminlte_image') && $otherParticipant->user->adminlte_image()) {
                        $avatarUrl = $otherParticipant->user->adminlte_image();
                    }
                    
                    // Get online status
                    $isOnline = $otherParticipant->user->last_seen && 
                                $otherParticipant->user->last_seen >= now()->subMinutes(2);
                }
            }
            
            // Format the last message
            $lastMessage = null;
            if ($conversation->lastMessage) {
                $lastMessage = [
                    'id' => $conversation->lastMessage->id,
                    'body' => $conversation->lastMessage->body,
                    'attachment' => $conversation->lastMessage->attachment ? asset('storage/' . $conversation->lastMessage->attachment) : null,
                    'attachment_name' => $conversation->lastMessage->attachment_name,
                    'attachment_type' => $conversation->lastMessage->attachment_type,
                    'created_at' => $conversation->lastMessage->created_at->toIso8601String(),
                    'user_id' => $conversation->lastMessage->user_id,
                    'user_name' => $conversation->lastMessage->user->first_name . ' ' . $conversation->lastMessage->user->last_name,
                    'is_system' => $conversation->lastMessage->is_system ?? false,
                    'system_action' => $conversation->lastMessage->system_action ?? null,
                ];
            }
            
            return [
                'id' => $conversation->id,
                'name' => $displayName,
                'avatar' => $avatarUrl,
                'is_group' => $conversation->is_group,
                'is_online' => $conversation->is_group ? false : ($isOnline ?? false),
                'last_message' => $lastMessage,
                'participants_count' => $conversation->participants->count(),
                'unread_count' => $conversation->unreadMessagesCount($user->id),
                'updated_at' => $conversation->updated_at->toIso8601String()
            ];
        })
        ->sortByDesc('updated_at')
        ->values();
        
        return response()->json($conversations);
    }
}
