@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Cash Advance Status</h1>
    <form action="{{ route('cash_advances.update', $cashAdvance) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                @if($cashAdvance->status == 'complete')
                    <option value="complete" selected>Complete</option>
                @else
                    <option value="pending" {{ $cashAdvance->status == 'pending' ? 'selected' : '' }} {{ $cashAdvance->status == 'active' ? 'disabled' : '' }}>Pending</option>
                    <option value="active" {{ $cashAdvance->status == 'active' ? 'selected' : '' }}>Active</option>
                    @if($cashAdvance->remainingBalance() == 0)
                        <option value="complete" {{ $cashAdvance->status == 'complete' ? 'selected' : '' }}>Complete</option>
                    @endif
                @endif
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>
</div>
@endsection
