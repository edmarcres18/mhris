@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Barangay</h3>
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

                    <form action="{{ route('barangay.update', $barangay->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city_id">City</label>
                                    <select id="city_id" name="city_id" class="form-control">
                                        @foreach($city as $city)
                                            <option value="{{ $city->id }}" {{ $city->id == $barangay->city_id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $barangay->name) }}" placeholder="Enter barangay name">
                                </div>
                            </div>


                            <!-- Add more fields as needed -->
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="btn-group" role="group" aria-label="Button group">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>&nbsp;&nbsp;
                                    <a href="{{ route('barangay.index') }}" class="btn btn-info">Back</a>
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
