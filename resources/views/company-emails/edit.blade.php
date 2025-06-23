@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <!-- Navigation links -->
    <div class="mb-4">
        <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
            @canany(['super-admin', 'admin', 'hrcompliance'])
            <a href="{{ route('company-emails.index') }}" class="contribution-link {{ request()->routeIs('company-emails*') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Company Emails</span>
                    <small class="description">Email Accounts</small>
                </div>
            </a>
            @endcanany
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Company Email</h3>
                    <div class="card-tools">
                        <a href="{{ route('company-emails.index') }}" class="btn btn-secondary btn-sm rounded-pill">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Alert box -->
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fs-4"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading">Important Note</h6>
                                <p class="mb-0">Updating this email's credentials may affect system communications. Ensure that you update any services that rely on this email account.</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('company-emails.update', $companyEmail->id) }}" class="form-horizontal">
                        @csrf
                        @method('PUT')

                        <div class="form-group row mb-4">
                            <label for="email" class="col-md-3 col-form-label text-md-end">
                                <i class="fas fa-envelope me-1 text-primary"></i> Email Address
                            </label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email', $companyEmail->email) }}" required autocomplete="email">
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    This is the email address used for company communications.
                                </small>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="password" class="col-md-3 col-form-label text-md-end">
                                <i class="fas fa-key me-1 text-primary"></i> Password
                            </label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input id="password" type="text" class="form-control @error('password') is-invalid @enderror" 
                                        name="password" value="{{ old('password', $companyEmail->password) }}" autocomplete="new-password">
                                    
                                    <button class="btn btn-outline-secondary" type="button" id="generatePassword" title="Generate Strong Password">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>

                                    <button class="btn btn-outline-secondary btn-copy" type="button" data-clipboard-target="#password" title="Copy Password">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    Password must be at least 8 characters long.
                                </small>
                                
                                <div class="password-strength mt-2">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%" id="passwordStrength"></div>
                                    </div>
                                    <small class="text-muted" id="passwordStrengthText">Password strength indicator</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label for="status" class="col-md-3 col-form-label text-md-end">
                                <i class="fas fa-toggle-on me-1 text-primary"></i> Status
                            </label>
                            <div class="col-md-6">
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="Active" {{ (old('status', $companyEmail->status) == 'Active') ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ (old('status', $companyEmail->status) == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                                
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row mb-4">
                            <label class="col-md-3 col-form-label text-md-end">
                                <i class="fas fa-info-circle me-1 text-primary"></i> Last Updated
                            </label>
                            <div class="col-md-6">
                                <p class="form-control-plaintext">
                                    @if(isset($companyEmail->updated_at))
                                        {{ $companyEmail->updated_at->format('F j, Y, g:i a') }}
                                        <small class="text-muted d-block">{{ $companyEmail->updated_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">Not available</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Email Account
                                </button>
                                <a href="{{ route('company-emails.index') }}" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* Password strength styles */
    .password-strength .progress {
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script>
    $(document).ready(function() {
        // Check for session messages and display them
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";
        
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: successMessage,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#28a745',
                color: '#fff',
                iconColor: 'white'
            });
        }
        
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#dc3545',
                color: '#fff',
                iconColor: 'white'
            });
        }

        // Password generator
        const generateBtn = $('#generatePassword');
        const passwordField = $('#password');
        const passwordStrength = $('#passwordStrength');
        const passwordStrengthText = $('#passwordStrengthText');
        
        generateBtn.on('click', function() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-+=";
            let password = "";
            
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset[randomIndex];
            }
            
            passwordField.val(password);
            checkPasswordStrength(password);
            
            // Show toast notification
            Swal.fire({
                icon: 'success',
                title: 'Generated!',
                text: 'Strong password generated',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                background: '#28a745',
                color: '#fff',
                iconColor: 'white'
            });
        });
        
        // Password strength checker
        passwordField.on('input', function() {
            const password = $(this).val();
            checkPasswordStrength(password);
        });
        
        // Check initial password strength
        checkPasswordStrength(passwordField.val());
        
        function checkPasswordStrength(password) {
            // Basic strength calculation
            let strength = 0;
            
            if (password.length >= 8) strength += 20;
            if (password.length >= 12) strength += 10;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            
            // Update UI
            passwordStrength.css('width', strength + '%');
            
            if (strength < 40) {
                passwordStrength.removeClass().addClass('progress-bar bg-danger');
                passwordStrengthText.text('Password strength: Too weak');
            } else if (strength < 70) {
                passwordStrength.removeClass().addClass('progress-bar bg-warning');
                passwordStrengthText.text('Password strength: Medium');
            } else {
                passwordStrength.removeClass().addClass('progress-bar bg-success');
                passwordStrengthText.text('Password strength: Strong');
            }
        }
        
        // Copy to clipboard functionality
        const clipboard = new ClipboardJS('.btn-copy');
        
        clipboard.on('success', function(e) {
            e.trigger.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(function() {
                e.trigger.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
            e.clearSelection();
            
            // Show copy success toast
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Password copied to clipboard',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                background: '#28a745',
                color: '#fff',
                iconColor: 'white'
            });
        });
    });
</script>
@endsection 