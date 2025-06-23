<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
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
     * Store or update a reaction.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'type' => 'required|in:like,love,haha,wow,sad,angry',
        ]);
        
        $reaction = Reaction::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'post_id' => $post->id,
            ],
            [
                'type' => $validated['type'],
            ]
        );
        
        // Return reaction counts for this post
        $counts = $this->getReactionCounts($post);
        $counts['user_reaction'] = $reaction->type;
        
        return response()->json($counts);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove a reaction.
     */
    public function destroy(Post $post)
    {
        Reaction::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->delete();
            
        // Return reaction counts for this post
        $counts = $this->getReactionCounts($post);
        $counts['user_reaction'] = null;
        
        return response()->json($counts);
    }
    
    /**
     * Get reaction counts for a post.
     */
    private function getReactionCounts(Post $post)
    {
        $counts = [
            'total' => 0,
            'like' => 0,
            'love' => 0,
            'haha' => 0,
            'wow' => 0,
            'sad' => 0,
            'angry' => 0,
        ];
        
        $reactions = $post->reactions;
        $counts['total'] = $reactions->count();
        
        foreach ($reactions as $reaction) {
            $counts[$reaction->type]++;
        }
        
        return $counts;
    }
}
