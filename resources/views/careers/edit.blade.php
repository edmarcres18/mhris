@extends('layouts.app')

@section('content')
    <h1>Edit Career</h1>
    <form action="{{ route('careers.update', $career) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $career->title }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $career->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="requirements">Requirements</label>
            <textarea class="form-control" id="requirements" name="requirements" rows="3" required>{{ $career->requirements }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Career</button>
    </form>
@endsection
