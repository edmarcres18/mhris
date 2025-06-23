@extends('layouts.app')

@section('content')
    <h1>Careers</h1>
    <a href="{{ route('careers.create') }}" class="btn btn-primary">Add New Career</a>

    @foreach($careers as $career)
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">{{ $career->title }}</h5>
                <p class="card-text">{{ Str::limit($career->description, 100) }}</p>
                <a href="{{ route('careers.show', $career) }}" class="btn btn-info">View</a>
                <a href="{{ route('careers.edit', $career) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('careers.destroy', $career) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
@endsection
