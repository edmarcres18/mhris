<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentReaction;
use Illuminate\Support\Facades\Auth;

class CommentReactionController extends Controller
{
    /**
     * Store a new reaction or update existing one
     */
    public function store(Request $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $userId = Auth::id();
        
        // Validate reaction type
        $request->validate([
            'type' => 'required|in:like,love,haha,wow,sad,angry'
        ]);
        
        // Check if user already has a reaction to this comment
        $existingReaction = CommentReaction::where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->first();
            
        if ($existingReaction) {
            // Update existing reaction
            $existingReaction->update(['type' => $request->type]);
        } else {
            // Create new reaction
            CommentReaction::create([
                'comment_id' => $commentId,
                'user_id' => $userId,
                'type' => $request->type
            ]);
        }
        
        return $this->getReactionCounts($comment);
    }
    
    /**
     * Remove a reaction
     */
    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $userId = Auth::id();
        
        // Delete the reaction
        CommentReaction::where('comment_id', $commentId)
            ->where('user_id', $userId)
            ->delete();
            
        return $this->getReactionCounts($comment);
    }
    
    /**
     * Get reaction counts for a comment
     */
    private function getReactionCounts(Comment $comment)
    {
        $userId = Auth::id();
        
        // Get all reactions
        $reactions = $comment->reactions;
        
        // Count by type
        $counts = [
            'total' => $reactions->count(),
            'like' => $reactions->where('type', 'like')->count(),
            'love' => $reactions->where('type', 'love')->count(),
            'haha' => $reactions->where('type', 'haha')->count(),
            'wow' => $reactions->where('type', 'wow')->count(),
            'sad' => $reactions->where('type', 'sad')->count(),
            'angry' => $reactions->where('type', 'angry')->count(),
        ];
        
        // Get user's reaction
        $userReaction = null;
        if ($userId) {
            $reaction = $reactions->where('user_id', $userId)->first();
            if ($reaction) {
                $userReaction = $reaction->type;
            }
        }
        
        return response()->json(array_merge($counts, ['user_reaction' => $userReaction]));
    }
} 