@extends('layouts.medical-products')

@section('title', $product->name)

@push('styles')
<style>
    .product-image {
        width: 100%;
        border-radius: 0.5rem;
        background-color: var(--content-bg);
    }

    .product-info h5 {
        color: var(--text-muted);
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .product-info p {
        color: var(--text-color);
        margin-bottom: 0;
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

    .related-product {
        transition: all 0.2s ease;
    }

    .related-product:hover {
        background-color: var(--hover-bg);
    }

    .related-product img {
        object-fit: cover;
    }

    .related-product h6 {
        color: var(--text-color);
    }

    .related-product small {
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('medical-products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
                <div class="btn-group">
                    <a href="{{ route('medical-products.edit', $product) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </a>
                    @can('super-admin')
                    <form action="{{ route('medical-products.destroy', $product) }}" 
                          method="POST" 
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger delete-product ms-2">
                            <i class="fas fa-trash-alt me-2"></i>Delete Product
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h5 class="mb-3">Main Product Image</h5>
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" 
                                     class="product-image" 
                                     alt="{{ $product->name }}">
                            @else
                                <div class="product-image d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="fas fa-image text-muted fa-3x"></i>
                                </div>
                            @endif
                            
                            @if(!empty($product->product_images) && is_array($product->product_images) && count($product->product_images) > 0)
                                <div class="mt-4">
                                    <h5 class="mb-3">Additional Product Images</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($product->product_images as $index => $img)
                                            <img src="{{ Storage::url($img) }}" 
                                                 class="img-thumbnail product-thumbnail" 
                                                 alt="{{ $product->name }} - Additional image {{ $index + 1 }}"
                                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal{{ $index }}">
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Image Modals -->
                                @foreach($product->product_images as $index => $img)
                                    <div class="modal fade" id="imageModal{{ $index }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $index }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imageModalLabel{{ $index }}">{{ $product->name }} - Additional Image {{ $index + 1 }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ Storage::url($img) }}" 
                                                         class="img-fluid" 
                                                         alt="{{ $product->name }} - Additional image {{ $index + 1 }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="col-md-6 product-info">
                            <h1 class="h2 mb-3">{{ $product->name }}</h1>
                            <div class="mb-4">
                                <h5>Category</h5>
                                <p>{{ $product->category->name }}</p>
                            </div>
                            <div class="mb-4">
                                <h5>Description</h5>
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Detailed Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @foreach(explode("\n", $product->details) as $detail)
                                <p>
                                    <i class="fas fa-check text-success me-2"></i>
                                    {!! e($detail) !!}
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 product-info">
                        <h5>Created At</h5>
                        <p>{{ $product->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div class="mb-3 product-info">
                        <h5>Last Updated</h5>
                        <p>{{ $product->updated_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div class="product-info">
                        <h5>Product ID</h5>
                        <p>#{{ $product->id }}</p>
                    </div>
                </div>
            </div>

            @if($product->category->products->where('id', '!=', $product->id)->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Related Products</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($product->category->products->where('id', '!=', $product->id)->take(5) as $relatedProduct)
                                <a href="{{ route('medical-products.show', $relatedProduct) }}" 
                                   class="list-group-item list-group-item-action related-product d-flex align-items-center p-3">
                                    @if($relatedProduct->image)
                                        <img src="{{ Storage::url($relatedProduct->image) }}" 
                                             class="rounded me-3" 
                                             alt="{{ $relatedProduct->name }}"
                                             style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                        <div class="rounded me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 48px; height: 48px; background-color: var(--content-bg);">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $relatedProduct->name }}</h6>
                                        <small>{{ Str::limit($relatedProduct->description, 50) }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelector('.delete-product')?.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
    
    // Product image gallery functionality
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.product-thumbnail');
        const mainImage = document.querySelector('.product-image');
        
        if (thumbnails.length > 0 && mainImage) {
            // Store the original main image source
            const originalSrc = mainImage.src;
            const originalAlt = mainImage.alt;
            
            // Add click event to each thumbnail (additional images)
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function(e) {
                    // Prevent modal from opening when clicking on thumbnail
                    e.stopPropagation();
                    
                    // Change main display area to show the clicked additional image
                    mainImage.src = this.src;
                    mainImage.alt = this.alt;
                    
                    // Add active class to clicked thumbnail
                    thumbnails.forEach(t => t.classList.remove('border-primary'));
                    this.classList.add('border-primary');
                    
                    // Add a "Return to main image" button if not already present
                    let returnButton = document.querySelector('.return-to-main');
                    if (!returnButton) {
                        returnButton = document.createElement('button');
                        returnButton.classList.add('btn', 'btn-sm', 'btn-outline-secondary', 'return-to-main', 'position-absolute', 'top-0', 'end-0', 'm-2');
                        returnButton.innerHTML = '<i class="fas fa-home me-1"></i>Main Image';
                        mainImage.parentElement.style.position = 'relative';
                        mainImage.parentElement.appendChild(returnButton);
                        
                        // Add click event to return button
                        returnButton.addEventListener('click', function() {
                            mainImage.src = originalSrc;
                            mainImage.alt = originalAlt;
                            thumbnails.forEach(t => t.classList.remove('border-primary'));
                            this.style.display = 'none';
                        });
                    } else {
                        returnButton.style.display = 'block';
                    }
                });
            });
        }
    });
</script>
@endpush 