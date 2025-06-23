@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Create System Update</h1>
                <a href="{{ route('system-updates.index') }}" class="d-none d-sm-inline-block btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Back to List
                </a>
            </div>
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-light p-2 rounded">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('system-updates.index') }}" class="text-decoration-none">System Updates</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-plus-circle me-2"></i>
                        <h6 class="m-0 font-weight-bold">New System Update</h6>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <h5 class="alert-heading fw-bold mb-2">
                                <i class="fas fa-exclamation-triangle me-1"></i> Please check the form
                            </h5>
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('system-updates.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-4">
                                    <label for="title" class="form-label fw-bold">
                                        <i class="fas fa-heading me-1 text-primary"></i> Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                        class="form-control form-control-lg shadow-sm @error('title') is-invalid @enderror" 
                                        id="title" 
                                        name="title" 
                                        value="{{ old('title') }}" 
                                        placeholder="Enter update title"
                                        maxlength="255"
                                        required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-1 text-primary"></i> Description <span class="text-danger">*</span>
                                    </label>
                                    <textarea 
                                        class="form-control shadow-sm @error('description') is-invalid @enderror" 
                                        id="description" 
                                        name="description" 
                                        rows="10" 
                                        placeholder="Enter detailed information about the update"
                                        required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-cog me-1"></i> Settings
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="published_at" class="form-label fw-bold">
                                                <i class="fas fa-calendar-alt me-1 text-primary"></i> Publish Date
                                            </label>
                                            <input 
                                                type="datetime-local" 
                                                class="form-control shadow-sm @error('published_at') is-invalid @enderror" 
                                                id="published_at" 
                                                name="published_at" 
                                                value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                                            @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle me-1"></i> Leave empty to use current time
                                            </small>
                                        </div>
                                        
                                        <div class="form-check form-switch mb-4">
                                            <input 
                                                type="checkbox" 
                                                class="form-check-input" 
                                                id="is_active" 
                                                name="is_active" 
                                                value="1" 
                                                role="switch"
                                                {{ old('is_active') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="is_active">
                                                <i class="fas fa-toggle-on me-1 text-primary"></i> Active Status
                                            </label>
                                            <div class="form-text">
                                                Enable to make this update visible to users
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-info shadow-sm">
                                            <i class="fas fa-info-circle me-1"></i> This update will be authored by <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex mt-4 border-top pt-4">
                            <a href="{{ route('system-updates.index') }}" class="btn btn-light border shadow-sm me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fas fa-save me-1"></i> Save Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add rich text editor to description field
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('description', {
                height: 400,
                removePlugins: 'elementspath',
                resize_enabled: false,
                toolbar: [
                    { name: 'document', items: [ 'Source' ] },
                    { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                    { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll' ] },
                    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
                    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                    { name: 'links', items: [ 'Link', 'Unlink' ] },
                    { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
                    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                    { name: 'tools', items: [ 'Maximize' ] }
                ]
            });
        }
        
        // Form validation
        (function () {
            'use strict'
            
            // Fetch all forms we want to apply custom validation to
            var forms = document.querySelectorAll('.needs-validation')
            
            // Loop over them and prevent submission
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
    });
</script>
@endsection 