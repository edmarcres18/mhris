@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-moon mr-2"></i>Night Premium Details
                    </h3>
                    <div class="card-tools">
                        @if(!Auth::user()->hasRole('Employee'))
                        <a href="{{ route('night-premium.index') }}" class="btn btn-sm btn-secondary">
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
                                            <td>{{ $nightPremium->employee->first_name }} {{ $nightPremium->employee->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Employee ID</th>
                                            <td>{{ $nightPremium->employee->company_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Department</th>
                                            <td>{{ $nightPremium->employee->department->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Position</th>
                                            <td>{{ $nightPremium->employee->position->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-none border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Night Premium Details</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Date</th>
                                            <td>{{ $nightPremium->date->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Night Hours</th>
                                            <td>{{ $nightPremium->night_hours }} hours</td>
                                        </tr>
                                        <tr>
                                            <th>Night Rate</th>
                                            <td>{{ $nightPremium->night_rate }}x</td>
                                        </tr>
                                        <tr>
                                            <th>Night Premium Pay</th>
                                            <td class="font-weight-bold">₱{{ number_format($nightPremium->night_premium_pay, 2) }}</td>
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
                                                        @if($nightPremium->approval_status == 'pending')
                                                            <span class="badge badge-warning">Pending</span>
                                                        @elseif($nightPremium->approval_status == 'approved')
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif($nightPremium->approval_status == 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($nightPremium->approval_status != 'pending')
                                                <tr>
                                                    <th>Processed By</th>
                                                    <td>
                                                        @if($nightPremium->approver)
                                                            {{ $nightPremium->approver->first_name }} {{ $nightPremium->approver->last_name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Processed Date</th>
                                                    <td>{{ $nightPremium->approved_at ? $nightPremium->approved_at->format('F d, Y h:i A') : 'N/A' }}</td>
                                                </tr>
                                                @endif
                                                @if($nightPremium->approval_status == 'rejected' && $nightPremium->rejection_reason)
                                                <tr>
                                                    <th>Rejection Reason</th>
                                                    <td>{{ $nightPremium->rejection_reason }}</td>
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