@extends('layouts.app')

@section('content')
    <br>
    <div class="container-fluid">
        <!-- Enhanced professional-looking link buttons -->
<div class="mb-4">
    <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
        <a href="{{ route('hirings.index') }}" class="contribution-link {{ request()->routeIs('hirings.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Hirings</span>
                <small class="description">Job Available List</small>
            </div>
        </a>
        <a href="{{ url('/all-careers') }}" class="contribution-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">
                    Applicants
                    @php
                        $unreadCareersCount = \App\Models\Career::where('is_read', false)->count();
                    @endphp
                    <span id="applicants-unread-badge" class="badge badge-danger" style="{{ $unreadCareersCount > 0 ? '' : 'display: none;' }}">{{ $unreadCareersCount }}</span>
                </span>
                <small class="description">Applicants List</small>
            </div>
        </a>
    </div>
</div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Hiring List</h3>
                        <div class="card-tools">
                            @can('hiring-create')
                            <a href="{{ route('hirings.create') }}" class="btn btn-success btn-sm rounded-pill">
                                Add Hiring <i class="fas fa-plus-circle"></i>
                            </a>
                            @endcan
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="hirings-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Description</th>
                                    <th>Requirements</th>
                                    <th>Location</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hirings as $hiring)
                                <tr>
                                    <td>{{ $hiring->position }}</td>
                                    <td>{{ Str::limit($hiring->description, 100) }}</td>
                                    <td>{{ Str::limit($hiring->requirements, 100) }}</td>
                                    <td>{{ $hiring->location }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="{{ route('hirings.show', $hiring->id) }}"><i class="fas fa-eye"></i>&nbsp;Preview</a>
                                                @can('hiring-edit')
                                                    <a class="dropdown-item" href="{{ route('hirings.edit', $hiring->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                                @endcan
                                                @can('hiring-delete')
                                                    <form action="{{ route('hirings.destroy', $hiring->id) }}" method="POST">
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
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function () {
            $('#hirings-table').DataTable();

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

            $(document).on('click', '.dropdown-item[type="submit"]', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this hiring? This action cannot be undone!",
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
        .colored-toast.swal2-icon-success {
            box-shadow: 0 0 12px rgba(40, 167, 69, 0.4) !important;
        }
        .colored-toast.swal2-icon-error {
            box-shadow: 0 0 12px rgba(220, 53, 69, 0.4) !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Function to update the unread careers count
            function updateApplicantsUnreadCount() {
                $.ajax({
                    url: '{{ route("careers.unread-count") }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var badge = $('#applicants-unread-badge');
                        if (response.count > 0) {
                            badge.text(response.count).show();
                        } else {
                            badge.hide();
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching unread careers count:', xhr.responseText);
                    }
                });
            }
            
            // Update count every 30 seconds
            setInterval(updateApplicantsUnreadCount, 30000);
        });
    </script>
@endsection
