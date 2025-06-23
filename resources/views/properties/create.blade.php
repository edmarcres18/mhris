@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Property</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="property_name">Property Name</label>
                            <input type="text" class="form-control @error('property_name') is-invalid @enderror" id="property_name" name="property_name" value="{{ old('property_name') }}" required>
                            @error('property_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Property Type</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="rent" {{ old('type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                                <option value="sale" {{ old('type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @foreach(['main_image', 'first_image', 'second_image', 'third_image', 'fourth_image', 'fifth_image'] as $image)
                            <div class="form-group">
                                <label for="{{ $image }}">{{ ucfirst(str_replace('_', ' ', $image)) }} @if(in_array($image, ['fourth_image', 'fifth_image'])) (Optional) @endif</label>
                                <input type="file" class="form-control-file @error($image) is-invalid @enderror" id="{{ $image }}" name="{{ $image }}" @if(!in_array($image, ['fourth_image', 'fifth_image'])) required @endif>
                                @error($image)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="contact_info">Contact Info</label>
                            <input type="text" class="form-control @error('contact_info') is-invalid @enderror" id="contact_info" name="contact_info" value="{{ old('contact_info') }}" required>
                            @error('contact_info')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Create Property</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
