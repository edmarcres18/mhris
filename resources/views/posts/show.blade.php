@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white d-flex align-items-center p-3">
                    <div class="post-author d-flex align-items-center">
                        <div class="author-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="width: 40px; height: 40px; font-size: 16px;">
                            {{ strtoupper(substr($post->user->first_name, 0, 1)) }}
            </div>
                        <div>
                            <h5 class="mb-0">{{ $post->user->first_name }} {{ $post->user->last_name }}</h5>
                            <small class="text-muted">{{ $post->created_at->format('M d, Y \a\t h:i A') }}</small>
                </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <h4 class="mb-3">{{ $post->title }}</h4>
                    
                    @if($post->image_path)
                    <div class="post-featured-image mb-4">
                        <img src="{{ asset($post->image_path) }}" alt="{{ $post->title }}" class="img-fluid rounded shadow-sm" style="max-height: 500px; width: 100%; object-fit: cover;" loading="lazy">
                        
                        <div class="image-overlay-controls d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary mr-2 zoom-image-btn" data-image="{{ asset($post->image_path) }}">
                                <i class="fas fa-search-plus"></i> View Full Image
                            </button>
                        </div>
                    </div>
                    @endif
                    
                    <div class="post-content mb-4">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                    
                    <div class="post-details mb-3">
                        <div class="row">
                    <div class="col-md-6">
                        <div class="post-info">
                            <strong>Start Date:</strong>
                            <p>{{ \Carbon\Carbon::parse($post->date_start)->format('F j, Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="post-info">
                            <strong>End Date:</strong>
                            <p>{{ \Carbon\Carbon::parse($post->date_end)->format('F j, Y') }}</p>
                        </div>
                    </div>
                        </div>
                    </div>
                    
                    <div class="post-stats d-flex justify-content-between align-items-center border-top border-bottom py-2 mb-3">
                        <div class="post-reactions-summary">
                            @if($reactionCounts['total'] > 0)
                                <div class="d-flex align-items-center">
                                    <div class="reaction-icons mr-2">
                                        @if($reactionCounts['like'] > 0)<span class="reaction-icon">👍</span>@endif
                                        @if($reactionCounts['love'] > 0)<span class="reaction-icon">❤️</span>@endif
                                        @if($reactionCounts['haha'] > 0)<span class="reaction-icon">😄</span>@endif
                                        @if($reactionCounts['wow'] > 0)<span class="reaction-icon">😮</span>@endif
                                        @if($reactionCounts['sad'] > 0)<span class="reaction-icon">😢</span>@endif
                                        @if($reactionCounts['angry'] > 0)<span class="reaction-icon">😡</span>@endif
                </div>
                                    <span class="reaction-count" data-toggle="modal" data-target="#reactionsModal" role="button">
                                        {{ $reactionCounts['total'] }}
                                    </span>
                </div>
                            @endif
            </div>
                        <div class="post-comments-summary">
                            @if($commentStats['total'] > 0)
                                <span data-toggle="modal" data-target="#commentersModal" role="button">
                                    {{ $commentStats['total'] }} {{ Str::plural('comment', $commentStats['total']) }}
                                </span>
                            @else
                                <span>0 comments</span>
                            @endif
        </div>
    </div>
                    
                    <div class="post-actions d-flex justify-content-between mb-4">
                        <div class="reactions-container">
                            <div class="reaction-button-main">
                                <button class="btn btn-light btn-block reaction-main-btn {{ $userReaction ? 'active-'.$userReaction->type : '' }}" data-post-id="{{ $post->id }}">
                                    <span class="reaction-icon-label">
                                        @if($userReaction)
                                            @if($userReaction->type == 'like') <span class="reaction-emoji">👍</span> Liked @endif
                                            @if($userReaction->type == 'love') <span class="reaction-emoji">❤️</span> Loved @endif
                                            @if($userReaction->type == 'haha') <span class="reaction-emoji">😄</span> Haha @endif
                                            @if($userReaction->type == 'wow') <span class="reaction-emoji">😮</span> Wow @endif
                                            @if($userReaction->type == 'sad') <span class="reaction-emoji">😢</span> Sad @endif
                                            @if($userReaction->type == 'angry') <span class="reaction-emoji">😡</span> Angry @endif
                                        @else
                                            <i class="far fa-thumbs-up mr-1"></i> Like
                                        @endif
                                    </span>
                                </button>
                                <div class="reaction-options">
                                    <button class="reaction-btn reaction-like" data-type="like" data-post-id="{{ $post->id }}">
                                        <span class="reaction-emoji">👍</span>
                                        <span class="reaction-tooltip">Like</span>
                                    </button>
                                    <button class="reaction-btn reaction-love" data-type="love" data-post-id="{{ $post->id }}">
                                        <span class="reaction-emoji">❤️</span>
                                        <span class="reaction-tooltip">Love</span>
                                    </button>
                                    <button class="reaction-btn reaction-haha" data-type="haha" data-post-id="{{ $post->id }}">
                                        <span class="reaction-emoji">😄</span>
                                        <span class="reaction-tooltip">Haha</span>
                                    </button>
                                    <button class="reaction-btn reaction-wow" data-type="wow" data-post-id="{{ $post->id }}">
                                        <span class="reaction-emoji">😮</span>
                                        <span class="reaction-tooltip">Wow</span>
                                    </button>
                                    <button class="reaction-btn reaction-sad" data-type="sad" data-post-id="{{ $post->id }}">
                                        <span class="reaction-emoji">😢</span>
                                        <span class="reaction-tooltip">Sad</span>
                                    </button>
                                    <button class="reaction-btn reaction-angry" data-type="angry" data-post-id="{{ $post->id }}">
                                        <span class="reaction-emoji">😡</span>
                                        <span class="reaction-tooltip">Angry</span>
                                    </button>
</div>
                            </div>
                        </div>
                        <button class="btn btn-light" id="commentToggleBtn">
                            <i class="far fa-comment mr-1"></i> Comment
                        </button>
                        <button class="btn btn-light" data-toggle="modal" data-target="#shareModal">
                            <i class="far fa-share-square mr-1"></i> Share
                        </button>
                    </div>
                    
                    <!-- Comments section -->
                    <div class="comments-section">
                        <!-- Comment form -->
                        <div class="comment-form mb-4">
                            <form id="newCommentForm" data-post-id="{{ $post->id }}" class="d-flex align-items-start">
                                @csrf
                                <div class="commenter-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="min-width: 40px; height: 40px; font-size: 16px;">
                                    {{ Auth::check() ? strtoupper(substr(Auth::user()->first_name, 0, 1)) : 'U' }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="form-group mb-2">
                                        <textarea class="form-control rounded-pill bg-light" id="commentContent" name="content" rows="1" placeholder="Write a comment..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm float-right">
                                        <span class="comment-btn-text">Comment</span>
                                        <span class="comment-btn-loader d-none">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Posting...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        @if($isPostAuthor && $reactionCounts['total'] > 0)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>{{ $reactionCounts['total'] }} {{ Str::plural('person', $reactionCounts['total']) }}</strong> reacted to your post.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <!-- Mobile sticky comment summary (only for post author) -->
                        @if($isPostAuthor && $commentStats['total'] > 0)
                        <div class="d-md-none comment-summary-sticky bg-white shadow-sm border rounded p-2 mb-3 sticky-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-comments text-primary"></i> 
                                    <span class="font-weight-bold">{{ $commentStats['total'] }} {{ Str::plural('comment', $commentStats['total']) }}</span>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#commentersModal">
                                    View Details
                                </button>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Comments list -->
                        <div class="comments-list">
                            @foreach($post->comments as $comment)
                                <div class="comment mb-3" id="comment-{{ $comment->id }}">
                                    <div class="d-flex">
                                        <div class="commenter-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="min-width: 40px; height: 40px; font-size: 16px;">
                                            {{ strtoupper(substr($comment->user->first_name, 0, 1)) }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="comment-bubble p-3 rounded bg-light">
                                                <div class="comment-header d-flex justify-content-between">
                                                    <div class="comment-user">
                                                        <strong>{{ $comment->user->first_name }} {{ $comment->user->last_name }}</strong>
                                                        @if($isPostAuthor && $post->user_id !== $comment->user_id)
                                                            <span class="badge badge-pill badge-light">Commenter</span>
                                                        @endif
                                                        @if($post->user_id === $comment->user_id)
                                                            <span class="badge badge-pill badge-primary">Author</span>
                                                        @endif
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm p-0 text-muted dropdown-toggle" type="button" id="commentDropdown{{ $comment->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="commentDropdown{{ $comment->id }}">
                                                            @if(Auth::id() == $comment->user_id)
                                                                <button class="dropdown-item edit-comment-btn" data-comment-id="{{ $comment->id }}">
                                                                    <i class="fas fa-edit mr-2"></i> Edit
                                                                </button>
                                                                <button class="dropdown-item text-danger delete-comment-btn" data-comment-id="{{ $comment->id }}">
                                                                    <i class="fas fa-trash mr-2"></i> Delete
                                                                </button>
                                                            @endif
                                                            <button class="dropdown-item reply-btn" data-comment-id="{{ $comment->id }}">
                                                                <i class="fas fa-reply mr-2"></i> Reply
                                                            </button>
                                                            @if($isPostAuthor && Auth::id() != $comment->user_id)
                                                                <button class="dropdown-item text-primary highlight-comment-btn" data-comment-id="{{ $comment->id }}">
                                                                    <i class="fas fa-star mr-2"></i> Highlight
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="comment-content mt-1">
                                                    <p class="mb-0">{{ $comment->content }}</p>
                                                </div>
                                                <div class="comment-reactions mt-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="reaction-icons-sm mr-2">
                                                            <!-- Reaction counts will appear here dynamically -->
                                                        </div>
                                                        <div class="reaction-buttons-sm">
                                                            <button class="btn btn-sm p-0 comment-reaction-btn" data-type="like" data-comment-id="{{ $comment->id }}">
                                                                <span class="reaction-emoji-sm">👍</span>
                                                            </button>
                                                            <button class="btn btn-sm p-0 ml-1 comment-reaction-btn" data-type="love" data-comment-id="{{ $comment->id }}">
                                                                <span class="reaction-emoji-sm">❤️</span>
                                                            </button>
                                                            <button class="btn btn-sm p-0 ml-1 comment-reaction-btn" data-type="haha" data-comment-id="{{ $comment->id }}">
                                                                <span class="reaction-emoji-sm">😄</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="comment-actions mt-1 ml-2">
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                <button class="btn btn-sm p-0 reply-btn text-muted ml-3" data-comment-id="{{ $comment->id }}">Reply</button>
                                                @if($isPostAuthor && Auth::id() != $comment->user_id)
                                                    <button class="btn btn-sm p-0 highlight-comment-btn text-muted ml-3" data-comment-id="{{ $comment->id }}">
                                                        <i class="fas fa-star"></i> Highlight
                                                    </button>
                                                @endif
                                            </div>
                                            
                                            <!-- Reply form -->
                                            <div class="reply-form mt-2 d-none ml-4" id="reply-form-{{ $comment->id }}">
                                                <form class="replyCommentForm d-flex align-items-start" data-post-id="{{ $post->id }}" data-parent-id="{{ $comment->id }}">
                                                    @csrf
                                                    <div class="commenter-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="min-width: 30px; height: 30px; font-size: 14px;">
                                                        {{ Auth::check() ? strtoupper(substr(Auth::user()->first_name, 0, 1)) : 'U' }}
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="form-group mb-2">
                                                            <textarea class="form-control rounded-pill bg-light" name="content" rows="1" placeholder="Write a reply..." required></textarea>
                                                        </div>
                                                        <div>
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <span class="reply-btn-text">Reply</span>
                                                                <span class="reply-btn-loader d-none">
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                                    Sending...
                                                                </span>
                                                            </button>
                                                            <button type="button" class="btn btn-light btn-sm cancel-reply" data-comment-id="{{ $comment->id }}">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            
                                            <!-- Replies list -->
                                            @if(count($comment->replies) > 0)
                                                <div class="replies-list ml-4 mt-2">
                                                    <div class="replies-count mb-2">
                                                        <small class="text-muted font-weight-bold">{{ count($comment->replies) }} {{ Str::plural('reply', count($comment->replies)) }}</small>
                                                    </div>
                                                    @foreach($comment->replies as $reply)
                                                        <div class="reply mb-2" id="comment-{{ $reply->id }}">
                                                            <div class="d-flex">
                                                                <div class="commenter-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="min-width: 30px; height: 30px; font-size: 14px;">
                                                                    {{ strtoupper(substr($reply->user->first_name, 0, 1)) }}
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <div class="reply-bubble p-2 rounded bg-light">
                                                                        <div class="reply-header d-flex justify-content-between">
                                                                            <div class="reply-user">
                                                                                <strong>{{ $reply->user->first_name }} {{ $reply->user->last_name }}</strong>
                                                                                @if($post->user_id === $reply->user_id)
                                                                                    <span class="badge badge-pill badge-primary">Author</span>
                                                                                @endif
                                                                            </div>
                                                                            @if(Auth::id() == $reply->user_id)
                                                                                <div class="reply-actions">
                                                                                    <button class="btn btn-sm p-0 edit-comment-btn text-muted" data-comment-id="{{ $reply->id }}"><i class="fas fa-edit"></i></button>
                                                                                    <button class="btn btn-sm p-0 delete-comment-btn text-muted ml-2" data-comment-id="{{ $reply->id }}"><i class="fas fa-trash"></i></button>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="reply-content mt-1">
                                                                            <p class="mb-0">{{ $reply->content }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="reply-meta ml-2 mt-1">
                                                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if(count($post->comments) > 0)
                            <div class="load-more-comments mt-3 text-center">
                                <button id="loadMoreCommentsBtn" class="btn btn-light btn-sm" data-post-id="{{ $post->id }}" data-offset="{{ count($post->comments) }}">
                                    <i class="fas fa-comment-dots mr-1"></i> Load More Comments
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Posts
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Reaction styles */
    .reaction-icons,
    .reaction-icons-sm {
        display: flex;
        align-items: center;
    }
    
    .reaction-icon {
        font-size: 18px;
        margin-right: 2px;
    }
    
    .reaction-icons-sm .reaction-icon-sm {
        font-size: 14px;
        margin-right: 1px;
    }
    
    .reaction-emoji-sm {
        font-size: 16px;
        opacity: 0.6;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }
    
    .comment-reaction-btn {
        opacity: 0.6;
        transition: all 0.2s ease;
    }
    
    .comment-reaction-btn:hover {
        opacity: 1;
    }
    
    .comment-reaction-btn:hover .reaction-emoji-sm {
        transform: scale(1.2);
        opacity: 1;
    }
    
    .comment-reaction-btn.active {
        opacity: 1;
    }
    
    .comment-reaction-btn.active[data-type="like"] .reaction-emoji-sm {
        color: #1877F2;
    }
    
    .comment-reaction-btn.active[data-type="love"] .reaction-emoji-sm {
        color: #ED5E82;
    }
    
    .comment-reaction-btn.active[data-type="haha"] .reaction-emoji-sm {
        color: #F7B928;
    }
    
    /* Comment styles */
    .comment-form textarea, .reply-form textarea {
        border: 1px solid #E4E6EB;
        padding: 10px 15px;
        resize: none;
        transition: height 0.2s ease;
    }
    
    .comment-form textarea:focus, .reply-form textarea:focus {
        height: 60px;
        border-radius: 15px !important;
    }
    
    .comment-bubble, .reply-bubble {
        background-color: #F0F2F5 !important;
        border-radius: 18px !important;
    }
    
    .comments-list {
        max-height: 600px;
        overflow-y: auto;
    }
    
    #loadMoreCommentsBtn {
        color: #1877F2;
        font-weight: 500;
    }
    
    #loadMoreCommentsBtn:hover {
        background-color: #F0F2F5;
    }
    
    /* Reaction modals */
    .reaction-summary {
        cursor: pointer;
    }
    
    .reaction-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .reaction-user-item:hover {
        background-color: #F0F2F5;
        border-radius: 8px;
    }
    
    .reaction-emoji-small {
        font-size: 16px;
    }
    
    /* Comment summary for post author */
    .comment-summary-sticky {
        top: 70px;
        z-index: 100;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 576px) {
        .reaction-btn {
            padding: 8px 10px;
        }
        
        .reaction-emoji {
            font-size: 18px;
            margin-right: 0;
        }
        
        .commenter-avatar {
            min-width: 32px !important;
            height: 32px !important;
            font-size: 14px !important;
        }
        
        .comment-bubble, .reply-bubble {
            padding: 10px !important;
        }
        
        .comment-actions, .reply-meta {
            font-size: 11px;
        }
        
        .replies-list {
            margin-left: 20px !important;
        }
    }
    
    /* Highlighted comment */
    .comment.highlighted .comment-bubble {
        background-color: #FFF9E6 !important;
        border: 1px solid #F7E3AF;
    }
    
    .comment.highlighted::before {
        content: "★";
        position: absolute;
        left: -5px;
        top: 10px;
        color: #F7B928;
        font-size: 16px;
    }
    
    /* Post reaction styles */
    .reaction-btn {
        border: none;
        background: transparent;
        padding: 8px 15px;
        border-radius: 20px;
        transition: all 0.2s ease;
        font-weight: 500;
        color: #65676B;
    }
    
    .reaction-btn:hover {
        background-color: #F2F3F5;
    }
    
    .reaction-btn.active {
        color: #1877F2;
        font-weight: 600;
    }
    
    .reaction-btn.active[data-type="like"] {
        color: #1877F2;
    }
    
    .reaction-btn.active[data-type="love"] {
        color: #ED5E82;
    }
    
    .reaction-btn.active[data-type="haha"], 
    .reaction-btn.active[data-type="wow"] {
        color: #F7B928;
    }
    
    .reaction-btn.active[data-type="sad"], 
    .reaction-btn.active[data-type="angry"] {
        color: #F6AF40;
    }
    
    .reaction-emoji {
        font-size: 20px;
        margin-right: 5px;
        display: inline-block;
        transition: transform 0.2s ease;
    }
    
    .reaction-btn:hover .reaction-emoji {
        transform: scale(1.2);
    }

    /* Enhanced Reaction System Styles */
    .reactions-container {
        position: relative;
        width: 32%;
    }

    .reaction-button-main {
        position: relative;
    }

    .reaction-main-btn {
        font-weight: 500;
        width: 100%;
        text-align: center;
        border-radius: 4px;
        padding: 8px 0;
        transition: background-color 0.2s;
    }

    .reaction-icon-label {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .reaction-icon-label .reaction-emoji {
        margin-right: 5px;
        font-size: 1.2rem;
    }

    .reaction-options {
        position: absolute;
        bottom: 100%;
        left: 0;
        display: flex;
        background: white;
        border-radius: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        padding: 5px;
        margin-bottom: 10px;
        transition: all 0.3s;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        z-index: 100;
    }

    .reaction-button-main:hover .reaction-options {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .reaction-btn {
        background: none;
        border: none;
        padding: 8px;
        margin: 0 2px;
        border-radius: 50%;
        position: relative;
        transition: transform 0.2s;
    }

    .reaction-btn:hover {
        transform: scale(1.3) translateY(-5px);
        z-index: 2;
    }

    .reaction-btn .reaction-emoji {
        font-size: 1.5rem;
        display: block;
    }

    .reaction-tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
        margin-bottom: 5px;
    }

    .reaction-btn:hover .reaction-tooltip {
        opacity: 1;
        visibility: visible;
    }

    .reaction-btn.reaction-like:hover { background-color: rgba(16, 111, 240, 0.1); }
    .reaction-btn.reaction-love:hover { background-color: rgba(242, 82, 104, 0.1); }
    .reaction-btn.reaction-haha:hover { background-color: rgba(247, 177, 37, 0.1); }
    .reaction-btn.reaction-wow:hover { background-color: rgba(247, 177, 37, 0.1); }
    .reaction-btn.reaction-sad:hover { background-color: rgba(247, 177, 37, 0.1); }
    .reaction-btn.reaction-angry:hover { background-color: rgba(233, 113, 15, 0.1); }

    .active-like { color: #106FF0; }
    .active-love { color: #F25268; }
    .active-haha { color: #F7B125; }
    .active-wow { color: #F7B125; }
    .active-sad { color: #F7B125; }
    .active-angry { color: #E9710F; }

    .reaction-icons {
        display: flex;
        align-items: center;
    }

    .reaction-icon {
        font-size: 14px;
        margin-right: -3px;
        border: 1px solid #fff;
        border-radius: 50%;
        background: #fff;
    }

    .reaction-count {
        font-size: 14px;
        margin-left: 4px;
        cursor: pointer;
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .post-actions {
            flex-wrap: wrap;
        }
        .reactions-container {
            width: 100%;
            margin-bottom: 10px;
        }
        .reaction-options {
            left: 50%;
            transform: translateX(-50%) translateY(10px);
        }
        .reaction-button-main:hover .reaction-options {
            transform: translateX(-50%) translateY(0);
        }
    }

    /* Social Share Styles */
    .social-share-buttons {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px;
        margin: 20px 0;
    }

    .btn-social {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .btn-social:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        color: white;
    }

    .btn-facebook {
        background-color: #3b5998;
    }

    .btn-twitter {
        background-color: #1da1f2;
    }

    .btn-telegram {
        background-color: #0088cc;
    }

    .btn-whatsapp {
        background-color: #25d366;
    }

    .btn-linkedin {
        background-color: #0077b5;
    }

    .btn-email {
        background-color: #777;
    }

    .copy-link-btn {
        cursor: pointer;
    }

    .qr-code-container {
        border-top: 1px solid #eee;
        padding-top: 20px;
        margin-top: 20px;
    }

    .qr-code-image {
        display: inline-block;
        padding: 10px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* Make the share options more responsive */
    @media (max-width: 576px) {
        .social-share-buttons {
            gap: 10px;
        }
        
        .btn-social {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        
        .qr-code-image img {
            max-width: 150px;
        }
    }

    /* Responsive image styles */
    .post-featured-image {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }

    .post-featured-image img {
        transition: transform 0.3s ease;
    }

    @media (max-width: 576px) {
        .post-featured-image img {
            max-height: 300px !important;
        }
        
        .image-overlay-controls {
            flex-direction: column;
        }
        
        .image-overlay-controls .btn {
            margin-bottom: 5px;
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .post-featured-image img {
            max-height: 400px !important;
        }
    }

    /* Full screen image modal */
    #imageModal .modal-content {
        background-color: rgba(0,0,0,0.8);
    }

    #imageModal .modal-body {
        padding: 0;
    }

    #imageModal .close {
        color: white;
        opacity: 0.8;
        position: absolute;
        right: 15px;
        top: 15px;
        z-index: 10;
        font-size: 30px;
    }

    #fullImage {
        max-height: 85vh;
        object-fit: contain;
    }

    /* Highlight button style to match reply button */
    .highlight-comment-btn {
        font-size: inherit;
        font-weight: normal;
        transition: color 0.2s ease;
    }

    .highlight-comment-btn:hover {
        color: #F7B928 !important;
    }

    .highlight-comment-btn .fas {
        font-size: 0.9em;
    }

    /* Ensure both action buttons have consistent spacing */
    .comment-actions .btn {
        margin-left: 12px;
    }

    .comment-actions .btn:first-child {
        margin-left: 0;
    }

    /* Hide all reaction elements */
    .comment-reactions, .reply-reactions, 
    .reaction-icons-sm, .reaction-buttons-sm, 
    .comment-reaction-btn, .reaction-emoji-sm,
    .post-reactions {
        display: none !important;
    }

    /* Enhanced Comment edit styles */
    .edit-comment-textarea {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
        resize: none; /* Using JS for auto-resize */
        min-height: 60px;
        padding: 10px;
        font-size: 14px;
        transition: border-color 0.2s ease, height 0.1s ease;
        overflow: hidden;
    }
    
    .edit-comment-textarea:focus {
        border-color: #4a76a8;
        box-shadow: 0 0 0 0.2rem rgba(74, 118, 168, 0.25);
    }
    
    /* Reply edit has smaller text area */
    .reply .edit-comment-textarea {
        min-height: 45px;
        font-size: 13px;
    }
    
    /* Comment fade effect */
    .comment, .reply {
        transition: opacity 0.3s ease, background-color 0.3s ease;
    }
    
    /* Comment updated highlight effect */
    .comment-updated {
        animation: highlight-comment 2s ease;
    }
    
    @keyframes highlight-comment {
        0% { background-color: rgba(255, 243, 205, 0); }
        30% { background-color: rgba(255, 243, 205, 1); }
        100% { background-color: rgba(255, 243, 205, 0); }
    }
    
    /* Mobile responsiveness improvements */
    @media (max-width: 576px) {
        .edit-form .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .edit-comment-textarea {
            font-size: 13px;
            padding: 8px;
        }
        
        .char-count {
            font-size: 10px;
        }
    }
    
    /* Hide reaction elements */
    .comment-reactions, .reply-reactions, 
    .reaction-icons-sm, .reaction-buttons-sm, 
    .comment-reaction-btn, .post-reactions, 
    .reaction-emoji-sm { 
        display: none !important; 
    }

    /* Professional edit and delete functionality */
    .toast-container {
        position: fixed;
        bottom: 1rem;
        right: 1rem;
        z-index: 9999;
    }
    
    .toast {
        min-width: 250px;
        opacity: 1 !important;
    }
    
    /* Enhanced Comment edit styles */
    .edit-comment-textarea {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
        resize: none; /* Using JS for auto-resize */
        min-height: 60px;
        padding: 10px;
        font-size: 14px;
        transition: border-color 0.2s ease, height 0.1s ease;
        overflow: hidden;
    }
    
    .edit-comment-textarea:focus {
        border-color: #4a76a8;
        box-shadow: 0 0 0 0.2rem rgba(74, 118, 168, 0.25);
    }
    
    /* Reply edit has smaller text area */
    .reply .edit-comment-textarea {
        min-height: 45px;
        font-size: 13px;
    }
    
    /* Comment fade effect */
    .comment, .reply {
        transition: opacity 0.3s ease, background-color 0.3s ease, transform 0.3s ease;
    }
    
    /* Comment updated highlight effect */
    .comment-updated {
        animation: highlight-comment 2s ease;
    }
    
    @keyframes highlight-comment {
        0% { background-color: rgba(255, 243, 205, 0); }
        30% { background-color: rgba(255, 243, 205, 1); }
        100% { background-color: rgba(255, 243, 205, 0); }
    }
    
    /* Edit/Delete Dropdown Styling */
    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .dropdown-item {
        padding: 8px 20px;
        transition: background-color 0.2s;
    }
    
    .dropdown-item:active {
        background-color: #f8f9fa;
        color: inherit;
    }
    
    .dropdown-item.text-danger:hover {
        background-color: #fff5f5;
    }
    
    .dropdown-item i {
        width: 20px;
        text-align: center;
    }
    
    /* Mobile responsiveness improvements */
    @media (max-width: 576px) {
        .edit-form .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .edit-comment-textarea {
            font-size: 13px;
            padding: 8px;
        }
        
        .char-count {
            font-size: 10px;
        }
        
        .reply-actions button {
            padding: 4px !important;
        }
        
        /* Make comment options more touch-friendly on mobile */
        .comment-header .dropdown-toggle,
        .reply-header .dropdown-toggle {
            padding: 8px 5px !important;
        }
        
        .dropdown-item {
            padding: 10px 20px;
        }
    }
    
    /* Hide reaction elements */
    .comment-reactions, .reply-reactions, 
    .reaction-icons-sm, .reaction-buttons-sm, 
    .comment-reaction-btn, .post-reactions, 
    .reaction-emoji-sm { 
        display: none !important; 
    }
</style>

@section('js')
<script id="app-data" type="application/json">
{
    "userInitial": "{{ Auth::check() ? strtoupper(substr(Auth::user()->first_name, 0, 1)) : 'U' }}",
    "isAuthor": {{ Auth::check() && Auth::id() == $post->user_id ? 'true' : 'false' }},
    "userId": {{ Auth::check() ? Auth::id() : '0' }},
    "postId": {{ $post->id }}
}
</script>

<script>
// Debug helper - log errors to console with context
function logError(message, error) {
    console.error(`[COMMENT SYSTEM] ${message}`, error);
}

// Parse app data with error handling
let appData = {};
try {
    appData = JSON.parse(document.getElementById('app-data').textContent);
} catch (error) {
    logError('Failed to parse app data', error);
    // Provide fallback values with proper JavaScript syntax
    appData = {
        userInitial: 'U',
        isAuthor: false,
        userId: 0,
        postId: parseInt("{{ $post->id }}")
    };
}
window.appData = appData;

document.addEventListener('DOMContentLoaded', function() {
    // Ensure Bootstrap is available
    if (typeof bootstrap === 'undefined' && typeof $ === 'undefined') {
        logError('Bootstrap not found. Some functionality may not work properly.');
    }
    
    // Handle new comment form submission
    const newCommentForm = document.getElementById('newCommentForm');
    if (newCommentForm) {
        newCommentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.getAttribute('data-post-id');
            const content = document.getElementById('commentContent').value;
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            if (!content.trim()) return;
            
            // Show loader and disable button
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.querySelector('.comment-btn-text').classList.add('d-none');
            submitBtn.querySelector('.comment-btn-loader').classList.remove('d-none');
            
            // Send AJAX request
            fetch('/posts/' + postId + '/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    content: content,
                    _token: csrf
                })
            })
            .then(response => response.json())
            .then(function(response) {
                // Create new comment HTML and add to top of comments list
                const commentsSection = document.querySelector('.comments-list');
                const authorBadge = window.appData.isAuthor ? '<span class="badge badge-pill badge-primary">Author</span>' : '';
                
                const commentHtml = 
                    '<div class="comment mb-3" id="comment-' + response.comment.id + '">' +
                    '    <div class="d-flex">' +
                    '        <div class="commenter-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="min-width: 40px; height: 40px; font-size: 16px;">' +
                    '            ' + window.appData.userInitial +
                    '        </div>' +
                    '        <div class="flex-grow-1">' +
                    '            <div class="comment-bubble p-3 rounded bg-light">' +
                    '                <div class="comment-header d-flex justify-content-between">' +
                    '                    <div class="comment-user">' +
                    '                        <strong>' + response.user.name + '</strong>' +
                    '                        ' + authorBadge +
                    '                    </div>' +
                    '                    <div class="dropdown">' +
                    '                        <button class="btn btn-sm p-0 text-muted dropdown-toggle" type="button" id="commentDropdown' + response.comment.id + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    '                            <i class="fas fa-ellipsis-v"></i>' +
                    '                        </button>' +
                    '                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="commentDropdown' + response.comment.id + '">' +
                    '                            <button class="dropdown-item edit-comment-btn" data-comment-id="' + response.comment.id + '">' +
                    '                                <i class="fas fa-edit mr-2"></i> Edit' +
                    '                            </button>' +
                    '                            <button class="dropdown-item text-danger delete-comment-btn" data-comment-id="' + response.comment.id + '">' +
                    '                                <i class="fas fa-trash mr-2"></i> Delete' +
                    '                            </button>' +
                    '                            <button class="dropdown-item reply-btn" data-comment-id="' + response.comment.id + '">' +
                    '                                <i class="fas fa-reply mr-2"></i> Reply' +
                    '                            </button>' +
                    '                        </div>' +
                    '                    </div>' +
                    '                </div>' +
                    '                <div class="comment-content mt-1">' +
                    '                    <p class="mb-0">' + response.comment.content + '</p>' +
                    '                </div>' +
                    '            </div>' +
                    '            <div class="comment-actions mt-1 ml-2">' +
                    '                <small class="text-muted">' + response.created_at_formatted + '</small>' +
                    '                <button class="btn btn-sm p-0 reply-btn text-muted ml-3" data-comment-id="' + response.comment.id + '">Reply</button>' +
                    '            </div>' +
                    '            <div class="reply-form mt-2 d-none ml-4" id="reply-form-' + response.comment.id + '">' +
                    '                <form class="replyCommentForm d-flex align-items-start" data-post-id="' + window.appData.postId + '" data-parent-id="' + response.comment.id + '">' +
                    '                    <input type="hidden" name="_token" value="' + csrf + '">' +
                    '                    <div class="commenter-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="min-width: 30px; height: 30px; font-size: 14px;">' +
                    '                        ' + window.appData.userInitial +
                    '                    </div>' +
                    '                    <div class="flex-grow-1">' +
                    '                        <div class="form-group mb-2">' +
                    '                            <textarea class="form-control rounded-pill bg-light" name="content" rows="1" placeholder="Write a reply..." required></textarea>' +
                    '                        </div>' +
                    '                        <div>' +
                    '                            <button type="submit" class="btn btn-primary btn-sm">' +
                    '                                <span class="reply-btn-text">Reply</span>' +
                    '                                <span class="reply-btn-loader d-none">' +
                    '                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
                    '                                    Sending...' +
                    '                                </span>' +
                    '                            </button>' +
                    '                            <button type="button" class="btn btn-light btn-sm cancel-reply" data-comment-id="' + response.comment.id + '">Cancel</button>' +
                    '                        </div>' +
                    '                    </div>' +
                    '                </form>' +
                    '            </div>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>';
                
                // Add to DOM
                commentsSection.insertAdjacentHTML('afterbegin', commentHtml);
                
                // Clear form
                document.getElementById('commentContent').value = '';
                document.getElementById('commentContent').style.height = '';
                
                // Setup new event listeners
                const newCommentEl = document.getElementById('comment-' + response.comment.id);
                setupCommentEventListeners(newCommentEl);
                
                // Update comment count
                updateCommentCount(1);
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert('There was an error posting your comment. Please try again.');
            })
            .finally(function() {
                // Hide loader and enable button
                submitBtn.disabled = false;
                submitBtn.querySelector('.comment-btn-text').classList.remove('d-none');
                submitBtn.querySelector('.comment-btn-loader').classList.add('d-none');
            });
        });
    }

    // Handle reply form submission
    function setupReplyFormListeners(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var postId = this.getAttribute('data-post-id');
            var parentId = this.getAttribute('data-parent-id');
            var content = this.querySelector('textarea').value;
            var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var thisForm = this;
            
            if (!content.trim()) return;
            
            // Show loader and disable button
            var submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.querySelector('.reply-btn-text').classList.add('d-none');
            submitBtn.querySelector('.reply-btn-loader').classList.remove('d-none');
            
            // Send AJAX request
            fetch('/posts/' + postId + '/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    content: content,
                    parent_id: parentId,
                    _token: csrf
                })
            })
            .then(response => response.json())
            .then(function(response) {
                // Hide the reply form
                document.getElementById('reply-form-' + parentId).classList.add('d-none');
                
                // Find or create replies list
                var repliesList = document.querySelector('#comment-' + parentId + ' .replies-list');
                if (!repliesList) {
                    document.querySelector('#comment-' + parentId + ' > .d-flex > .flex-grow-1').insertAdjacentHTML(
                        'beforeend', 
                        '<div class="replies-list ml-4 mt-2"><div class="replies-count mb-2"><small class="text-muted font-weight-bold">1 reply</small></div></div>'
                    );
                    repliesList = document.querySelector('#comment-' + parentId + ' .replies-list');
                } else {
                    // Update reply count
                    var replyCount = repliesList.querySelector('.replies-count small');
                    var currentCount = parseInt(replyCount.textContent.split(' ')[0]);
                    var newCount = currentCount + 1;
                    replyCount.textContent = newCount + ' ' + (newCount === 1 ? 'reply' : 'replies');
                }
                
                // Create reply HTML
                var authorBadge = window.appData.isAuthor ? '<span class="badge badge-pill badge-primary">Author</span>' : '';
                
                var replyHtml = 
                    '<div class="reply mb-2" id="comment-' + response.comment.id + '">' +
                    '    <div class="d-flex">' +
                    '        <div class="commenter-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="min-width: 30px; height: 30px; font-size: 14px;">' +
                    '            ' + window.appData.userInitial +
                    '        </div>' +
                    '        <div class="flex-grow-1">' +
                    '            <div class="reply-bubble p-2 rounded bg-light">' +
                    '                <div class="reply-header d-flex justify-content-between">' +
                    '                    <div class="reply-user">' +
                    '                        <strong>' + response.user.name + '</strong>' +
                    '                        ' + authorBadge +
                    '                    </div>' +
                    '                    <div class="reply-actions">' +
                    '                        <button class="btn btn-sm p-0 edit-comment-btn text-muted" data-comment-id="' + response.comment.id + '"><i class="fas fa-edit"></i></button>' +
                    '                        <button class="btn btn-sm p-0 delete-comment-btn text-muted ml-2" data-comment-id="' + response.comment.id + '"><i class="fas fa-trash"></i></button>' +
                    '                    </div>' +
                    '                </div>' +
                    '                <div class="reply-content mt-1">' +
                    '                    <p class="mb-0">' + response.comment.content + '</p>' +
                    '                </div>' +
                    '            </div>' +
                    '            <div class="reply-meta ml-2 mt-1">' +
                    '                <small class="text-muted">' + response.created_at_formatted + '</small>' +
                    '            </div>' +
                    '        </div>' +
                    '    </div>' +
                    '</div>';
                
                // Add reply to list
                repliesList.insertAdjacentHTML('beforeend', replyHtml);
                
                // Setup event listeners for the new reply
                const newReplyEl = document.getElementById('comment-' + response.comment.id);
                setupCommentEventListeners(newReplyEl);
                
                // Clear the form input
                thisForm.querySelector('textarea').value = '';
                thisForm.querySelector('textarea').style.height = '';
                
                // Update comment count
                updateCommentCount(1);
            })
            .catch(function(error) {
                console.error('Error:', error);
                alert('There was an error posting your reply. Please try again.');
            })
            .finally(function() {
                // Hide loader and enable button
                submitBtn.disabled = false;
                submitBtn.querySelector('.reply-btn-text').classList.remove('d-none');
                submitBtn.querySelector('.reply-btn-loader').classList.add('d-none');
            });
        });
    }

    // Set up event listeners for all reply forms
    document.querySelectorAll('.replyCommentForm').forEach(function(form) {
        setupReplyFormListeners(form);
    });

    // Enhanced edit comment functionality with auto-expanding textarea and character limit
    function setupEditCommentListeners(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default in case it's a link
            const commentId = this.getAttribute('data-comment-id');
            if (!commentId) {
                logError('Comment ID not found on edit button');
                return;
            }
            
            const commentEl = document.getElementById('comment-' + commentId);
            if (!commentEl) {
                logError('Comment element not found with ID: comment-' + commentId);
                return;
            }
            
            // Check if this comment is already being edited
            const existingEditForm = commentEl.querySelector('.edit-form');
            if (existingEditForm) {
                return; // Don't create another edit form if one already exists
            }
            
            // Remove any other active edit forms first
            document.querySelectorAll('.edit-form').forEach(form => {
                try {
                    const contentElement = form.closest('.comment, .reply').querySelector('.comment-content p, .reply-content p');
                    if (contentElement) contentElement.classList.remove('d-none');
                    form.remove();
                } catch (error) {
                    logError('Error cleaning up edit forms', error);
                }
            });
            
            const contentEl = commentEl.querySelector('.comment-content p, .reply-content p');
            if (!contentEl) {
                logError('Content element not found in comment/reply', commentId);
                return;
            }
            
            const currentContent = contentEl.textContent.trim();
            const maxLength = 1000; // Max character limit (same as server validation)
            const isReply = commentEl.classList.contains('reply');
            
            try {
                // Create cleaner, more professional edit form
                const editFormHtml = 
                    '<div class="edit-form mt-2">' +
                    '    <div class="form-group">' +
                    '        <textarea class="form-control edit-comment-textarea" maxlength="' + maxLength + '" rows="' + (isReply ? '2' : '3') + '" placeholder="Edit your ' + (isReply ? 'reply' : 'comment') + '...">' + currentContent + '</textarea>' +
                    '        <div class="d-flex justify-content-between mt-1">' +
                    '            <small class="text-muted">Press Esc to <a href="#" class="cancel-edit-btn" data-comment-id="' + commentId + '">cancel</a>, Enter+Shift to save</small>' +
                    '            <small class="text-muted char-count">' + currentContent.length + '/' + maxLength + '</small>' +
                    '        </div>' +
                    '    </div>' +
                    '    <div class="d-flex justify-content-end">' +
                    '        <button type="button" class="btn btn-outline-secondary btn-sm mr-2 cancel-edit-btn" data-comment-id="' + commentId + '">Cancel</button>' +
                    '        <button type="button" class="btn btn-primary btn-sm save-edit-btn" data-comment-id="' + commentId + '">' +
                    '            <span class="edit-btn-text">Save Changes</span>' +
                    '            <span class="edit-btn-loader d-none">' +
                    '                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
                    '                Saving...' +
                    '            </span>' +
                    '        </button>' +
                    '    </div>' +
                    '</div>';
                
                // Hide content and show edit form
                contentEl.classList.add('d-none');
                contentEl.insertAdjacentHTML('afterend', editFormHtml);
                
                // Focus textarea and set cursor at end
                const textarea = commentEl.querySelector('.edit-comment-textarea');
                textarea.focus();
                textarea.setSelectionRange(textarea.value.length, textarea.value.length);
                
                // Auto-resize textarea as user types
                function autoResizeTextarea() {
                    textarea.style.height = 'auto';
                    textarea.style.height = (textarea.scrollHeight) + 'px';
                }
                
                // Call once to initialize
                autoResizeTextarea();
                
                // Update character count and auto-resize as user types
                textarea.addEventListener('input', function() {
                    try {
                        const charCount = commentEl.querySelector('.char-count');
                        if (charCount) {
                            charCount.textContent = this.value.length + '/' + maxLength;
                            
                            // Change color when approaching limit
                            if (this.value.length > maxLength * 0.8) {
                                charCount.classList.add('text-warning');
                            } else {
                                charCount.classList.remove('text-warning');
                            }
                        }
                        
                        autoResizeTextarea();
                    } catch (error) {
                        logError('Error updating character count', error);
                    }
                });
                
                // Setup keyboard shortcuts (Esc to cancel, Shift+Enter to save)
                textarea.addEventListener('keydown', function(e) {
                    // Esc key to cancel
                    if (e.key === 'Escape') {
                        contentEl.classList.remove('d-none');
                        const form = commentEl.querySelector('.edit-form');
                        if (form) form.remove();
                    }
                    
                    // Shift+Enter to save
                    if (e.key === 'Enter' && e.shiftKey) {
                        e.preventDefault();
                        const saveBtn = commentEl.querySelector('.save-edit-btn');
                        if (saveBtn) saveBtn.click();
                    }
                });
                
                // Setup cancel edit button (both link and button)
                commentEl.querySelectorAll('.cancel-edit-btn').forEach(function(cancelBtn) {
                    cancelBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        contentEl.classList.remove('d-none');
                        const form = commentEl.querySelector('.edit-form');
                        if (form) form.remove();
                    });
                });
                
                // Setup save edit button
                const saveBtn = commentEl.querySelector('.save-edit-btn');
                if (saveBtn) {
                    saveBtn.addEventListener('click', function() {
                        const textarea = commentEl.querySelector('.edit-comment-textarea');
                        if (!textarea) {
                            logError('Textarea not found for saving');
                            return;
                        }
                        
                        const newContent = textarea.value.trim();
                        
                        if (!newContent) {
                            showToast('Comment cannot be empty.', 'error');
                            return;
                        }
                        
                        if (newContent === currentContent) {
                            // No changes, just cancel edit mode
                            contentEl.classList.remove('d-none');
                            const form = commentEl.querySelector('.edit-form');
                            if (form) form.remove();
                            return;
                        }
                        
                        if (newContent.length > maxLength) {
                            showToast(`Text is too long. Maximum ${maxLength} characters allowed.`, 'error');
                            return;
                        }
                        
                        const csrf = document.querySelector('meta[name="csrf-token"]');
                        if (!csrf) {
                            logError('CSRF token not found');
                            showToast('Security error. Please refresh the page and try again.', 'error');
                            return;
                        }
                        
                        const csrfToken = csrf.getAttribute('content');
                        
                        // Show loader
                        this.disabled = true;
                        const textSpan = this.querySelector('.edit-btn-text');
                        const loaderSpan = this.querySelector('.edit-btn-loader');
                        if (textSpan) textSpan.classList.add('d-none');
                        if (loaderSpan) loaderSpan.classList.remove('d-none');
                        
                        // Store button reference for error handling
                        const saveButton = this;
                        
                        // Send AJAX request to update comment
                        fetch('/comments/' + commentId, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                content: newContent,
                                _token: csrfToken
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 403) {
                                    throw new Error('You are not authorized to edit this comment.');
                                } else if (response.status === 422) {
                                    return response.json().then(data => {
                                        throw new Error(data.message || 'Validation error');
                                    });
                                } else {
                                    throw new Error('Failed to update comment');
                                }
                            }
                            return response.json();
                        })
                        .then(function(data) {
                            // Update content
                            contentEl.textContent = newContent;
                            
                            // Show content and remove edit form
                            contentEl.classList.remove('d-none');
                            const form = commentEl.querySelector('.edit-form');
                            if (form) form.remove();
                            
                            // Add edited indicator if not already present
                            const timeEl = commentEl.querySelector('.comment-actions small, .reply-meta small');
                            if (timeEl && !timeEl.textContent.includes('· Edited')) {
                                timeEl.textContent = timeEl.textContent + ' · Edited';
                            }
                            
                            // Highlight the updated comment briefly
                            contentEl.classList.add('comment-updated');
                            setTimeout(() => {
                                contentEl.classList.remove('comment-updated');
                            }, 2000);
                            
                            // Show success toast
                            showToast(isReply ? 'Reply updated successfully' : 'Comment updated successfully', 'success');
                        })
                        .catch(function(error) {
                            logError('Error updating comment:', error);
                            showToast(error.message || 'Failed to update. Please try again.', 'error');
                            
                            // Return to edit mode
                            saveButton.disabled = false;
                            if (textSpan) textSpan.classList.remove('d-none');
                            if (loaderSpan) loaderSpan.classList.add('d-none');
                        });
                    });
                }
            } catch (error) {
                logError('Error setting up edit form', error);
                // Revert the UI to prevent broken state
                contentEl.classList.remove('d-none');
                showToast('Error preparing edit form. Please try again.', 'error');
            }
        });
    }

    // Handle delete comment functionality with confirmation modal
    function setupDeleteCommentListeners(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default in case it's a link
            
            const commentId = this.getAttribute('data-comment-id');
            if (!commentId) {
                logError('Comment ID not found on delete button');
                return;
            }
            
            const commentEl = document.getElementById('comment-' + commentId);
            if (!commentEl) {
                logError('Comment element not found with ID: comment-' + commentId);
                return;
            }
            
            const isReply = commentEl.classList.contains('reply');
            
            try {
                // Create and show a professional confirmation modal
                const modalId = 'deleteConfirmModal-' + Date.now();
                const modalHtml = `
                    <div class="modal fade" id="${modalId}" tabindex="-1" role="dialog" aria-labelledby="${modalId}Label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="${modalId}Label">Confirm Delete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this ${isReply ? 'reply' : 'comment'}? This action cannot be undone.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-danger confirm-delete-btn">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Check if any existing modals need to be cleaned up
                const oldModals = document.querySelectorAll('.modal[id^="deleteConfirmModal-"]');
                oldModals.forEach(modal => {
                    try {
                        if (typeof $ !== 'undefined') {
                            $(modal).modal('hide');
                        }
                        setTimeout(() => {
                            if (document.body.contains(modal)) {
                                modal.remove();
                            }
                        }, 300);
                    } catch (error) {
                        logError('Error cleaning up old modals', error);
                    }
                });
                
                // Add modal to body
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                
                const modal = document.getElementById(modalId);
                if (!modal) {
                    logError('Modal element not found after creation');
                    return;
                }
                
                // Show the modal - check if we should use jQuery or Bootstrap
                if (typeof $ !== 'undefined') {
                    $(modal).modal('show');
                } else if (typeof bootstrap !== 'undefined') {
                    const bsModal = new bootstrap.Modal(modal);
                    bsModal.show();
                } else {
                    logError('Neither jQuery nor Bootstrap found for modal');
                    modal.classList.add('show');
                    modal.style.display = 'block';
                }
                
                // Handle delete confirmation
                const deleteBtn = modal.querySelector('.confirm-delete-btn');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function() {
                        const csrf = document.querySelector('meta[name="csrf-token"]');
                        if (!csrf) {
                            logError('CSRF token not found');
                            showToast('Security error. Please refresh the page and try again.', 'error');
                            return;
                        }
                        
                        const csrfToken = csrf.getAttribute('content');
                        
                        // Add loading state to button
                        this.disabled = true;
                        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
                        
                        // Store button reference for error handling
                        const deleteButton = this;
                        
                        // Send AJAX request to delete comment
                        fetch('/comments/' + commentId, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 403) {
                                    throw new Error('You are not authorized to delete this comment.');
                                } else {
                                    throw new Error('Failed to delete comment');
                                }
                            }
                            return response.json();
                        })
                        .then(function(data) {
                            // Hide modal
                            if (typeof $ !== 'undefined') {
                                $(modal).modal('hide');
                            } else if (typeof bootstrap !== 'undefined') {
                                const bsModal = bootstrap.Modal.getInstance(modal);
                                if (bsModal) {
                                    bsModal.hide();
                                } else {
                                    modal.classList.remove('show');
                                    modal.style.display = 'none';
                                }
                            }
                            
                            // Check if this is a reply
                            if (isReply) {
                                try {
                                    // Update reply count
                                    const parentComment = commentEl.closest('.comment');
                                    if (parentComment) {
                                        const repliesList = parentComment.querySelector('.replies-list');
                                        if (repliesList) {
                                            const replyCount = repliesList.querySelector('.replies-count small');
                                            if (replyCount) {
                                                const currentCount = parseInt(replyCount.textContent.split(' ')[0]);
                                                const newCount = currentCount - 1;
                                                
                                                if (newCount === 0) {
                                                    // Remove entire replies list if this was the last reply
                                                    repliesList.remove();
                                                } else {
                                                    // Update count text
                                                    replyCount.textContent = newCount + ' ' + (newCount === 1 ? 'reply' : 'replies');
                                                }
                                            }
                                        }
                                    }
                                } catch (error) {
                                    logError('Error updating reply count', error);
                                    // Continue with deletion even if count update fails
                                }
                            }
                            
                            // Remove comment from DOM with fade effect
                            commentEl.style.transition = 'opacity 0.3s, transform 0.3s';
                            commentEl.style.opacity = '0';
                            commentEl.style.transform = 'translateX(20px)';
                            setTimeout(() => {
                                try {
                                    commentEl.remove();
                                } catch (error) {
                                    logError('Error removing comment element', error);
                                }
                                
                                // Remove modal from DOM after animation completes
                                setTimeout(() => {
                                    try {
                                        if (document.body.contains(modal)) {
                                            modal.remove();
                                        }
                                    } catch (error) {
                                        logError('Error removing modal element', error);
                                    }
                                }, 300);
                            }, 300);
                            
                            // Update comment count
                            try {
                                updateCommentCount(-1);
                            } catch (error) {
                                logError('Error updating comment count', error);
                            }
                            
                            // Show success toast
                            showToast(isReply ? 'Reply deleted successfully' : 'Comment deleted successfully', 'success');
                        })
                        .catch(function(error) {
                            logError('Error deleting comment:', error);
                            
                            // Reset button state
                            deleteButton.disabled = false;
                            deleteButton.textContent = 'Delete';
                            
                            // Show error toast
                            showToast(error.message || 'Failed to delete. Please try again.', 'error');
                        });
                    });
                }
                
                // Clean up modal when closed
                if (typeof $ !== 'undefined') {
                    $(modal).on('hidden.bs.modal', function() {
                        try {
                            // Only remove if not already removed by successful deletion
                            if (document.body.contains(modal)) {
                                modal.remove();
                            }
                        } catch (error) {
                            logError('Error removing modal on hide', error);
                        }
                    });
                }
            } catch (error) {
                logError('Error setting up delete modal', error);
                showToast('Error preparing delete confirmation. Please try again.', 'error');
            }
        });
    }

    // Function to update comment count display with error handling
    function updateCommentCount(increment) {
        try {
        var countElement = document.querySelector('.post-comments-summary span');
        if (countElement) {
                var currentText = countElement.textContent.trim();
                var currentCount = parseInt(currentText.split(' ')[0]) || 0;
                var newCount = Math.max(0, currentCount + increment); // Ensure count doesn't go below 0
            countElement.textContent = newCount + ' ' + (newCount === 1 ? 'Comment' : 'Comments');
            }
        } catch (error) {
            logError('Error updating comment count', error);
        }
    }

    // Function to show toast messages with enhanced error handling
    function showToast(message, type = 'info') {
        try {
            // Check if toast container exists, if not create it
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed bottom-0 right-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }
            
            // Create toast
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} text-white`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.setAttribute('data-delay', '3000');
            
            const icon = type === 'success' ? '<i class="fas fa-check-circle mr-2"></i>' : 
                        type === 'error' ? '<i class="fas fa-exclamation-circle mr-2"></i>' : 
                        '<i class="fas fa-info-circle mr-2"></i>';
            
            toast.innerHTML = `
                <div class="toast-header bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} text-white">
                    ${icon}<strong class="mr-auto">${type === 'success' ? 'Success' : type === 'error' ? 'Error' : 'Info'}</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Try Bootstrap 5 approach first
            try {
                if (typeof bootstrap !== 'undefined') {
                    const bsToast = new bootstrap.Toast(document.getElementById(toastId));
                    bsToast.show();
                } else if (typeof $ !== 'undefined') {
                    // Fallback to Bootstrap 4 / jQuery
                    $(toast).toast('show');
                } else {
                    // Manual fallback if Bootstrap isn't available
                    toast.style.opacity = '1';
                    setTimeout(() => {
                        if (document.body.contains(toast)) {
                            toast.style.opacity = '0';
                            setTimeout(() => toast.remove(), 300);
                        }
                    }, 3000);
                }
                
                // Remove toast when hidden (Bootstrap 5)
                toast.addEventListener('hidden.bs.toast', function() {
                    if (document.body.contains(toast)) {
                        toast.remove();
                    }
                });
                
                // Also try jQuery method (Bootstrap 4)
                if (typeof $ !== 'undefined') {
                    $(toast).on('hidden.bs.toast', function() {
                        if (document.body.contains(toast)) {
                            toast.remove();
                        }
                    });
                }
            } catch (error) {
                logError('Toast show error, using fallback', error);
                // Fallback display method
                toast.style.opacity = '1';
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        toast.style.opacity = '0';
                        setTimeout(() => {
                            if (document.body.contains(toast)) {
                                toast.remove();
                            }
                        }, 300);
                    }
                }, 3000);
            }
        } catch (error) {
            logError('Error showing toast', error);
            // Last resort - alert if everything else fails
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    }

    // Setup comment event listeners with error handling
    function setupCommentEventListeners(commentEl) {
        try {
            if (!commentEl) return;
            
            // Edit button listeners
            commentEl.querySelectorAll('.edit-comment-btn').forEach(function(btn) {
                setupEditCommentListeners(btn);
            });
            
            // Delete button listeners
            commentEl.querySelectorAll('.delete-comment-btn').forEach(function(btn) {
                setupDeleteCommentListeners(btn);
            });
            
            // Reply button listeners
            commentEl.querySelectorAll('.reply-btn').forEach(function(btn) {
                setupReplyBtnListeners(btn);
            });
            
            // Reply form listeners
            commentEl.querySelectorAll('.replyCommentForm').forEach(function(form) {
                setupReplyFormListeners(form);
            });
            
            // Cancel reply button listeners
            commentEl.querySelectorAll('.cancel-reply').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    try {
                        const commentId = this.getAttribute('data-comment-id');
                        if (!commentId) return;
                        
                        const replyForm = document.getElementById('reply-form-' + commentId);
                        if (replyForm) {
                            replyForm.classList.add('d-none');
                            const textarea = replyForm.querySelector('textarea');
                            if (textarea) textarea.value = '';
                        }
                    } catch (error) {
                        logError('Error in cancel reply handler', error);
                    }
                });
            });
        } catch (error) {
            logError('Error setting up comment event listeners', error);
        }
    }

    // Initial setup of all event listeners
    try {
        // Set up event listeners for all existing comments and replies
        document.querySelectorAll('.comment, .reply').forEach(function(commentEl) {
            setupCommentEventListeners(commentEl);
        });
    } catch (error) {
        logError('Error in initial comment setup', error);
    }

    // ... existing code ...

    // Handle reply button functionality
    function setupReplyBtnListeners(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default in case it's a link
            try {
                const commentId = this.getAttribute('data-comment-id');
                if (!commentId) {
                    logError('Comment ID not found on reply button');
                    return;
                }
                
                const replyForm = document.getElementById('reply-form-' + commentId);
                if (!replyForm) {
                    logError('Reply form not found for comment: ' + commentId);
                    return;
                }
            
            if (replyForm.classList.contains('d-none')) {
                // Hide all other reply forms first
                document.querySelectorAll('.reply-form').forEach(function(form) {
                    form.classList.add('d-none');
                });
                
                // Show this reply form
                replyForm.classList.remove('d-none');
                    const textarea = replyForm.querySelector('textarea');
                    if (textarea) {
                        textarea.focus();
                        
                        // Auto-resize as user types
                        textarea.addEventListener('input', function() {
                            this.style.height = 'auto';
                            this.style.height = (this.scrollHeight) + 'px';
                        });
                        
                        // Initial height adjustment
                        setTimeout(() => {
                            textarea.style.height = 'auto';
                            textarea.style.height = (textarea.scrollHeight) + 'px';
                        }, 0);
                    }
            } else {
                replyForm.classList.add('d-none');
                }
            } catch (error) {
                logError('Error in reply button handler', error);
            }
        });
    }

    // ... existing code ...

    // Add CSS for styled comments and reactions - clean up duplicates
    const style = document.createElement('style');
    style.textContent = `
        /* Toast container styles */
        .toast-container {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 9999;
        }
        
        .toast {
            min-width: 250px;
            opacity: 1 !important;
        }
        
        /* Enhanced Comment edit styles */
        .edit-comment-textarea {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: none; /* Using JS for auto-resize */
            min-height: 60px;
            padding: 10px;
            font-size: 14px;
            transition: border-color 0.2s ease, height 0.1s ease;
            overflow: hidden;
        }
        
        .edit-comment-textarea:focus {
            border-color: #4a76a8;
            box-shadow: 0 0 0 0.2rem rgba(74, 118, 168, 0.25);
        }
        
        /* Reply edit has smaller text area */
        .reply .edit-comment-textarea {
            min-height: 45px;
            font-size: 13px;
        }
        
        /* Comment fade effect */
        .comment, .reply {
            transition: opacity 0.3s ease, background-color 0.3s ease, transform 0.3s ease;
        }
        
        /* Comment updated highlight effect */
        .comment-updated {
            animation: highlight-comment 2s ease;
        }
        
        @keyframes highlight-comment {
            0% { background-color: rgba(255, 243, 205, 0); }
            30% { background-color: rgba(255, 243, 205, 1); }
            100% { background-color: rgba(255, 243, 205, 0); }
        }
        
        /* Edit/Delete Dropdown Styling */
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .dropdown-item {
            padding: 8px 20px;
            transition: background-color 0.2s;
        }
        
        .dropdown-item:active {
            background-color: #f8f9fa;
            color: inherit;
        }
        
        .dropdown-item.text-danger:hover {
            background-color: #fff5f5;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        
        /* Mobile responsiveness improvements */
        @media (max-width: 576px) {
            .edit-form .btn {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            
            .edit-comment-textarea {
                font-size: 13px;
                padding: 8px;
            }
            
            .char-count {
                font-size: 10px;
            }
            
            .reply-actions button {
                padding: 4px !important;
            }
            
            /* Make comment options more touch-friendly on mobile */
            .comment-header .dropdown-toggle,
            .reply-header .dropdown-toggle {
                padding: 8px 5px !important;
            }
            
            .dropdown-item {
                padding: 10px 20px;
            }
        }
        
        /* Hide reaction elements */
        .comment-reactions, .reply-reactions, 
        .reaction-icons-sm, .reaction-buttons-sm, 
        .comment-reaction-btn, .post-reactions, 
        .reaction-emoji-sm { 
            display: none !important; 
        }
    `;
    document.head.appendChild(style);
    
    // Image zoom functionality with error handling
    try {
        document.querySelectorAll('.zoom-image-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                try {
                    const imgSrc = this.getAttribute('data-image');
                    if (!imgSrc) {
                        logError('Image source not found on zoom button');
                        return;
                    }
                    
                    const fullImage = document.getElementById('fullImage');
                    if (!fullImage) {
                        logError('Full image element not found');
                        return;
                    }
                    
                    fullImage.setAttribute('src', imgSrc);
                    
                    // Open modal using available method
                    const imageModal = document.getElementById('imageModal');
                    if (!imageModal) {
                        logError('Image modal not found');
                        return;
                    }
                    
                    if (typeof bootstrap !== 'undefined') {
                        new bootstrap.Modal(imageModal).show();
                    } else if (typeof $ !== 'undefined') {
                        $(imageModal).modal('show');
                    } else {
                        imageModal.style.display = 'block';
                    }
                } catch (error) {
                    logError('Error in zoom image handler', error);
                }
            });
        });
    } catch (error) {
        logError('Error setting up zoom image functionality', error);
    }
    
    // Ensure all form textareas auto-resize as typed
    try {
        document.querySelectorAll('textarea.rounded-pill').forEach(function(textarea) {
            textarea.addEventListener('focus', function() {
                this.setAttribute('data-originalHeight', this.style.height);
            });
            
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            textarea.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.height = this.getAttribute('data-originalHeight') || '';
                }
            });
        });
    } catch (error) {
        logError('Error setting up auto-resize textareas', error);
    }
});
</script>
@endsection

<!-- Reactions Modal -->
<div class="modal fade" id="reactionsModal" tabindex="-1" role="dialog" aria-labelledby="reactionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reactionsModalLabel">Reactions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs reaction-tabs mb-3" id="reactionTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">
                            All <span class="badge badge-pill badge-secondary">{{ $reactionCounts['total'] }}</span>
                        </a>
                    </li>
                    @if($reactionCounts['like'] > 0)
                    <li class="nav-item">
                        <a class="nav-link" id="like-tab" data-toggle="tab" href="#like" role="tab" aria-controls="like" aria-selected="false">
                            👍 <span class="badge badge-pill badge-primary">{{ $reactionCounts['like'] }}</span>
                        </a>
                    </li>
                    @endif
                    @if($reactionCounts['love'] > 0)
                    <li class="nav-item">
                        <a class="nav-link" id="love-tab" data-toggle="tab" href="#love" role="tab" aria-controls="love" aria-selected="false">
                            ❤️ <span class="badge badge-pill badge-danger">{{ $reactionCounts['love'] }}</span>
                        </a>
                    </li>
                    @endif
                    @if($reactionCounts['haha'] > 0 || $reactionCounts['wow'] > 0 || $reactionCounts['sad'] > 0 || $reactionCounts['angry'] > 0)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            More
                        </a>
                        <div class="dropdown-menu">
                            @if($reactionCounts['haha'] > 0)
                            <a class="dropdown-item" id="haha-tab" data-toggle="tab" href="#haha" role="tab" aria-controls="haha" aria-selected="false">
                                😄 Haha <span class="badge badge-pill badge-warning">{{ $reactionCounts['haha'] }}</span>
                            </a>
                            @endif
                            @if($reactionCounts['wow'] > 0)
                            <a class="dropdown-item" id="wow-tab" data-toggle="tab" href="#wow" role="tab" aria-controls="wow" aria-selected="false">
                                😮 Wow <span class="badge badge-pill badge-warning">{{ $reactionCounts['wow'] }}</span>
                            </a>
                            @endif
                            @if($reactionCounts['sad'] > 0)
                            <a class="dropdown-item" id="sad-tab" data-toggle="tab" href="#sad" role="tab" aria-controls="sad" aria-selected="false">
                                😢 Sad <span class="badge badge-pill badge-info">{{ $reactionCounts['sad'] }}</span>
                            </a>
                            @endif
                            @if($reactionCounts['angry'] > 0)
                            <a class="dropdown-item" id="angry-tab" data-toggle="tab" href="#angry" role="tab" aria-controls="angry" aria-selected="false">
                                😠 Angry <span class="badge badge-pill badge-dark">{{ $reactionCounts['angry'] }}</span>
                            </a>
                            @endif
                        </div>
                    </li>
                    @endif
                </ul>
                
                <div class="tab-content" id="reactionTabContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="reaction-list">
                            @foreach(['like', 'love', 'haha', 'wow', 'sad', 'angry'] as $type)
                                @foreach($reactionsByType[$type] as $user)
                                <div class="reaction-user-item d-flex align-items-center p-2">
                                    <div class="reaction-emoji-small mr-2">
                                        @if($type == 'like') 👍
                                        @elseif($type == 'love') ❤️
                                        @elseif($type == 'haha') 😄
                                        @elseif($type == 'wow') 😮
                                        @elseif($type == 'sad') 😢
                                        @elseif($type == 'angry') 😠
                                        @endif
                                    </div>
                                    <div class="user-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="width: 40px; height: 40px; font-size: 16px;">
                                        {{ strtoupper(substr($user['name'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $user['name'] }}</div>
                                        <small class="text-muted">{{ $user['timestamp'] }}</small>
                                    </div>
                                </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    
                    @foreach(['like', 'love', 'haha', 'wow', 'sad', 'angry'] as $type)
                    <div class="tab-pane fade" id="{{ $type }}" role="tabpanel" aria-labelledby="{{ $type }}-tab">
                        <div class="reaction-list">
                            @foreach($reactionsByType[$type] as $user)
                            <div class="reaction-user-item d-flex align-items-center p-2">
                                <div class="user-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="width: 40px; height: 40px; font-size: 16px;">
                                    {{ strtoupper(substr($user['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $user['name'] }}</div>
                                    <small class="text-muted">{{ $user['timestamp'] }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commenters Modal -->
<div class="modal fade" id="commentersModal" tabindex="-1" role="dialog" aria-labelledby="commentersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentersModalLabel">Commenters</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="commenter-stats mb-3">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4">{{ $commentStats['total'] }}</div>
                            <div class="small text-muted">Total Comments</div>
                        </div>
                        <div class="col-4">
                            <div class="h4">{{ $commentStats['topLevel'] }}</div>
                            <div class="small text-muted">Top-level</div>
                        </div>
                        <div class="col-4">
                            <div class="h4">{{ $commentStats['replies'] }}</div>
                            <div class="small text-muted">Replies</div>
                        </div>
                    </div>
                </div>
                
                <h6 class="border-bottom pb-2 mb-3">Recent commenters</h6>
                <div class="commenters-list">
                    @foreach($commenters as $user)
                    <div class="commenter-item d-flex align-items-center p-2">
                        <div class="user-avatar rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mr-2" style="width: 40px; height: 40px; font-size: 16px;">
                            {{ strtoupper(substr($user['name'], 0, 1)) }}
                        </div>
                        <div class="font-weight-bold">{{ $user['name'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share this post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="share-url-container mb-3">
                    <label for="shareUrl" class="form-label">Post URL:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="shareUrl" value="{{ url('/posts/' . $post->id) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary copy-link-btn" type="button" data-toggle="tooltip" data-placement="top" title="Copy to clipboard">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="copy-feedback mt-1 text-success d-none">
                        <small><i class="fas fa-check"></i> Link copied to clipboard!</small>
                    </div>
                </div>
                
                <p class="text-center mb-2">Share on social media:</p>
                <div class="social-share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/posts/' . $post->id)) }}" target="_blank" class="btn btn-social btn-facebook" data-toggle="tooltip" title="Share on Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/posts/' . $post->id)) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-social btn-twitter" data-toggle="tooltip" title="Share on Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode(url('/posts/' . $post->id)) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-social btn-telegram" data-toggle="tooltip" title="Share on Telegram">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url('/posts/' . $post->id)) }}" target="_blank" class="btn btn-social btn-whatsapp" data-toggle="tooltip" title="Share on WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url('/posts/' . $post->id)) }}" target="_blank" class="btn btn-social btn-linkedin" data-toggle="tooltip" title="Share on LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode('Check out this post: ' . url('/posts/' . $post->id)) }}" class="btn btn-social btn-email" data-toggle="tooltip" title="Share via Email">
                        <i class="far fa-envelope"></i>
                    </a>
                </div>
                
                <div class="qr-code-container text-center">
                    <p class="mb-2">Scan QR Code to share:</p>
                    <div class="qr-code-image">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/posts/' . $post->id)) }}" alt="QR Code" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add a modal for full-screen image view -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="fullImage" class="img-fluid" src="" alt="Full size image">
            </div>
        </div>
    </div>
</div>
@endsection