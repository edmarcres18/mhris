<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);
        
        $comment = new Comment([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);
        
        $comment->save();
        
        $comment->load('user');
        
        if ($request->ajax()) {
            return response()->json([
                'comment' => $comment,
                'user' => [
                    'name' => $comment->user->name ?? $comment->user->first_name . ' ' . $comment->user->last_name,
                    'avatar' => $comment->user->avatar ?? null,
                ],
                'created_at_formatted' => $comment->created_at->diffForHumans(),
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if the user is authorized to update this comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not authorized to edit this comment.'], 403);
        }
        
        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
            ]);
            
            $comment->content = $validated['content'];
            $comment->save();
            
            // Return proper JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'comment' => $comment,
                    'message' => 'Comment updated successfully'
                ]);
            }
            
            return redirect()->back()->with('success', 'Comment updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating comment: ' . $e->getMessage(), [
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to update comment. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update comment. Please try again.');
        }
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        // Only allow users to delete their own comments
        if ($comment->user_id !== Auth::id()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['error' => 'You are not authorized to delete this comment.'], 403);
            }
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }
        
        try {
            // Handle replies to this comment if needed
            $hasReplies = $comment->replies()->count() > 0;
            
            // Delete the comment
            $result = $comment->delete();
            
            if (!$result) {
                throw new \Exception('Failed to delete comment.');
            }
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment deleted successfully',
                    'had_replies' => $hasReplies
                ]);
            }
            
            return redirect()->back()->with('success', 'Comment deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting comment: ' . $e->getMessage(), [
                'comment_id' => $comment->id,
                'user_id' => Auth::id(),
                'exception' => $e
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to delete comment. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to delete comment. Please try again.');
        }
    }

    /**
     * Load more comments for a post.
     */
    public function loadMore(Request $request, Post $post)
    {
        try {
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 10);
            
            $comments = $post->comments()
                ->with(['user', 'replies.user'])
                ->orderBy('created_at', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
            
            return response()->json([
                'success' => true,
                'comments' => $comments,
                'hasMore' => $post->comments()->count() > ($offset + $limit),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading more comments: ' . $e->getMessage(), [
                'post_id' => $post->id,
                'exception' => $e
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Failed to load comments. Please try again.'
            ], 500);
        }
    }
}
