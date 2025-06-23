@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2"></i>Overtime Details
                    </h3>
                    <div class="card-tools">
                        @if(!Auth::user()->hasRole('Employee'))
                        <a href="{{ route('overtime.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to List
                        </a>
                        @else
                        <a href="{{ route('home') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Home
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Employee Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Employee Name</th>
                                            <td>{{ $overtime->employee->first_name }} {{ $overtime->employee->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Employee ID</th>
                                            <td>{{ $overtime->employee->company_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Department</th>
                                            <td>{{ $overtime->employee->department->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Position</th>
                                            <td>{{ $overtime->employee->position->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Overtime Details</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Date</th>
                                            <td>{{ $overtime->date->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Overtime Hours</th>
                                            <td>{{ $overtime->overtime_hours }} hours</td>
                                        </tr>
                                        <tr>
                                            <th>Overtime Rate</th>
                                            <td>{{ $overtime->overtime_rate }}x</td>
                                        </tr>
                                        <tr>
                                            <th>Overtime Pay</th>
                                            <td class="font-weight-bold">₱{{ number_format($overtime->overtime_pay, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Approval Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="30%">Status</th>
                                                    <td>
                                                        @if($overtime->approval_status == 'pending')
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif($overtime->approval_status == 'approved')
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif($overtime->approval_status == 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($overtime->approval_status != 'pending')
                                                <tr>
                                                    <th>Processed By</th>
                                                    <td>
                                                        @if($overtime->approver)
                                                            {{ $overtime->approver->first_name }} {{ $overtime->approver->last_name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Processed Date</th>
                                                    <td>{{ $overtime->approved_at ? $overtime->approved_at->format('F d, Y h:i A') : 'N/A' }}</td>
                                                </tr>
                                                @endif
                                                @if($overtime->approval_status == 'rejected' && $overtime->rejection_reason)
                                                <tr>
                                                    <th>Rejection Reason</th>
                                                    <td>{{ $overtime->rejection_reason }}</td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 