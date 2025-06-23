@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Edit Role</h2>
                    <div class="card-tools">
                        <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">Back</a>
                    </div>
                </div>
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

                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" value="{{ $role->name }}" name="name" class="form-control" placeholder="Enter role name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Permissions:</label>
                            <div class="row">
                                @forelse($permissions as $perm)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="permission_{{ $perm->id }}" {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission_{{ $perm->id }}">
                                                {{ $perm->name }}
                                            </label>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12">
                                        <span>No permissions available</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>


                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
