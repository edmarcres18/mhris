@extends('layouts.app')

@section('content')
<br>
<!-- Enhanced professional-looking link buttons -->
<div class="mb-4">
    <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
        <a href="{{ route('loan_sss.index') }}" class="contribution-link {{ request()->routeIs('loan_sss.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Sss Loan</span>
                <small class="description">Social Security System</small>
            </div>
        </a>
        <a href="{{ route('loan_pagibig.index') }}" class="contribution-link {{ request()->routeIs('loan_pagibig.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-home"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Pag-IBIG Loan</span>
                <small class="description">Home Development Mutual Fund</small>
            </div>
        </a>
        <a href="{{ route('cash_advances.index') }}" class="contribution-link {{ request()->routeIs('cash_advances.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Cash Advances</span>
                <small class="description">Cash Advances List</small>
            </div>
        </a>
        <a href="{{ route('loans.employees-list') }}" class="contribution-link {{ request()->routeIs('loans.employees-list') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-users"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Borrower</span>
                <small class="description">Employee Borrower List</small>
            </div>
        </a>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Contribution Employee List</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="employeeTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td data-label="Employee ID">{{ $employee->company_id }}</td>
                                <td data-label="Employee Name">{{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle ?? ' ' }} {{ $employee->suffix ?? ' ' }}</td>
                                <td data-label="Action">
                                    <a href="{{ route('loans.employee', $employee->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-coins"></i> View Loans
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('#employeeTable').DataTable();
        });
    </script>
@endsection

@section('css')
<style>
    @media screen and (max-width: 767px) {
        .table-responsive {
            border: 0;
        }

        #employeeTable thead {
            display: none;
        }

        #employeeTable tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
        }

        #employeeTable td {
            display: block;
            text-align: right;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px solid #dee2e6;
        }

        #employeeTable td:last-child {
            border-bottom: 0;
        }

        #employeeTable td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
        }
    }
</style>
@endsection
