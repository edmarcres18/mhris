@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Edit User</h2>
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

                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        @if ($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="profile-img img-fluid rounded-circle mb-3" style="height: 150px; width: 150px;">
                                    @else
                                        <p>No image available</p>
                                    @endif
                        <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Profile Image:</strong>
                                    <input type="file" name="profile_image" class="form-control" placeholder="Profile Image">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>First Name:</strong>
                                    <input type="text" value="{{ $user->first_name }}" name="first_name" class="form-control" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Middle Name:</strong>
                                    <input type="text" value="{{ $user->middle_name }}" name="middle_name" class="form-control" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Last Name:</strong>
                                    <input type="text" value="{{ $user->last_name }}" name="last_name" class="form-control" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Suffix:</strong>
                                    <input type="text" value="{{ $user->suffix }}" name="suffix" class="form-control" placeholder="Suffix">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Bio:</strong>
                                    <input type="text" value="{{ $user->bio }}" name="bio" class="form-control" placeholder="Bio">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Email:</strong>
                                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="Email">
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
                                                <option value="{{ $role }}" {{ $user->hasRole($role) ? 'selected' : '' }}>{{ $role }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="btn-group" role="group" aria-label="Button group">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>&nbsp;&nbsp;
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
