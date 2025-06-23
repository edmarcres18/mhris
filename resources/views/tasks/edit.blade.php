@extends('layouts.app')

@section('content')
    <h1>Edit Task</h1>
    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT')

        @if(auth()->user()->hasRole(['Super Admin', 'Admin']))
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $task->title }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required>{{ $task->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="employee_id">Employee</label>
                <select name="employee_id" id="employee_id" class="form-control" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $task->employee_id == $employee->id ? 'selected' : '' }}>
                            {{ $employee->company_id }} {{ $employee->last_name }}, {{ $employee->first_name }} {{ $employee->middle_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @else
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" value="{{ $task->title }}" readonly>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" readonly>{{ $task->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="employee">Employee</label>
                <input type="text" class="form-control" value="{{ $task->employee->company_id }} {{ $task->employee->last_name }}, {{ $task->employee->first_name }} {{ $task->employee->middle_name }}" readonly>
            </div>
        @endif

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                @if($task->status == 'Pending')
                    <option value="Pending" selected>Pending</option>
                    <option value="On Progress">On Progress</option>
                    <option value="Done">Done</option>
                    <option value="Abandoned">Abandoned</option>
                @elseif($task->status == 'On Progress')
                    <option value="On Progress" selected>On Progress</option>
                    <option value="Done">Done</option>
                @elseif($task->status == 'Done')
                    <option value="Done" selected>Done</option>
                @elseif($task->status == 'Abandoned')
                    <option value="Abandoned" selected>Abandoned</option>
                @endif
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Task</button>
    </form>
@endsection
