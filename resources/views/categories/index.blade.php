@extends('layouts.medical-products')

@section('title', 'Categories')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Categories</h5>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add New Category</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        @forelse($categories as $category)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    @if($category->logo)
                                        <img src="{{ Storage::url($category->logo) }}" class="card-img-top p-3" alt="{{ $category->name }}" style="height: 200px; object-fit: contain;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $category->name }}</h5>
                                        <p class="card-text">{{ Str::limit($category->description, 100) }}</p>
                                        <div class="mt-auto">
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-primary">View</a>
                                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-secondary">Edit</a>
                                                @can('super-admin')
                                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                        onclick="return confirm('Are you sure you want to delete this category? This will also delete all products in this category.')">
                                                        Delete
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    No categories found. <a href="{{ route('categories.create') }}">Create your first category</a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
