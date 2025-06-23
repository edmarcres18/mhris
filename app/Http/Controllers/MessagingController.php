<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Participant;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MessagingController extends Controller
{
    /**
     * Display the messaging interface.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get conversations through participants table
        $conversations = Conversation::whereHas('participants', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['lastMessage', 'participants.user'])
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('messaging.index', [
            'conversations' => $conversations,
        ]);
    }

    /**
     * Show a specific conversation.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = Auth::user();
        $conversation = Conversation::with(['messages.user', 'participants.user'])
            ->findOrFail($id);

        // Check if user is a participant
        if (!$conversation->hasParticipant($user->id)) {
            abort(403, 'You are not a participant in this conversation.');
        }

        // Mark conversation as read for this user
        $participant = Participant::where('conversation_id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if ($participant) {
            $participant->markAsRead();
        }

        // Set all unread messages to read
        $unreadMessages = $conversation->unreadMessages($user->id);
        foreach ($unreadMessages as $message) {
            $message->markAsRead();
        }

        // Get conversations through participants table
        $conversations = Conversation::whereHas('participants', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['lastMessage', 'participants.user'])
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('messaging.index', [
            'conversations' => $conversations,
            'activeConversation' => $conversation,
        ]);
    }

    /**
     * Store a new message in a conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $conversationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMessage(Request $request, $conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);

        // Check if user is a participant
        if (!$conversation->hasParticipant($user->id)) {
            abort(403, 'You are not a participant in this conversation.');
        }

        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file',
            'reply_to' => 'nullable|exists:messages,id',
        ]);

        $message = new Message();
        $message->conversation_id = $conversationId;
        $message->user_id = $user->id;
        $message->body = $request->message;
        
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

        // Broadcast the message event
        event(new MessageSent($message, $conversation, $user));

        return redirect()->route('messaging.show', $conversationId);
    }

    /**
     * Create a new conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createConversation(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'name' => 'nullable|string|max:255',
            'is_group' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Check if this is a group chat or direct message
        $isGroup = $request->is_group ?? false;
        
        // For direct messages, check if conversation already exists
        if (!$isGroup && count($request->users) === 1) {
            $otherUserId = $request->users[0];
            
            // Check for existing conversation between these two users
            // Better approach: use a subquery to count participants
            $existingConversation = Conversation::whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('participants', function($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })
            ->where('is_group', false)
            ->whereRaw('(SELECT COUNT(*) FROM participants WHERE participants.conversation_id = conversations.id) = 2')
            ->first();
            
            if ($existingConversation) {
                return redirect()->route('messaging.show', $existingConversation->id);
            }
        }
        
        // Create new conversation
        $conversation = new Conversation();
        $conversation->is_group = $isGroup;
        $conversation->name = $isGroup ? $request->name : null;
        $conversation->created_by = $user->id;
        $conversation->save();
        
        // Add creator as first participant
        $conversation->addParticipant($user->id);
        
        // Add other users to conversation
        foreach ($request->users as $userId) {
            if ($userId != $user->id) {
                if ($isGroup) {
                    // Use the method that creates system messages and triggers events
                    $conversation->addUserToGroup($userId, $user->id);
                } else {
                    // For direct messages, just add participant
                    $conversation->addParticipant($userId);
                }
            }
        }
        
        // Add welcome message for group conversations
        if ($isGroup) {
            // Create welcome message
            $conversation->createWelcomeMessage($user->id);
        }
        
        return redirect()->route('messaging.show', $conversation->id);
    }

    /**
     * Search users to start a conversation with.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('id', '!=', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email', 'profile_image']);

        // Format user names and get avatars
        $users->each(function ($user) {
            $user->name = $user->first_name . ' ' . $user->last_name;
            $user->adminlte_image = $user->adminlte_image();
        });

        return response()->json($users);
    }

    /**
     * Mark all messages in a conversation as read.
     *
     * @param  int  $id  Conversation ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead($id)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($id);
        
        // Check if user is a participant
        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Get all unread messages
        $unreadMessages = $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->get();
        
        // Mark each message as read and trigger events
        foreach ($unreadMessages as $message) {
            $message->markAsRead();
            
            // Broadcast read receipt event
            try {
                event(new \App\Events\MessageRead($message, $user));
            } catch (\Exception $e) {
                // Log error but continue
                \Illuminate\Support\Facades\Log::error('Failed to broadcast read receipt: ' . $e->getMessage());
            }
        }
        
        // Update participant's last_read timestamp
        $participant = $conversation->participants()
            ->where('user_id', $user->id)
            ->first();
            
        if ($participant) {
            $participant->markAsRead();
        }
        
        return response()->json([   
            'success' => true,
            'count' => $unreadMessages->count()
        ]);
    }

    /**
     * Search messages in a conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  Conversation ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchMessages(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $conversation = Conversation::findOrFail($id);
            
            // Check if user is a participant
            if (!$conversation->hasParticipant($user->id)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $query = $request->input('query');
            
            if (empty($query) || strlen($query) < 2) {
                return response()->json(['results' => []]);
            }
            
            // Sanitize query for SQL injection prevention
            $sanitizedQuery = str_replace(['%', '_'], ['\%', '\_'], $query);
            
            // Search messages with surrounding context
            $messages = $conversation->messages()
                ->with(['user'])
                ->where(function($q) use ($sanitizedQuery) {
                    // Search in message body
                    $q->where('body', 'LIKE', "%{$sanitizedQuery}%")
                      // Also search in metadata for system messages if query is about calls
                      ->orWhere(function($subq) use ($sanitizedQuery) {
                          if (stripos('call', $sanitizedQuery) !== false || 
                              stripos('audio', $sanitizedQuery) !== false || 
                              stripos('video', $sanitizedQuery) !== false) {
                              $subq->where('system_action', 'call_started');
                          }
                      });
                })
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function($message) use ($query, $user) {
                    try {
                        // Highlight the search term safely with proper escaping
                        $escapedQuery = preg_quote($query, '/');
                        $escapedBody = htmlspecialchars($message->body, ENT_QUOTES, 'UTF-8');
                        $highlightedBody = preg_replace(
                            '/(' . $escapedQuery . ')/i', 
                            '<span class="bg-yellow-200">$1</span>', 
                            $escapedBody
                        );
                        
                        return [
                            'id' => $message->id,
                            'body' => $message->body,
                            'highlighted_body' => $highlightedBody ?: $escapedBody, // Fallback if highlighting fails
                            'created_at' => $message->created_at->format('M j, Y g:i A'),
                            'user_name' => $message->user->first_name . ' ' . $message->user->last_name,
                            'user_avatar' => $message->user->adminlte_image(),
                            'is_mine' => $message->user_id === $user->id,
                            'timestamp' => $message->created_at->timestamp,
                            'is_system' => $message->is_system,
                            'system_action' => $message->system_action,
                        ];
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Error processing message in search: ' . $e->getMessage());
                        // Return basic message info if processing fails
                        return [
                            'id' => $message->id,
                            'body' => $message->body,
                            'highlighted_body' => htmlspecialchars($message->body, ENT_QUOTES, 'UTF-8'),
                            'created_at' => $message->created_at->format('M j, Y g:i A'),
                            'user_name' => $message->user->first_name . ' ' . $message->user->last_name,
                            'timestamp' => $message->created_at->timestamp,
                        ];
                    }
                });
            
            return response()->json([
                'results' => $messages,
                'count' => $messages->count(),
                'query' => $query,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error searching messages: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching messages',
                'results' => [],
                'count' => 0,
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get shared files in a conversation.
     *
     * @param  int  $id  Conversation ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSharedFiles($id)
    {
        try {
            $user = Auth::user();
            $conversation = Conversation::findOrFail($id);
            
            // Check if user is a participant
            if (!$conversation->hasParticipant($user->id)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            // Get all messages with attachments in this conversation
            $messagesWithAttachments = $conversation->messages()
                ->whereNotNull('attachment')
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Get all messages with links in this conversation
            $messagesWithLinks = $conversation->messages()
                ->whereRaw("body LIKE '%http%'")
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Process attachments into categories
            $media = [];
            $documents = [];
            
            foreach ($messagesWithAttachments as $message) {
                $attachmentUrl = asset('storage/' . $message->attachment);
                $attachmentType = $message->attachment_type;
                $attachmentName = $message->attachment_name ?: 'File';
                $createdAt = $message->created_at;
                $userId = $message->user_id;
                $userName = $message->user->first_name . ' ' . $message->user->last_name;
                
                $fileData = [
                    'id' => $message->id,
                    'name' => $attachmentName,
                    'url' => $attachmentUrl,
                    'type' => $attachmentType,
                    'size' => Storage::disk('public')->size($message->attachment) ?? 0,
                    'created_at' => $createdAt,
                    'user_id' => $userId,
                    'user_name' => $userName
                ];
                
                // Categorize the file based on MIME type
                if (strpos($attachmentType, 'image/') === 0 || strpos($attachmentType, 'video/') === 0) {
                    // Add to media
                    $media[] = $fileData;
                } else {
                    // Add to documents
                    $documents[] = $fileData;
                }
            }
            
            // Extract links from messages
            $links = [];
            
            foreach ($messagesWithLinks as $message) {
                // Use regex to find URLs in the message body
                preg_match_all('/https?:\/\/\S+/i', $message->body, $matches);
                
                if (!empty($matches[0])) {
                    foreach ($matches[0] as $url) {
                        // Clean up the URL (remove trailing punctuation etc.)
                        $url = rtrim($url, '.,;:)"\']}');
                        
                        // Try to get the title (in a real app, you might want to fetch the page and extract the title)
                        $title = parse_url($url, PHP_URL_HOST);
                        
                        $links[] = [
                            'id' => $message->id,
                            'url' => $url,
                            'title' => $title,
                            'created_at' => $message->created_at,
                            'user_id' => $message->user_id,
                            'user_name' => $message->user->first_name . ' ' . $message->user->last_name
                        ];
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'media' => $media,
                'documents' => $documents,
                'links' => $links
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error getting shared files: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving shared files',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
