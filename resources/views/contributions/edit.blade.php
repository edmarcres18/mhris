@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Contribution</h3>
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

                        <form action="{{ route('contributions.update', $contribution->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">Employee<span class="text-danger">*</span></label>
                                        <select id="employee_id" name="employee_id" class="form-control" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" data-sss-no="{{ $employee->sss_no }}"
                                                        data-pagibig-no="{{ $employee->pagibig_no }}"
                                                        data-philhealth-no="{{ $employee->philhealth_no }}"
                                                        data-tin-no="{{ $employee->tin_no }}"
                                                        {{ $employee->id == $contribution->employee_id ? 'selected' : '' }}>
                                                    {{ $employee->company_id }}  {{ $employee->last_name }}  {{ $employee->first_name }}, {{ $employee->middle_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Date<span class="text-danger">*</span></label>
                                        <input type="date" id="date" name="date" value="{{$contribution->date}}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sss_contribution">SSS Contribution</label>
                                        <input type="number" id="sss_contribution" name="sss_contribution" class="form-control" step="0.1" value="{{ $contribution->sss_contribution }}" readonly placeholder="No SSS Number">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pagibig_contribution">PAGIBIG Contribution</label>
                                        <input type="number" id="pagibig_contribution" name="pagibig_contribution" class="form-control" step="0.1" value="{{ $contribution->pagibig_contribution }}" readonly placeholder="No PAGIBIG Number">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="philhealth_contribution">PHILHEALTH Contribution</label>
                                        <input type="number" id="philhealth_contribution" name="philhealth_contribution" class="form-control" step="0.1" value="{{ $contribution->philhealth_contribution }}" readonly placeholder="No PHILHEALTH Number">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tin_contribution">TIN Contribution</label>
                                        <input type="number" id="tin_contribution" name="tin_contribution" class="form-control" step="0.1" value="{{ $contribution->tin_contribution }}" readonly placeholder="No TIN Number">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('contributions.index') }}" class="btn btn-info">Back</a>
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
                var philhealthNo = selectedOption.data('philhealth-no');
                var tinNo = selectedOption.data('tin-no');

                // Handle SSS Contribution Field
                if (sssNo) {
                    $('#sss_contribution').removeAttr('readonly').attr('placeholder', '');
                } else {
                    $('#sss_contribution').attr('readonly', true).attr('placeholder', 'No SSS Number');
                }

                // Handle PAGIBIG Contribution Field
                if (pagibigNo) {
                    $('#pagibig_contribution').removeAttr('readonly').attr('placeholder', '');
                } else {
                    $('#pagibig_contribution').attr('readonly', true).attr('placeholder', 'No PAGIBIG Number');
                }

                // Handle PHILHEALTH Contribution Field
                if (philhealthNo) {
                    $('#philhealth_contribution').removeAttr('readonly').attr('placeholder', '');
                } else {
                    $('#philhealth_contribution').attr('readonly', true).attr('placeholder', 'No PHILHEALTH Number');
                }

                // Handle TIN Contribution Field
                if (tinNo) {
                    $('#tin_contribution').removeAttr('readonly').attr('placeholder', '');
                } else {
                    $('#tin_contribution').attr('readonly', true).attr('placeholder', 'No TIN Number');
                }
            }).trigger('change'); // Trigger change event on page load to set initial state
        });
    </script>
@stop
