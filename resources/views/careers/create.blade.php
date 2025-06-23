@extends('layouts.app')

@section('content')
    <h1>Create New Career</h1>
    <form action="{{ route('careers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="requirements">Requirements</label>
            <textarea class="form-control" id="requirements" name="requirements" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Career</button>
    </form>
@endsection
