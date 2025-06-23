@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title m-0 font-weight-bold text-primary">Share Credentials</h3>
                    <div class="card-tools">
                        <a href="{{ route('credentials.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('credentials.generate-share') }}" method="POST" id="share-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info bg-light border-left border-info">
                                    <i class="fas fa-info-circle text-info"></i>
                                    <span class="ml-2">Select credentials to share and set an expiration time for the shareable link.</span>
                                </div>
                                
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <div class="form-group">
                                    <label for="credential-selector" class="font-weight-bold">Select Credentials to Share</label>
                                    <div class="p-3 border rounded bg-white shadow-sm credential-container">
                                        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="select-all">
                                                <label class="custom-control-label font-weight-bold" for="select-all">
                                                    Select All Credentials
                                                </label>
                                            </div>
                                            <span class="ml-auto badge badge-primary credential-counter">0 selected</span>
                                        </div>
                                        <div class="row">
                                            @foreach($credentials as $credential)
                                                <div class="col-md-4 mb-3">
                                                    <div class="custom-control custom-checkbox credential-item">
                                                        <input class="custom-control-input credential-checkbox" type="checkbox" 
                                                            name="credential_ids[]" 
                                                            value="{{ $credential->id }}" 
                                                            id="credential-{{ $credential->id }}">
                                                        <label class="custom-control-label d-flex flex-column" for="credential-{{ $credential->id }}">
                                                            <span class="font-weight-bold">{{ $credential->employee->last_name }}, {{ $credential->employee->first_name }}</span>
                                                            <small class="text-muted">{{ $credential->company_email ?: 'No Email' }}</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description" class="font-weight-bold">Description (Optional)</label>
                                    <input type="text" class="form-control" id="description" name="description" 
                                        placeholder="e.g., 'IT Department Credentials', 'Project X Access'">
                                    <small class="form-text text-muted">Add a descriptive label to remember what this link is for.</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="expiration" class="font-weight-bold">Link Expiration</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="expiration" name="expiration" 
                                            min="1" max="720" value="24">
                                        <div class="input-group-append">
                                            <span class="input-group-text">hours</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Set how long this link will remain valid. Maximum: 720 hours (30 days).</small>
                                </div>
                                
                                <div class="alert alert-warning border-left border-warning">
                                    <i class="fas fa-exclamation-triangle text-warning"></i> 
                                    <strong class="ml-2">Important:</strong> 
                                    Anyone with the generated link will be able to view the selected credentials until the link expires.
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="share-btn" disabled>
                                <i class="fas fa-share-alt mr-2"></i> Generate Shareable Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    /* Professional credential styling */
    .credential-container {
        max-height: 400px;
        overflow-y: auto;
        border-color: #e9ecef !important;
    }
    
    .credential-item {
        transition: all 0.2s ease;
        padding: 10px;
        border-radius: 5px;
    }
    
    .credential-item:hover {
        background-color: #f8f9fa;
    }
    
    .credential-item .custom-control-input:checked ~ .custom-control-label {
        color: #007bff;
    }
    
    .credential-item .custom-control-input:checked ~ .custom-control-label::before {
        border-color: #007bff;
        background-color: #007bff;
    }
    
    .border-left {
        border-left: 4px solid;
    }
    
    .border-info {
        border-left-color: #17a2b8 !important;
    }
    
    .border-warning {
        border-left-color: #ffc107 !important;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .credential-counter {
            display: none;
        }
    }
    
    /* Button styling */
    #share-btn {
        transition: all 0.3s ease;
    }
    
    #share-btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    #share-btn:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Custom checkbox styling */
    .custom-control-input:checked ~ .custom-control-label::before {
        border-color: #007bff;
        background-color: #007bff;
    }
    
    .custom-checkbox .custom-control-label::before {
        border-radius: 3px;
    }
    
    /* Counter badge */
    .credential-counter {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Handle select all checkbox
        $('#select-all').change(function() {
            $('.credential-checkbox').prop('checked', $(this).prop('checked'));
            toggleSubmitButton();
            updateCounter();
            
            // Add animation
            if($(this).prop('checked')) {
                $('.credential-item').addClass('bg-light');
            } else {
                $('.credential-item').removeClass('bg-light');
            }
        });
        
        // Handle individual checkbox changes
        $('.credential-checkbox').change(function() {
            toggleSubmitButton();
            updateCounter();
            
            // Update select all checkbox state
            if ($('.credential-checkbox:checked').length === $('.credential-checkbox').length) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
            
            // Add highlight effect
            if($(this).prop('checked')) {
                $(this).closest('.credential-item').addClass('bg-light');
            } else {
                $(this).closest('.credential-item').removeClass('bg-light');
            }
        });
        
        // Enable/disable submit button based on selection
        function toggleSubmitButton() {
            if ($('.credential-checkbox:checked').length > 0) {
                $('#share-btn').prop('disabled', false);
            } else {
                $('#share-btn').prop('disabled', true);
            }
        }
        
        // Update counter badge
        function updateCounter() {
            var count = $('.credential-checkbox:checked').length;
            $('.credential-counter').text(count + ' selected');
            
            if(count > 0) {
                $('.credential-counter').removeClass('badge-primary').addClass('badge-success');
            } else {
                $('.credential-counter').removeClass('badge-success').addClass('badge-primary');
            }
        }
        
        // Hover effects
        $('.credential-item').hover(
            function() {
                $(this).addClass('shadow-sm');
            },
            function() {
                $(this).removeClass('shadow-sm');
            }
        );
    });
</script>
@endsection 