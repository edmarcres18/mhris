@extends('layouts.medical-products')

@section('title', 'Edit Medical Product')

@push('styles')
<style>
    .form-label {
        color: var(--text-color);
        font-weight: 500;
    }

    .form-control,
    .form-select {
        background-color: var(--sidebar-bg);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .form-control:focus,
    .form-select:focus {
        background-color: var(--sidebar-bg);
        border-color: var(--primary-color);
        color: var(--text-color);
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.1);
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    .form-text {
        color: var(--text-muted);
    }

    .invalid-feedback {
        color: #dc3545;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    }

    .preview-image {
        max-height: 200px;
        border-radius: 0.5rem;
        background-color: var(--content-bg);
    }

    .current-image {
        max-height: 200px;
        border-radius: 0.5rem;
        background-color: var(--content-bg);
    }
</style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0">Edit Medical Product</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('medical-products.update', $product) }}" 
                              method="POST" 
                              enctype="multipart/form-data"
                              class="needs-validation" 
                              novalidate>
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" 
                                        id="category_id" 
                                        class="form-select select2 @error('category_id') is-invalid @enderror" 
                                        required
                                        data-placeholder="Select a category">
                                    <option value=""></option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $product->name) }}" 
                                       placeholder="Enter product name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" 
                                          id="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="Enter product description"
                                          required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="details" class="form-label">Details</label>
                                <textarea name="details" 
                                          id="details" 
                                          class="form-control @error('details') is-invalid @enderror" 
                                          rows="5" 
                                          placeholder="Enter detailed product information"
                                          required>{{ old('details', $product->details) }}</textarea>
                                @error('details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input type="checkbox" 
                                           name="is_featured" 
                                           id="is_featured" 
                                           class="form-check-input @error('is_featured') is-invalid @enderror"
                                           value="1"
                                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning me-1"></i>Feature this product
                                    </label>
                                    <div class="form-text">
                                        Featured products will be highlighted and displayed prominently on the homepage
                                    </div>
                                    @error('is_featured')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Main Product Image</label>
                                @if($product->image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="current-image">
                                        <div class="form-text mt-1">Current main product image</div>
                                    </div>
                                @endif
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       class="form-control @error('image') is-invalid @enderror"
                                       accept="image/*"
                                       placeholder="Choose new main product image">
                                <div class="form-text">This will be the primary image displayed for your product. Maximum file size: 10MB.</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="product_images" class="form-label">Additional Product Images (Max 3)</label>
                                @if(!empty($product->product_images) && is_array($product->product_images))
                                    <div class="mb-2 d-flex flex-wrap gap-2">
                                        @foreach($product->product_images as $index => $img)
                                        <div class="position-relative current-additional-image">
                                            <img src="{{ Storage::url($img) }}" 
                                                alt="Additional image {{ $index + 1 }}" 
                                                class="current-image" 
                                                style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="form-text mb-2">
                                        <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Uploading new images will replace all existing additional images</small>
                                    </div>
                                @endif
                                <input type="file" 
                                       name="product_images[]" 
                                       id="product_images" 
                                       class="form-control @error('product_images') is-invalid @enderror"
                                       accept="image/*"
                                       multiple
                                       placeholder="Choose additional product images">
                                <div class="form-text">These secondary images will appear in the product gallery. Select up to 3 additional images. Maximum file size per image: 10MB.</div>
                                <div id="preview_images" class="d-flex flex-wrap gap-2 mt-2"></div>
                                @error('product_images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('product_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('medical-products.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: $(this).data('placeholder')
        });

        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // Main product image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 10 * 1024 * 1024) {
                    this.value = '';
                    alert('File size must be less than 10MB');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.classList.add('preview-image', 'mt-2');
                    
                    const container = document.getElementById('image').parentElement;
                    const oldPreview = container.querySelector('img:not(.current-image)');
                    if (oldPreview) {
                        container.removeChild(oldPreview);
                    }
                    container.appendChild(preview);
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Additional product images preview
        document.getElementById('product_images').addEventListener('change', function(e) {
            const files = this.files;
            const previewContainer = document.getElementById('preview_images');
            
            // Clear previous previews
            previewContainer.innerHTML = '';
            
            // Validate number of files
            if (files.length > 3) {
                alert('You can only select up to 3 additional images');
                this.value = '';
                return;
            }
            
            // Process each file
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Validate file size
                if (file.size > 10 * 1024 * 1024) {
                    alert(`Image "${file.name}" exceeds the 10MB size limit`);
                    this.value = '';
                    previewContainer.innerHTML = '';
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewWrapper = document.createElement('div');
                    previewWrapper.classList.add('position-relative');
                    
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.classList.add('preview-image');
                    preview.style.width = '100px';
                    preview.style.height = '100px';
                    preview.style.objectFit = 'cover';
                    preview.style.borderRadius = '0.5rem';
                    
                    // Add filename as a tooltip
                    preview.title = file.name;
                    
                    previewWrapper.appendChild(preview);
                    previewContainer.appendChild(previewWrapper);
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush 
