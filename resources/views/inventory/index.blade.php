@extends('layouts.app')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Inventory List</h3>
                        <div class="card-tools">
                            <div class="button-group d-flex">
                                @can('inventory-create')
                                <a href="{{ route('inventory.create') }}" class="btn btn-success btn-sm rounded-pill mr-2">
                                    Add Inventory <i class="fas fa-plus-circle"></i>
                                </a>
                                @endcan
                                <button type="button" class="btn btn-primary btn-sm rounded-pill mr-2" data-toggle="modal" data-target="#importModal">
                                    Import Inventory <i class="fas fa-file-import"></i>
                                </button>
                                <form action="{{ route('inventory.export') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm rounded-pill">
                                        Export Inventory <i class="fas fa-file-export"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="importModalLabel">Import Inventory</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="importForm" action="{{ route('inventory.import') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="file">Choose Excel or CSV file</label>
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="inventory-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventory as $inventory)
                                        <tr>
                                            <td>{{ $inventory->id }}</td>
                                            <td>{{ $inventory->name }}</td>
                                            <td>{{ $inventory->description }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        @can('inventory-edit')
                                                            <a class="dropdown-item" href="{{ route('inventory.edit',$inventory->id) }}">
                                                                <i class="fas fa-edit"></i>&nbsp;Edit
                                                            </a>
                                                        @endcan
                                                        @can('inventory-delete')
                                                            <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item delete-btn">
                                                                    <i class="fas fa-trash"></i>&nbsp;Delete
                                                                </button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .colored-toast.swal2-icon-success {
        box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
    }
    .colored-toast.swal2-icon-error {
        box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize DataTable for inventory
        $('#inventory-table').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [[0, "desc"]], // Sort by ID column descending
            "columnDefs": [
                { "orderable": false, "targets": 3 } // Disable sorting on action column
            ],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "zeroRecords": "No matching records found"
            }
        });

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

        // Handle delete button click
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
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
    });
</script>
@endsection
