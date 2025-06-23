@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New User</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>First Name:</strong>
                                    <input type="text" name="first_name" class="form-control" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Middle Name:</strong>
                                    <input type="text" name="middle_name" class="form-control" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Last Name:</strong>
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Suffix:</strong>
                                    <input type="text" name="suffix" class="form-control" placeholder="Suffix">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Bio:</strong>
                                    <input type="text" name="bio" class="form-control" placeholder="Enter bio">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Profile Image:</strong>
                                    <input type="file" name="profile_image" class="form-control" placeholder="Enter profile_image">
                                    <!-- Display the uploaded profile image if available -->
                                    @if (isset($user->profile_image))
                                        <img src="{{ asset('profile_images/' . $user->profile_image) }}" alt="Profile Image" style="max-width: 100px; max-height: 100px;">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Email:</strong>
                                    <input type="email" name="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Password:</strong>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Confirm Password:</strong>
                                    <input type="password" name="confirm-password" class="form-control" placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <strong>Role:</strong>
                                    <select class="form-control select2" single="single" data-placeholder="Select roles" style="width: 100%;" name="roles[]">
                                        @foreach ($roles as $role)
                                            @if(auth()->user()->hasRole('Super Admin') || $role != 'Super Admin')
                                                <option value="{{ $role }}">{{ $role }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="btn-group" role="group" aria-label="Button group">
                                    <button type="submit" class="btn btn-primary">Create</button>&nbsp;&nbsp;
                                    <a href="{{ route('users.index') }}" class="btn btn-info">Back</a>
                                </div>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all select elements
            $('select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
@stop
