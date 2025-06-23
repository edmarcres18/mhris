@extends('layouts.app')

@section('content')
<style>
    /* Enhanced UI Styles */
    body {
        background-color: #f4f6f9;
    }

    .container-fluid {
        padding: 2rem;
    }

    h1 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .card-header {
        background-color: #3498db;
        color: #ffffff;
        font-weight: 600;
        padding: 1rem;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #34495e;
    }

    .form-control {
        border-radius: 4px;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .table {
        background-color: #ffffff;
        border-radius: 8px;
        overflow: hidden;
    }

    .table thead th {
        background-color: #ecf0f1;
        color: #2c3e50;
        font-weight: 600;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }

    .btn-sm {
        border-radius: 4px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #3498db;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        font-size: 1.2rem;
        color: #34495e;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 992px) {
        .container-fluid {
            padding-left: 3rem;
            padding-right: 3rem;
        }
    }
</style>

<div class="container-fluid">
    <h1>My Payrolls</h1>
    <div class="card">
        <div class="card-header">
            Filter Payrolls
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button id="filterButton" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>

    <div id="payrollContent">
        <div class="table-responsive" id="payrollTableContainer">
            <table class="table table-striped" id="payrollTable">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Net Pay</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employee->payrolls as $payroll)
                        <tr>
                            <td class="start-date">{{ $payroll->start_date->format('Y-m-d') }}</td>
                            <td class="end-date">{{ $payroll->end_date->format('Y-m-d') }}</td>
                            <td> ₱{{ number_format($payroll->net_salary, 2) }}</td>
                            <td>
                                <a href="{{ route('payroll.show', $payroll->id) }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr id="noPayrollsRow">
                            <td colspan="4" class="text-center">No payrolls available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div id="emptyState" class="empty-state" style="display: none;">
            <h3 class="empty-state-text">No payrolls found in selected date</h3>
        </div>
    </div>
</div>

<script>
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = new Date(this.value);
    if (!isNaN(startDate.getTime())) {
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 15);
        document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
    }
});

document.getElementById('filterButton').addEventListener('click', function() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const rows = document.querySelectorAll('#payrollTable tbody tr:not(#noPayrollsRow)');
    let visibleRows = 0;

    rows.forEach(row => {
        const rowStartDate = row.querySelector('.start-date').textContent;
        const rowEndDate = row.querySelector('.end-date').textContent;

        if ((startDate && rowStartDate < startDate) || (endDate && rowEndDate > endDate)) {
            row.style.display = 'none';
        } else {
            row.style.display = '';
            visibleRows++;
        }
    });

    if (visibleRows === 0) {
        document.getElementById('payrollTableContainer').style.display = 'none';
        document.getElementById('emptyState').style.display = 'block';
    } else {
        document.getElementById('payrollTableContainer').style.display = 'block';
        document.getElementById('emptyState').style.display = 'none';
    }
});

// Check if there are no payrolls initially
window.addEventListener('load', function() {
    const noPayrollsRow = document.getElementById('noPayrollsRow');
    if (noPayrollsRow) {
        document.getElementById('payrollTableContainer').style.display = 'none';
        document.getElementById('emptyState').style.display = 'block';
    }
});
</script>
@endsection
