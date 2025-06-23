@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h2 class="card-title fw-bold text-dark mb-0">{{ $task->title }}</h2>
                </div>
                <div class="card-body px-4 py-4">
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <div class="info-box shadow-sm rounded-3 h-100 border">
                                <div class="d-flex align-items-center p-3">
                                    <div class="info-box-icon rounded-circle bg-info-subtle p-3 me-3">
                                        <i class="far fa-user text-info fs-4"></i>
                                    </div>
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted small text-uppercase">Assigned To</span>
                                        <span class="info-box-number fw-bold text-dark">
                                            {{ $task->employee->company_id }} {{ $task->employee->last_name }}, 
                                            {{ $task->employee->first_name }} {{ $task->employee->middle_name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="info-box shadow-sm rounded-3 h-100 border">
                                <div class="d-flex align-items-center p-3">
                                    <div class="info-box-icon rounded-circle bg-warning-subtle p-3 me-3">
                                        <i class="fas fa-tasks text-warning fs-4"></i>
                                    </div>
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted small text-uppercase">Status</span>
                                        <span class="info-box-number fw-bold text-dark">{{ $task->status }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded-3">
                        <h5 class="fw-bold mb-3">Description</h5>
                        <p class="mb-0 text-secondary">{{ $task->description }}</p>
                    </div>

                    <div class="mt-4 d-flex gap-3 flex-wrap">
                        @if(Auth::user()->hasRole('Employee'))
                            <a href="{{ route('myTasks') }}" class="btn btn-light border shadow-sm">
                                <i class="fas fa-arrow-left me-2"></i>Back to My Tasks
                            </a>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning shadow-sm">
                                <i class="fas fa-edit me-2"></i>Update My Task
                            </a>
                        @else
                            <a href="{{ route('tasks.index') }}" class="btn btn-light border shadow-sm">
                                <i class="fas fa-arrow-left me-2"></i>Back to Tasks
                            </a>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning shadow-sm">
                                <i class="fas fa-edit me-2"></i>Edit Task
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop