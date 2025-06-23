@extends('layouts.medical-products')

@section('title', 'Medical Products')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Medical Products List</h1>
        <a href="{{ route('medical-products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Add New Product
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search products...">
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    @foreach($categories as $category)
        <div class="card mb-4 category-section" data-category="{{ $category->name }}">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">{{ $category->name }}</h2>
            </div>
            <div class="card-body">
                @if($category->products->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach($category->products as $product)
                            <div class="col">
                                <div class="card product-card">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" 
                                             class="card-img-top product-image" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="card-img-top product-image d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted fa-3x"></i>
                                        </div>
                                    @endif
                                    
                                    @if(!empty($product->product_images) && is_array($product->product_images) && count($product->product_images) > 0)
                                        <div class="position-absolute bottom-0 end-0 p-2">
                                            <span class="badge bg-info">
                                                <i class="fas fa-images me-1"></i>+{{ count($product->product_images) }} more
                                            </span>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        @if($product->is_featured)
                                            <div class="mb-2">
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-star me-1"></i>Featured
                                                </span>
                                            </div>
                                        @else
                                            <div class="mb-2">
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-star me-1"></i>Not Featured
                                                </span>
                                            </div>  
                                        @endif
                                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <div class="btn-group w-100">
                                            <a href="{{ route('medical-products.show', $product) }}" 
                                               class="btn btn-outline-primary">
                                                <i class="fas fa-eye me-2"></i>View
                                            </a>
                                            <a href="{{ route('medical-products.edit', $product) }}" 
                                               class="btn btn-outline-secondary">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                            @can('super-admin')
                                            <form action="{{ route('medical-products.destroy', $product) }}" 
                                                  method="POST" 
                                                  class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-outline-danger delete-product"
                                                        data-product-name="{{ $product->name }}">
                                                    <i class="fas fa-trash-alt me-2"></i>Delete
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No products found in this category.</p>
                @endif
            </div>
        </div>
    @endforeach
@endsection
@push('styles')
<style>
    .product-image {
        height: 200px;
        object-fit: cover;
        background-color: var(--content-bg);
    }

    .product-card {
        height: 100%;
        transition: transform 0.2s ease;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .card-title {
        color: var(--text-color);
    }

    .card-text {
        color: var(--text-muted);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
    }

    .btn-outline-secondary {
        color: var(--text-muted);
        border-color: var(--border-color);
    }

    .btn-outline-secondary:hover {
        background-color: var(--hover-bg);
        border-color: var(--text-muted);
        color: var(--text-color);
    }

    .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete product functionality
        document.querySelectorAll('.delete-product').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const productName = this.dataset.productName;
                
                Swal.fire({
                    title: 'Are you sure?',
                    html: `You are about to delete <strong>${productName}</strong>.<br>This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Search and filter functionality
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const productCards = document.querySelectorAll('.product-card');
        const categorySections = document.querySelectorAll('.category-section');

        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = categoryFilter.value;

            categorySections.forEach(section => {
                const categoryName = section.dataset.category;
                const productsInSection = section.querySelectorAll('.product-card');
                let hasVisibleProducts = false;

                productsInSection.forEach(card => {
                    const productName = card.querySelector('.card-title').textContent.toLowerCase();
                    const productDescription = card.querySelector('.card-text').textContent.toLowerCase();
                    const matchesSearch = productName.includes(searchTerm) || productDescription.includes(searchTerm);
                    const matchesCategory = !selectedCategory || categoryName === selectedCategory;

                    if (matchesSearch && matchesCategory) {
                        card.closest('.col').style.display = '';
                        hasVisibleProducts = true;
                    } else {
                        card.closest('.col').style.display = 'none';
                    }
                });

                section.style.display = hasVisibleProducts ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterProducts);
        categoryFilter.addEventListener('change', filterProducts);
    });
</script>
@endpush 
