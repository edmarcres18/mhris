@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-share-alt text-primary me-2 fs-5"></i>
                        <h5 class="mb-0 fw-bold text-primary">{{ __('Share Company Emails') }}</h5>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-circle mt-1 me-2"></i>
                            <div>
                                <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}
                                <ul class="mb-0 mt-1 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('company-emails.generate-share') }}" id="shareForm" novalidate>
                        @csrf

                        <div class="form-group mb-4">
                            <label for="description" class="form-label fw-bold">{{ __('Description (optional)') }}</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light"><i class="fas fa-comment"></i></span>
                                <input type="text" class="form-control" id="description" name="description" 
                                    placeholder="What is this shared link for?" value="{{ old('description') }}">
                            </div>
                            <small class="text-muted mt-1">A brief description helps others understand the purpose of this shared link.</small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="expiration_time" class="form-label fw-bold">{{ __('Expiration Time') }} <span class="text-danger">*</span></label>
                            <div class="input-group has-validation">
                                <span class="input-group-text bg-light"><i class="fas fa-clock"></i></span>
                                <select class="form-select @error('expiration_time') is-invalid @enderror" id="expiration_time" name="expiration_time" required>
                                    <option value="10" {{ old('expiration_time') == '10' ? 'selected' : '' }}>10 minutes</option>
                                    <option value="20" {{ old('expiration_time') == '20' ? 'selected' : '' }}>20 minutes</option>
                                    <option value="30" {{ old('expiration_time') == '30' ? 'selected' : '' }}>30 minutes</option>
                                    <option value="40" {{ old('expiration_time') == '40' ? 'selected' : '' }}>40 minutes</option>
                                    <option value="50" {{ old('expiration_time') == '50' ? 'selected' : '' }}>50 minutes</option>
                                    <option value="60" {{ old('expiration_time') == '60' ? 'selected' : '' }}>1 hour</option>
                                </select>
                                @error('expiration_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted mt-1">Select how long this shared link will be accessible.</small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">{{ __('Select Company Emails to Share') }} <span class="text-danger">*</span></label>
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-info-circle me-2 fs-5"></i> 
                                <span>The shareable link will expire based on your selected time after creation.</span>
                            </div>
                            
                            <div class="card mt-3 shadow-sm">
                                <div class="card-header bg-light py-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="select-all">
                                        <label class="form-check-label fw-bold" for="select-all">Select All</label>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="5%" class="text-center">#</th>
                                                    <th>Email</th>
                                                    <th width="20%">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($companyEmails as $email)
                                                    <tr>
                                                        <td class="text-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input email-checkbox" 
                                                                    name="company_emails[]" value="{{ $email->id }}" 
                                                                    id="email-{{ $email->id }}"
                                                                    {{ (is_array(old('company_emails')) && in_array($email->id, old('company_emails'))) ? 'checked' : '' }}>
                                                                <label class="form-check-label d-none" for="email-{{ $email->id }}">Select</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <label for="email-{{ $email->id }}" class="cursor-pointer mb-0 d-block">
                                                                <i class="fas fa-envelope me-1 text-muted"></i> {{ $email->email }}
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $email->status == 'active' ? 'success' : 'secondary' }}">
                                                                {{ $email->status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">
                                                            <div class="py-3">
                                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                                <p class="mb-0">No company emails found</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @error('company_emails')
                                    <div class="card-footer bg-danger-subtle text-danger py-2">
                                        <small><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}</small>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group d-flex flex-wrap justify-content-between gap-2 mt-4">
                            <a href="{{ route('company-emails.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-share-alt me-1"></i> {{ __('Generate Shareable Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }
    
    /* Improve mobile responsiveness */
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }
        
        .form-group {
            margin-bottom: 1.5rem !important;
        }
        
        .table th, .table td {
            padding: 0.5rem;
        }
        
        .btn {
            padding: 0.375rem 0.75rem;
        }
    }
    
    /* Better focus indicators for accessibility */
    .form-control:focus, .form-select:focus, .form-check-input:focus, .btn:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Style improvements */
    .card {
        transition: all 0.3s ease;
    }
    
    .form-check-input {
        cursor: pointer;
    }
    
    /* Table hover effects */
    .table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }
    
    /* Improve button interaction */
    .btn-primary {
        transition: all 0.2s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle select all checkbox
        $('#select-all').change(function() {
            $('.email-checkbox').prop('checked', $(this).prop('checked'));
            updateRowHighlighting();
        });
        
        // Update select all if all checkboxes are selected
        $('.email-checkbox').change(function() {
            updateSelectAllState();
            updateRowHighlighting();
        });
        
        // Form validation before submit
        $('#shareForm').submit(function(e) {
            if ($('.email-checkbox:checked').length === 0) {
                e.preventDefault();
                
                // Show alert if no emails selected
                if ($('.alert-validation').length === 0) {
                    $('<div class="alert alert-danger alert-validation mb-3">' +
                        '<i class="fas fa-exclamation-circle me-2"></i> ' +
                        'Please select at least one email to share.' +
                        '</div>').insertBefore('#submitBtn').hide().fadeIn();
                        
                    // Scroll to the error
                    $('html, body').animate({
                        scrollTop: $('.alert-validation').offset().top - 100
                    }, 300);
                }
                return false;
            }
            
            // Add loading state to button
            $('#submitBtn').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                'Generating...'
            );
            
            return true;
        });
        
        // Initialization functions
        function updateRowHighlighting() {
            $('.email-checkbox').each(function() {
                if($(this).is(':checked')) {
                    $(this).closest('tr').addClass('table-primary');
                } else {
                    $(this).closest('tr').removeClass('table-primary');
                }
            });
        }
        
        function updateSelectAllState() {
            var totalCheckboxes = $('.email-checkbox').length;
            var checkedCheckboxes = $('.email-checkbox:checked').length;
            
            if (checkedCheckboxes > 0 && checkedCheckboxes === totalCheckboxes) {
                $('#select-all').prop('checked', true);
                $('#select-all').prop('indeterminate', false);
            } else if (checkedCheckboxes > 0) {
                $('#select-all').prop('indeterminate', true);
            } else {
                $('#select-all').prop('checked', false);
                $('#select-all').prop('indeterminate', false);
            }
        }
        
        // Initialize state on page load
        updateRowHighlighting();
        updateSelectAllState();
    });
</script>
@endsection
@endsection 