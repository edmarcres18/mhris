@extends('layouts.app')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Holiday List</h3>
                        <div class="card-tools d-flex flex-wrap align-items-center">
                            @can('holiday-create')
                            <button type="button" class="btn btn-primary btn-sm rounded-pill mr-2 mb-2 d-flex align-items-center" data-toggle="modal" data-target="#importModal">
                                <i class="fas fa-file-import mr-1"></i>
                                <span>Import Holidays</span>
                            </button>
                            <form action="{{ route('holidays.export') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-info btn-sm rounded-pill mr-2 mb-2 d-flex align-items-center">
                                    <i class="fas fa-file-export mr-1"></i>
                                    <span>Export Holidays</span>
                                </button>
                            </form>
                            <a href="{{ route('holidays.create') }}" class="btn btn-success btn-sm rounded-pill mb-2 d-flex align-items-center">
                                <i class="fas fa-plus-circle mr-1"></i>
                                <span>Add Holiday</span>
                            </a>
                            @endcan
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="holiday-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Holiday Type</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($holidays as $holiday)
                                    <tr>
                                        <td>{{ $holiday->id}}</td>
                                        <td>{{ $holiday->title }}</td>
                                        <td>{{ $holiday->type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($holiday->date)->format('F j, Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <!-- <a class="dropdown-item" href="{{ route('holidays.show',$holiday->id) }}"><i class="fas fa-eye"></i>&nbsp;Preview</a> -->
                                                    @can('holiday-edit')
                                                        <a class="dropdown-item" href="{{ route('holidays.edit',$holiday->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                                    @endcan
                                                    @can('holiday-delete')
                                                        <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"><i class="fas fa-trash"></i>&nbsp;Delete</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="fas fa-file-import mr-2"></i>Import Holidays
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file" class="font-weight-bold">
                                <i class="fas fa-file-excel mr-1"></i>Choose Excel File
                            </label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file" accept=".xlsx, .xls" required>
                                <label class="custom-file-label" for="file">Choose file...</label>
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Please upload an Excel file (.xlsx or .xls) with the following columns: title, type, date
                            </small>
                        </div>
                        <div class="alert alert-info">
                            <h6 class="font-weight-bold">
                                <i class="fas fa-info-circle mr-1"></i>Available Holiday Types:
                            </h6>
                            <ul class="mb-0 pl-4">
                                @foreach(App\Models\Holiday::types() as $type)
                                    <li>{{ $type }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="importSubmitBtn">
                            <span class="normal-state">
                                <i class="fas fa-file-import mr-1"></i>Import
                            </span>
                            <span class="loading-state d-none">
                                <i class="fas fa-spinner fa-spin mr-1"></i>Importing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    
    <script>
        $(document).ready(function () {
            // Initialize custom file input
            bsCustomFileInput.init();

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

            // Handle import form submission
            $('#importForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = $('#importSubmitBtn');
                
                // Show loading state
                submitBtn.prop('disabled', true)
                    .find('.normal-state').addClass('d-none')
                    .end()
                    .find('.loading-state').removeClass('d-none');
                
                $.ajax({
                    url: '{{ route("holidays.import") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#importModal').modal('hide');
                        
                        Swal.fire({
                            ...toastConfig,
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            background: '#28a745',
                            color: '#fff'
                        });
                        
                        // Reload the page after a short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while importing.';
                        
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                errorMessage = xhr.responseJSON.errors.join('\n');
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }
                        
                        Swal.fire({
                            ...toastConfig,
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            background: '#dc3545',
                            color: '#fff'
                        });
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false)
                            .find('.loading-state').addClass('d-none')
                            .end()
                            .find('.normal-state').removeClass('d-none');
                    }
                });
            });

            // Reset form and button state when modal is closed
            $('#importModal').on('hidden.bs.modal', function () {
                $('#importForm').trigger('reset');
                $('.custom-file-label').html('Choose file...');
                $('#importSubmitBtn')
                    .prop('disabled', false)
                    .find('.loading-state').addClass('d-none')
                    .end()
                    .find('.normal-state').removeClass('d-none');
            });

            // Success toast
            @if(Session::has('success'))
                Swal.fire({
                    ...toastConfig,
                    icon: 'success',
                    title: 'Success',
                    text: "{{ Session::get('success') }}",
                    background: '#28a745',
                    color: '#fff'
                });
            @endif

            // Error toast
            @if(Session::has('error'))
                Swal.fire({
                    ...toastConfig,
                    icon: 'error',
                    title: 'Error',
                    text: "{{ Session::get('error') }}",
                    background: '#dc3545',
                    color: '#fff'
                });
            @endif

            // Initialize DataTable
            $('#holiday-table').DataTable();

            // Update delete confirmation to use SweetAlert
            $(document).on('click', '.dropdown-item[type="submit"]', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
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
        });
    </script>

    <style>
        /* Toast styles */
        .colored-toast.swal2-icon-success {
            box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
        }
        .colored-toast.swal2-icon-error {
            box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
        }

        /* Button hover effects */
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Custom file input styling */
        .custom-file-input:focus ~ .custom-file-label {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }

        /* Modal animation */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: scale(0.95);
        }
        .modal.show .modal-dialog {
            transform: scale(1);
        }

        /* Responsive button text */
        @media (max-width: 576px) {
            .card-tools .btn span {
                display: none;
            }
            .card-tools .btn i {
                margin-right: 0 !important;
            }
        }
    </style>
@endsection
