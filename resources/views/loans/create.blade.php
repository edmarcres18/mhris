@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Loan</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('loans.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">Employee<span class="text-danger">*</span></label>
                                        <select id="employee_id" name="employee_id" class="form-control" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    data-sss-no="{{ $employee->sss_no }}"
                                                        data-pagibig-no="{{ $employee->pagibig_no }}">
                                                    {{ $employee->company_id }}  {{ $employee->last_name }}  {{ $employee->first_name }}, {{ $employee->middle_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Date Contributed<span class="text-danger">*</span></label>
                                        <input type="date" id="date" name="date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sss_loan">SSS loan</label>
                                        <input type="number" id="sss_loan" name="sss_loan" class="form-control" step="0.1">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pagibig_loan">PAGIBIG loan</label>
                                        <input type="number" id="pagibig_loan" name="pagibig_loan" class="form-control" step="0.1">
                                    </div>
                                </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cash_advance">CASH ADVANCE</label>
                                    <input type="number" id="cash_advance" name="cash_advance" class="form-control" step="0.1">
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button type="submit" class="btn btn-primary">Create</button>&nbsp;&nbsp;
                                        <a href="{{ route('loans.index') }}" class="btn btn-info">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
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
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all select elements
            $('select').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
            $('#employee_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var sssNo = selectedOption.data('sss-no');
                var pagibigNo = selectedOption.data('pagibig-no');

                // Handle SSS Contribution Field
                if (sssNo) {
                    $('#sss_loan').removeAttr('readonly').attr('placeholder', '');
                } else {
                    $('#sss_loan').attr('readonly', true).attr('placeholder', 'No SSS Number');
                }

                // Handle PAGIBIG Contribution Field
                if (pagibigNo) {
                    $('#pagibig_loan').removeAttr('readonly').attr('placeholder', '');
                } else {
                    $('#pagibig_loan').attr('readonly', true).attr('placeholder', 'No PAGIBIG Number');
                }
            });
        });
    </script>
@stop
