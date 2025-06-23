@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <!-- Enhanced professional-looking link buttons -->
    <div class="mb-4">
        <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
            @canany(['super-admin', 'admin', 'hrcompliance'])
            <a href="{{ route('company-emails.index') }}" class="contribution-link {{ request()->routeIs('company-emails*') && !request()->routeIs('company-emails.share*') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Company Emails</span>
                    <small class="description">Email Accounts</small>
                </div>
            </a>
            <a href="{{ route('company-emails.shareable-links') }}" class="contribution-link {{ request()->routeIs('company-emails.share*') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-share-alt"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Shareable Links</span>
                    <small class="description">Manage Temporary Access</small>
                </div>
            </a>
            @endcanany
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Company Email Management</h3>
                    <div class="card-tools">
                        @canany(['super-admin', 'admin', 'hrcompliance'])
                        <a href="{{ route('company-emails.shareable-links') }}" class="btn btn-info btn-sm rounded-pill me-2">
                            Manage Shared Links <i class="fas fa-share-alt"></i>
                        </a>
                        <a href="{{ route('company-emails.share-form') }}" class="btn btn-primary btn-sm rounded-pill me-2">
                            Share Emails <i class="fas fa-share-alt"></i>
                        </a>
                        <a href="{{ route('company-emails.create') }}" class="btn btn-success btn-sm rounded-pill">
                            Add Email <i class="fas fa-plus-circle"></i>
                        </a>
                        @endcanany
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="company-emails-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email Address</th>
                                <th>Password</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($companyEmails as $email)
                                <tr>
                                    <td>{{ $email->id }}</td>
                                    <td><strong>{{ $email->email }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="password-text me-2">{{ $email->password }}</span>
                                            <button class="btn btn-sm btn-outline-secondary btn-copy" data-clipboard-text="{{ $email->password }}" title="Copy password">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        @if(isset($email->status))
                                            <span class="badge {{ $email->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $email->status }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($email->updated_at))
                                            <small>{{ $email->updated_at->diffForHumans() }}</small>
                                        @else
                                            <small class="text-muted">Not available</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('company-emails.edit',$email->id) }}">
                                                    <i class="fas fa-edit"></i>&nbsp;Edit
                                                </a>
                                                <a class="dropdown-item quick-share" href="#" data-email-id="{{ $email->id }}" data-email-address="{{ $email->email }}">
                                                    <i class="fas fa-share-alt"></i>&nbsp;Quick Share
                                                </a>
                                                @if(Auth::user()->hasRole('Super Admin'))
                                                <form action="{{ route('company-emails.destroy', $email->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item delete-email" data-email-address="{{ $email->email }}">
                                                        <i class="fas fa-trash"></i>&nbsp;Delete
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-inbox fs-3 text-muted mb-2"></i>
                                            <p class="mb-0">No company emails have been added yet</p>
                                            <a href="{{ route('company-emails.create') }}" class="btn btn-sm btn-primary mt-2">
                                                <i class="fas fa-plus-circle me-1"></i> Add your first email
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
    /* Toast styles */
    .colored-toast.swal2-icon-success {
        box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
    }
    .colored-toast.swal2-icon-error {
        box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
    }
    
    /* Password styling */
    .password-text {
        font-family: monospace;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script>
    $(document).ready(function () {
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
        
        // Initialize DataTable
        $('#company-emails-table').DataTable();
        
        // Initialize Clipboard.js
        var clipboard = new ClipboardJS('.btn-copy');
        
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

        // Handle delete confirmation
        $(document).on('click', '.delete-email', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            let emailAddress = $(this).data('email-address');

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete "${emailAddress}"? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Handle Quick Share
        $(document).on('click', '.quick-share', function(e) {
            e.preventDefault();
            let emailId = $(this).data('email-id');
            let emailAddress = $(this).data('email-address');

            Swal.fire({
                title: 'Quick Share',
                text: `Generate a 10-minute shareable link for "${emailAddress}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Generate Link',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                input: 'text',
                inputPlaceholder: 'Description (optional)',
                inputAttributes: {
                    autocapitalize: 'off'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading indicator
                    Swal.fire({
                        title: 'Generating link...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Create form data and submit
                    let formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('company_emails[]', emailId);
                    if (result.value) {
                        formData.append('description', result.value);
                    }

                    // Submit form via AJAX
                    $.ajax({
                        url: '{{ route("company-emails.generate-share") }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Redirect to the share link page
                            window.location.href = '{{ route("company-emails.share-link", "") }}/' + response.token;
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to generate link. Please try again.',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection 