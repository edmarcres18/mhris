<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Reaction;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:post-list|post-create|post-edit|post-delete'], ['only' => ['index']]);
        $this->middleware(['permission:post-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:post-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:post-delete'], ['only' => ['destroy']]);
        $this->middleware(['permission:post-show'], ['only' => ['show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('posts.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        // Handle image upload if present
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Validate image size and dimensions
            $this->validate($request, [
                'image' => 'image|max:2048', // 2MB max
            ]);
            
            // Create optimized image name with timestamp and random string
            $imageName = time() . '_' . substr(md5(rand()), 0, 10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/posts'), $imageName);
            $data['image_path'] = 'uploads/posts/' . $imageName;
        }
        
        $post = Post::create($data);
        return redirect()->route('posts.index')->with('success', 'Post saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Load comments with user info and replies
        $post->load(['comments' => function($query) {
            $query->with(['user', 'replies.user'])
                  ->orderBy('created_at', 'desc')
                  ->take(10);
        }]);
        
        // Get detailed reaction counts
        $reactionCounts = [
            'total' => 0,
            'like' => 0,
            'love' => 0,
            'haha' => 0,
            'wow' => 0,
            'sad' => 0,
            'angry' => 0,
        ];
        
        // Get all reactions with user details grouped by type
        $reactionsByType = [
            'like' => [],
            'love' => [],
            'haha' => [],
            'wow' => [],
            'sad' => [],
            'angry' => []
        ];
        
        $reactions = $post->reactions()->with('user')->get();
        $reactionCounts['total'] = $reactions->count();
        
        foreach ($reactions as $reaction) {
            $reactionCounts[$reaction->type]++;
            $reactionsByType[$reaction->type][] = [
                'id' => $reaction->user->id,
                'name' => $reaction->user->first_name . ' ' . $reaction->user->last_name,
                'avatar' => $reaction->user->profile_image ?? null,
                'timestamp' => $reaction->created_at->diffForHumans(),
            ];
        }
        
        // Get user's reaction if logged in
        $userReaction = null;
        if (Auth::check()) {
            $userReaction = $post->reactions()
                ->where('user_id', Auth::id())
                ->first();
        }
        
        // Check if current user is the post author
        $isPostAuthor = Auth::check() && Auth::id() === $post->user_id;
        
        // Get recent commenters (unique users)
        $commenters = $post->allComments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->pluck('user')
            ->unique('id')
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'avatar' => $user->profile_image ?? null,
                ];
            });
        
        // Get comment count details
        $commentStats = [
            'total' => $post->allComments()->count(),
            'topLevel' => $post->comments()->count(),
            'replies' => $post->allComments()->whereNotNull('parent_id')->count(),
            'latest' => $post->allComments()->latest()->first() ? $post->allComments()->latest()->first()->created_at->diffForHumans() : null
        ];
        
        // Get related posts (posts from the same day)
        $relatedPosts = Post::whereDate('created_at', $post->created_at->toDateString())
            ->where('id', '!=', $post->id)
            ->take(3)
            ->get();
        
        return view('posts.show', compact(
            'post', 
            'reactionCounts', 
            'reactionsByType', 
            'userReaction', 
            'isPostAuthor', 
            'commenters', 
            'commentStats',
            'relatedPosts'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->all();
        
        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image_path && file_exists(public_path($post->image_path))) {
                unlink(public_path($post->image_path));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/posts'), $imageName);
            $data['image_path'] = 'uploads/posts/' . $imageName;
        }
        
        $post->update($data);
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    /**
     * Display the specified post by ID.
     */
    public function showPostById($id)
    {
        $post = Post::findOrFail($id);
        
        // Load comments with user info and replies
        $post->load(['comments' => function($query) {
            $query->with(['user', 'replies.user'])
                  ->orderBy('created_at', 'desc')
                  ->take(10);
        }]);
        
        // Get detailed reaction counts
        $reactionCounts = [
            'total' => 0,
            'like' => 0,
            'love' => 0,
            'haha' => 0,
            'wow' => 0,
            'sad' => 0,
            'angry' => 0,
        ];
        
        // Get all reactions with user details grouped by type
        $reactionsByType = [
            'like' => [],
            'love' => [],
            'haha' => [],
            'wow' => [],
            'sad' => [],
            'angry' => []
        ];
        
        $reactions = $post->reactions()->with('user')->get();
        $reactionCounts['total'] = $reactions->count();
        
        foreach ($reactions as $reaction) {
            $reactionCounts[$reaction->type]++;
            $reactionsByType[$reaction->type][] = [
                'id' => $reaction->user->id,
                'name' => $reaction->user->first_name . ' ' . $reaction->user->last_name,
                'avatar' => $reaction->user->profile_image ?? null,
                'timestamp' => $reaction->created_at->diffForHumans(),
            ];
        }
        
        // Get user's reaction if logged in
        $userReaction = null;
        if (Auth::check()) {
            $userReaction = $post->reactions()
                ->where('user_id', Auth::id())
                ->first();
        }
        
        // Check if current user is the post author
        $isPostAuthor = Auth::check() && Auth::id() === $post->user_id;
        
        // Get recent commenters (unique users)
        $commenters = $post->allComments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->pluck('user')
            ->unique('id')
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'avatar' => $user->profile_image ?? null,
                ];
            });
        
        // Get comment count details
        $commentStats = [
            'total' => $post->allComments()->count(),
            'topLevel' => $post->comments()->count(),
            'replies' => $post->allComments()->whereNotNull('parent_id')->count(),
            'latest' => $post->allComments()->latest()->first() ? $post->allComments()->latest()->first()->created_at->diffForHumans() : null
        ];
        
        // Get related posts (posts from the same day)
        $relatedPosts = Post::whereDate('created_at', $post->created_at->toDateString())
            ->where('id', '!=', $post->id)
            ->take(3)
            ->get();
        
        return view('posts.show', compact(
            'post', 
            'reactionCounts', 
            'reactionsByType', 
            'userReaction', 
            'isPostAuthor', 
            'commenters', 
            'commentStats',
            'relatedPosts'
        ));
    }
    
    /**
     * Get reaction details for a post
     */
    public function getReactionDetails(Post $post, $type = null)
    {
        $query = $post->reactions()->with('user');
        
        if ($type && in_array($type, ['like', 'love', 'haha', 'wow', 'sad', 'angry'])) {
            $query->where('type', $type);
        }
        
        $reactions = $query->get()->map(function($reaction) {
            return [
                'id' => $reaction->user->id,
                'name' => $reaction->user->first_name . ' ' . $reaction->user->last_name,
                'type' => $reaction->type,
                'timestamp' => $reaction->created_at->diffForHumans(),
            ];
        });
        
        return response()->json($reactions);
    }
}
