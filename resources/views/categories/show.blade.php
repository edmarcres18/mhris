@extends('layouts.medical-products')

@section('title', 'Category Details')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $category->name }}</h5>
                    <div>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-primary me-2"><i class="fas fa-edit me-2"></i>Edit</a>
                        @can('super-admin')
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category? This will also delete all products in this category.')"><i class="fas fa-trash-alt me-2"></i>Delete</button>
                        </form>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    @if($category->logo)
                        <div class="text-center mb-4">
                            <img src="{{ Storage::url($category->logo) }}" alt="{{ $category->name }}" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Description</h6>
                        <p>{{ $category->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Products in this Category</h6>
                        @if($category->products->count() > 0)
                            <div class="list-group">
                                @foreach($category->products as $product)
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            @if($product->image)
                                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="me-3" style="height: 40px; width: 40px; object-fit: cover;">
                                            @endif
                                            {{ $product->name }}
                                        </div>
                                        <a href="{{ route('medical-products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-2"></i>Edit</a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No products in this category yet.</p>
                        @endif
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to Categories</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 