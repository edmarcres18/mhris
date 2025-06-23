@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2>Edit Department</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('departments.update', $department->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" value="{{ $department->name }}" class="form-control" placeholder="Enter department name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="head_name">Head Name:</label>
                                        <input type="text" id="head_name" name="head_name" value="{{ $department->head_name }}" class="form-control" placeholder="Enter department head name">
                                    </div>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>
                            <div class="row">
    <div class="col-md-6">
        <div class="btn-group" role="group" aria-label="Button group">
            <button type="submit" class="btn btn-primary">Save Changes</button>&nbsp;&nbsp;
            <a href="{{ route('departments.index') }}" class="btn btn-info">Back</a>
        </div>
    </div>
</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
