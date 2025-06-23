@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0">SSS Contribution Information</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="employee-info mb-4">
                        <h4 class="text-primary">{{ $sss->employee->last_name }}, {{ $sss->employee->first_name }} {{ $sss->employee->middle_name ?? '' }} {{ $sss->employee->suffix ?? '' }}</h4>
                        <p class="text-muted">Employee ID: {{ $sss->employee->company_id }}</p>
                    </div>
                    <div class="contribution-details">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="detail-label">Contribution Date</span>
                                    <span class="detail-value">{{ $sss->contribution_date->format('F Y') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="detail-label">Monthly Salary Credit</span>
                                    <span class="detail-value">₱{{ number_format($sss->monthly_salary_credit, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="detail-label">Employee Contribution</span>
                                    <span class="detail-value">₱{{ number_format($sss->employee_contribution, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="detail-label">Employer Contribution</span>
                                    <span class="detail-value">₱{{ number_format($sss->employer_contribution, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="detail-label">Total Contribution</span>
                                    <span class="detail-value font-weight-bold">₱{{ number_format($sss->total_contribution, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="detail-label">EC Contribution</span>
                                    <span class="detail-value">₱{{ number_format($sss->ec_contribution, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="detail-item">
                                    <span class="detail-label">Total Contribution With EC</span>
                                    <span class="detail-value font-weight-bold">₱{{ number_format($sss->total_contribution + $sss->ec_contribution, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-none d-md-block">
                    <img src="{{ asset('vendor/adminlte/dist/img/sss.png') }}" alt="SSS Logo" class="img-fluid mt-4" style="opacity: 0.7;">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('sss.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        transition: box-shadow 0.3s ease-in-out;
    }
    .card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .card-header {
        border-bottom: none;
        padding: 1.5rem;
    }
    .card-body {
        padding: 2rem;
    }
    .employee-info h4 {
        margin-bottom: 0.5rem;
    }
    .detail-item {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        height: 100%;
    }
    .detail-label {
        display: block;
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .detail-value {
        display: block;
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
    }
    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        transition: background-color 0.3s ease;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
    }
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }
    }
</style>
@endsection
