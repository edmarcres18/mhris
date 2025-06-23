@extends('layouts.app')

@section('content')
    <h1>{{ $career->title }}</h1>
    <p><strong>Description:</strong> {{ $career->description }}</p>
    <p><strong>Requirements:</strong> {{ $career->requirements }}</p>
    <a href="{{ route('careers.edit', $career) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('careers.destroy', $career) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
    </form>
    <a href="{{ route('careers.index') }}" class="btn btn-secondary">Back to Careers</a>
@endsection
