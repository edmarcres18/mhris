@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title m-0 font-weight-bold text-primary">My Shareable Credential Links</h3>
                    <div class="card-tools">
                        <a href="{{ route('credentials.share-form') }}" class="btn btn-primary btn-sm rounded-pill">
                            <i class="fas fa-plus-circle mr-1"></i> Create New Link
                        </a>
                        <a href="{{ route('credentials.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill ml-2">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(Session::has('success'))
                        <input type="hidden" id="success-message" value="{{ Session::get('success') }}">
                    @endif
                    @if(Session::has('error'))
                        <input type="hidden" id="error-message" value="{{ Session::get('error') }}">
                    @endif
                    
                    @if($shareableLinks->isEmpty())
                        <div class="alert alert-info bg-light border-left border-info">
                            <i class="fas fa-info-circle text-info"></i> 
                            <span class="ml-2">You haven't created any shareable links yet.
                            <a href="{{ route('credentials.share-form') }}" class="alert-link">Create your first shareable link</a>.</span>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="shareable-links-table" class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Description</th>
                                        <th>Created On</th>
                                        <th>Expires On</th>
                                        <th>Status</th>
                                        <th>Credentials Count</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shareableLinks as $link)
                                        <tr class="{{ !$link->isActive() ? 'table-danger' : '' }}">
                                            <td class="font-weight-medium">{{ $link->description ?: 'No description' }}</td>
                                            <td>{{ $link->created_at->format('M d, Y h:i A') }}</td>
                                            <td>{{ $link->expires_at->format('M d, Y h:i A') }}</td>
                                            <td>
                                                @if($link->isActive())
                                                    <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle mr-1"></i> Active</span>
                                                    <small class="d-block mt-1 text-muted">{{ $link->remainingTimeInMinutes() }} minutes left</small>
                                                @else
                                                    <span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle mr-1"></i> Expired</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info px-3 py-2">{{ $link->credentials->count() }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-circle mr-1 copy-link" 
                                                        data-link="{{ route('credentials.access-shared', $link->token) }}"
                                                        data-toggle="tooltip" title="Copy Link">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    <a href="{{ route('credentials.access-shared', $link->token) }}" 
                                                        class="btn btn-outline-info btn-sm rounded-circle mr-1"
                                                        target="_blank" data-toggle="tooltip" title="Open Link">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                    <a href="{{ route('credentials.tracking', $link->id) }}"
                                                        class="btn btn-outline-primary btn-sm rounded-circle mr-1"
                                                        data-toggle="tooltip" title="View Tracking">
                                                        <i class="fas fa-chart-line"></i>
                                                    </a>
                                                    <form action="{{ route('credentials.delete-share', $link->id) }}" method="POST" class="delete-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" 
                                                            data-toggle="tooltip" title="Delete Link">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    /* Table styling */
    .table-responsive {
        overflow-x: auto;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.02);
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        border-bottom-width: 1px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        vertical-align: middle;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    /* Badge styling */
    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
        border-radius: 30px;
        font-size: 75%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Alert styling */
    .border-left {
        border-left: 4px solid;
    }
    
    .border-info {
        border-left-color: #17a2b8 !important;
    }
    
    /* Card styling */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,0.075);
    }
    
    /* Button styling */
    .btn {
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-sm.rounded-circle {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Expired row styling */
    tr.table-danger td {
        background-color: rgba(220, 53, 69, 0.05);
    }
    
    /* Font weights */
    .font-weight-medium {
        font-weight: 500;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .card-tools {
            margin-top: 15px;
            width: 100%;
            display: flex;
        }
        
        .btn {
            margin-top: 5px;
        }
        
        .d-flex.justify-content-center {
            justify-content: flex-start !important;
        }
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Common toast configuration
        const toastConfig = {
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#fff',
            color: '#424242',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast'
            }
        };

        // Success message toast
        const successMessage = $('#success-message').val();
        if (successMessage) {
            Swal.fire({
                ...toastConfig,
                icon: 'success',
                title: 'Success',
                text: successMessage,
                background: '#28a745',
                color: '#fff'
            });
        }
        
        // Error message toast
        const errorMessage = $('#error-message').val();
        if (errorMessage) {
            Swal.fire({
                ...toastConfig,
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                background: '#dc3545',
                color: '#fff'
            });
        }

        // Delete confirmation
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete this shareable link!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                buttonsStyling: true,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary mr-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Copy link to clipboard with animation
        $('.copy-link').on('click', function(e) {
            e.preventDefault();
            const link = $(this).data('link');
            const $button = $(this);
            
            // Create a temporary input element
            const tempInput = document.createElement('input');
            tempInput.value = link;
            document.body.appendChild(tempInput);
            
            // Select and copy the link
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            // Visual feedback
            $button.removeClass('btn-outline-primary').addClass('btn-success');
            $button.find('i').removeClass('fa-copy').addClass('fa-check');
            
            setTimeout(() => {
                $button.removeClass('btn-success').addClass('btn-outline-primary');
                $button.find('i').removeClass('fa-check').addClass('fa-copy');
            }, 2000);
            
            // Show success message
            Swal.fire({
                ...toastConfig,
                icon: 'success',
                title: 'Link Copied!',
                text: 'Shareable link copied to clipboard',
                background: '#28a745',
                color: '#fff'
            });
        });

        /* DataTable configuration */
        $('#shareable-links-table').DataTable({
            responsive: true,
            order: [[1, 'desc']], // Sort by created date desc
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 },
                { responsivePriority: 3, targets: 3 },
                { responsivePriority: 4, targets: '_all' }
            ],
            language: {
                emptyTable: "No shareable links available at the moment."
            },
            pageLength: 10,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            drawCallback: function() {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });
</script>
@endsection 