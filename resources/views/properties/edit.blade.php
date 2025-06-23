@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Property</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('properties.update', $property->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="property_name">Property Name</label>
                            <input type="text" class="form-control" id="property_name" name="property_name" value="{{ $property->property_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="main_image">Main Image</label>
                            <input type="file" class="form-control-file" id="main_image" name="main_image">
                            <small class="form-text text-muted">Leave blank to keep the current image</small>
                        </div>
                        <div class="form-group">
                            <label for="first_image">First Image</label>
                            <input type="file" class="form-control-file" id="first_image" name="first_image">
                            <small class="form-text text-muted">Leave blank to keep the current image</small>
                        </div>
                        <div class="form-group">
                            <label for="second_image">Second Image</label>
                            <input type="file" class="form-control-file" id="second_image" name="second_image">
                            <small class="form-text text-muted">Leave blank to keep the current image</small>
                        </div>
                        <div class="form-group">
                            <label for="third_image">Third Image</label>
                            <input type="file" class="form-control-file" id="third_image" name="third_image">
                            <small class="form-text text-muted">Leave blank to keep the current image</small>
                        </div>
                        <div class="form-group">
                            <label for="fourth_image">Fourth Image (Optional)</label>
                            <input type="file" class="form-control-file" id="fourth_image" name="fourth_image">
                            <small class="form-text text-muted">Leave blank to keep the current image</small>
                        </div>
                        <div class="form-group">
                            <label for="fifth_image">Fifth Image (Optional)</label>
                            <input type="file" class="form-control-file" id="fifth_image" name="fifth_image">
                            <small class="form-text text-muted">Leave blank to keep the current image</small>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $property->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="contact_info">Contact Info</label>
                            <input type="text" class="form-control" id="contact_info" name="contact_info" value="{{ $property->contact_info }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $property->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ $property->location }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Property</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
