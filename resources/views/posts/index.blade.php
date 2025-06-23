@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Post List</h3>
                    <div class="card-tools">
                        @can('post-create')
                        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm rounded-pill">
                            Add Post <i class="fas fa-plus-circle"></i>
                        </a>
                        @endcan
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="posts-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Posted By</th>
                                <th>Date Start</th>
                                <th>Date End</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td>
                                        @if($post->image_path)
                                        <img src="{{ asset($post->image_path) }}" alt="Post image" class="img-thumbnail" style="max-width: 100px; max-height: 80px; object-fit: cover;">
                                        @else
                                        <span class="text-muted"><i class="far fa-image"></i> No image</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->user->first_name }} {{ $post->user->last_name }}</td>
                                    <td>{{ $post->date_start ? $post->date_start->format('M d, Y') : '-' }}</td>
                                    <td>{{ $post->date_end ? $post->date_end->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button post="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('posts.show',$post->id) }}"><i class="fas fa-eye"></i>&nbsp;Preview</a>
                                                @can('post-edit')
                                                    <a class="dropdown-item" href="{{ route('posts.edit',$post->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>
                                                @endcan
                                                @can('post-delete')
                                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
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

@section('js')
<script>
    $(document).ready(function () {
        // SweetAlert toast configuration
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

        // Initialize DataTable
        $('#posts-table').DataTable({
            order: [[2, 'desc']], // Sort by start date by default
            columnDefs: [
                { targets: [2, 3], // Date columns
                  render: function(data) {
                      return moment(data).format('MMM D, YYYY');
                  }
                }
            ]
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
</style>
@endsection
