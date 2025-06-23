@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h2><i class="fas fa-file-invoice-dollar me-2"></i> Cash Advance Details</h2>
            <div>
                @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'))
                <a href="{{ route('cash_advances.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Back to List
                </a>
                @else
                <a href="javascript:void(0)" onclick="window.history.back()" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-light">
                            <h4 class="mb-0"><i class="fas fa-user me-2"></i> Employee Information</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th class="bg-light" width="35%">Employee Name:</th>
                                    <td>{{ $cashAdvance->employee->last_name }} {{ $cashAdvance->employee->first_name }}, {{ $cashAdvance->employee->middle_name ?? ' '}} {{ $cashAdvance->employee->suffix ?? ' ' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Employee ID:</th>
                                    <td>{{ $cashAdvance->employee->company_id }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Department:</th>
                                    <td>{{ $cashAdvance->employee->department->name }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h4 class="mb-0"><i class="fas fa-money-check-alt me-2"></i> Cash Advance Details</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <th class="bg-light" width="35%">Reference Number:</th>
                                    <td>{{ ($cashAdvance->reference_number) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Date Requested:</th>
                                    <td>{{ $cashAdvance->created_at->format('F d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Cash Advance Amount:</th>
                                    <td class="font-weight-bold">₱ {{ number_format($cashAdvance->cash_advance_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Repayment Term:</th>
                                    <td>{{ $cashAdvance->repayment_term }} months</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Monthly Amortization:</th>
                                    <td>₱ {{ number_format($cashAdvance->monthly_amortization, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Total Repayment:</th>
                                    <td>₱ {{ number_format($cashAdvance->total_repayment, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $cashAdvance->status === 'active' ? 'success' : ($cashAdvance->status === 'pending' ? 'warning' : 'secondary') }} px-3 py-2">
                                            <i class="fas fa-{{ $cashAdvance->status === 'active' ? 'check-circle' : ($cashAdvance->status === 'pending' ? 'clock' : 'times-circle') }} me-1"></i>
                                            {{ ucfirst($cashAdvance->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($cashAdvance->status === 'active')
                                    <tr>
                                        <th class="bg-light">Approved By:</th>
                                        <td>{{ $cashAdvance->approvedByUser->first_name . ' ' . $cashAdvance->approvedByUser->last_name ?? 'N/A' }}</td>
                                    </tr>
                                @elseif($cashAdvance->status === 'declined')
                                    <tr>
                                        <th class="bg-light">Rejected By:</th>
                                        <td>{{ $cashAdvance->rejectedByUser->first_name . ' ' . $cashAdvance->rejectedByUser->last_name ?? 'N/A' }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="bg-light">Remaining Balance:</th>
                                    <td class="font-weight-bold {{ $cashAdvance->remainingBalance() > 0 ? 'text-danger' : 'text-success' }}">
                                        ₱ {{ number_format($cashAdvance->remainingBalance(), 2) }}
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    @if($cashAdvance->signature)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <h4 class="mb-0"><i class="fas fa-signature me-2"></i> Signature</h4>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ Storage::url($cashAdvance->signature) }}"
                                     alt="Signature"
                                     class="img-fluid border rounded p-2"
                                     style="max-height: 150px;">
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
            @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'))
                <div class="d-flex gap-2">
                    @if($cashAdvance->status === 'pending')
                        <form action="{{ route('cash_advances.update', $cashAdvance) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i>Approve Cash Advance
                            </button>
                        </form>

                        <form action="{{ route('cash_advances.update', $cashAdvance) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="declined">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-1"></i>Decline Cash Advance
                            </button>
                        </form>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('cash_advances.edit', $cashAdvance) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <form action="{{ route('cash_advances.destroy', $cashAdvance) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this cash advance? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i>Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

