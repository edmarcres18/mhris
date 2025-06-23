@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row my-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-calendar-alt me-2"></i>
                    {{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name ?? ' ' }} {{ $employee->suffix ?? ' ' }}'s Leaves
                </h1>
                <a href="{{ route('leaves.all_employees') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Leaves Sheet
                </a>
            </div>
            
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="employee-leaves-table">
                            <thead>
                                <tr>
                                    <th>Date From</th>
                                    <th>Date To</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $leave)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($leave->date_from)->format('F j, Y g:iA') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->date_to)->format('F j, Y g:iA') }}</td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ $leave->type->name }}</span>
                                    </td>
                                    <td>
                                        @if ($leave->status == 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Approved
                                            </span>
                                        @elseif ($leave->status == 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-hourglass-half me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#employee-leaves-table').DataTable({
                order: [[0, 'desc']],
                language: {
                    emptyTable: "No leave requests available"
                }
            });
        });
    </script>
@endsection
