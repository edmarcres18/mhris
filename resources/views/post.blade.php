@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card post-card">
                <div class="post-header">
                    <h1 class="post-title">{{ $post->title }}</h1>
                </div>

                <div class="post-content">
                    {{ $post->content }}
                </div>

                <div class="post-meta">
                    <span><i class="fas fa-user"></i> {{ $post->user->first_name }}</span>
                    <span><i class="fas fa-calendar-alt"></i> {{ $post->created_at->format('M d, Y H:i') }}</span>
                    <span><i class="fas fa-clock"></i> Last updated: {{ $post->updated_at->format('M d, Y H:i') }}</span>
                </div>

                <div class="post-footer">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* Post Display Styles */
.post-card {
    transition: box-shadow 0.3s ease-in-out;
}

.post-card:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.post-header {
    background: linear-gradient(45deg, #007bff, #0056b3);
    padding: 1.5rem;
    border-radius: 0.25rem 0.25rem 0 0;
}

.post-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0;
    color: #ffffff;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

.post-content {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #333;
    padding: 1.5rem;
}

.post-meta {
    display: flex;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
    font-size: 0.9rem;
    color: #6c757d;
}

.post-meta span {
    display: flex;
    align-items: center;
}

.post-meta i {
    margin-right: 0.5rem;
}

.post-footer {
    padding: 1rem 1.5rem;
    background-color: #f1f3f5;
    border-top: 1px solid #e9ecef;
}

.btn-back {
    transition: all 0.3s ease;
}

.btn-back:hover {
    background-color: #007bff;
    color: #ffffff;
}

@media (max-width: 768px) {
    .post-meta {
        flex-direction: column;
    }

    .post-meta span {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection
