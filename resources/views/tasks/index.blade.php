@extends('layouts.app')

@section('content')
<br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Task List</h3>
            <div class="card-tools">
                <a href="{{ route('tasks.create') }}" class="btn btn-success btn-sm rounded-pill">
                    Add Task <i class="fas fa-plus-circle"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="tasks-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->employee->company_id }} {{ $task->employee->last_name }}, {{ $task->employee->first_name }} {{ $task->employee->middle_name }}</td>
                            <td>{{ $task->title }}</td>
                            <td>
                            @if($task->status === 'Pending')
                                <i class="fas fa-hourglass-half text-gray"></i> Pending
                            @elseif($task->status === 'On Progress')
                                <i class="fas fa-cog text-warning rotating"></i> On Progress
                            @elseif($task->status === 'Done')
                                <i class="fas fa-check-circle text-success"></i> Done
                            @elseif($task->status === 'Abandoned')
                                <i class="fas fa-times-circle text-danger"></i> Abandoned
                            @endif
                        </td>
                            <td>
                                        <div class="btn-group">
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{ route('tasks.show',$task->id) }}"><i class="fas fa-eye"></i>&nbsp;Preview</a>
                                                    @can('tasks-edit')
                                                        <a class="dropdown-item" href="{{ route('tasks.edit',$task->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                                    @endcan
                                                    @can('tasks-delete')
                                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-trash"></i>&nbsp;Delete
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <style>
        .rotating {
            animation: rotate 2s infinite linear;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Toast styles */
        .colored-toast.swal2-icon-success {
            box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
        }
        .colored-toast.swal2-icon-error {
            box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
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
            $('#tasks-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

            // Delete confirmation
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
@stop
