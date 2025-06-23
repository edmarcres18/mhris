@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pay Slips for {{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name ?? ' ' }} {{ $employee->suffix ?? ' ' }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="payroll-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Payroll ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employee->payrolls as $payroll)
                                <tr>
                                    <td>{{ $payroll->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payroll->start_date)->format('F j, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payroll->end_date)->format('F j, Y') }}</td>
                                    <td>
                                        <a href="{{ route('payroll.downloadPdf', ['id' => $payroll->id]) }}" class="btn btn-info">Download Payslip</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('#payroll-table').DataTable();
    });
</script>
@endsection
