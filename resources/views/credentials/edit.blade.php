@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Credential</h3>
                    </div>
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

                        <form action="{{ route('credentials.update', $credential->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">Employee<span class="text-danger">*</span></label>
                                        <select name="employee_id" id="employee_id" class="form-control" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('employee_id', $credential->employee_id) == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name ?? ' ' }} {{ $employee->suffix ?? ' ' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_number">Company Number<span class="text-danger">*</span></label>
                                        <input type="number" id="company_number" name="company_number" class="form-control" placeholder="Enter company number" value="{{ old('company_number', $credential->company_number) }}" required max="99999999999">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_email">Company Email<span class="text-danger">*</span></label>
                                        <select name="company_email" id="company_email" class="form-control">
                                            <option value="">Select Company Email</option>
                                            @foreach($companyEmails as $email)
                                                <option value="{{ $email->email }}" {{ old('company_email', $credential->company_email) == $email->email ? 'selected' : '' }}>
                                                    {{ $email->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-2">
                                            <small class="text-muted">Select from registered company emails or enter a custom email</small>
                                        </div>
                                        <div class="mt-2">
                                            <input type="email" id="custom_company_email" class="form-control" placeholder="Or enter custom email (if not in the list)" value="{{ !in_array($credential->company_email, $companyEmails->pluck('email')->toArray()) ? $credential->company_email : '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_password">Email Password<span class="text-danger">*</span></label>
                                        <input type="text" id="email_password" name="email_password" class="form-control" placeholder="Password will auto-fill when selecting a registered email" value="{{ old('email_password', $credential->email_password) }}" required>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="show_password">
                                            <label class="form-check-label" for="show_password">
                                                Show Password
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button type="submit" class="btn btn-primary">Update</button>&nbsp;&nbsp;
                                        <a href="{{ route('credentials.index') }}" class="btn btn-info">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

            // Limit company_number input to 11 digits
            $('#company_number').on('input', function() {
                if (this.value.length > 11) {
                    this.value = this.value.slice(0, 11);
                }
            });
            
            // Handle company email selection
            $('#company_email').on('change', function() {
                const selectedEmail = $(this).val();
                $('#custom_company_email').val('');
                
                if (selectedEmail) {
                    // Get password for the selected email
                    $.ajax({
                        url: '{{ route("credentials.get-email-password") }}',
                        type: 'GET',
                        data: { email: selectedEmail },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#email_password').val(response.password);
                            }
                        }
                    });
                }
            });
            
            // Handle custom email input
            $('#custom_company_email').on('input', function() {
                const customEmail = $(this).val();
                if (customEmail) {
                    // Clear the dropdown and set the custom email
                    $('#company_email').val('').trigger('change');
                    $('input[name="company_email"]').val(customEmail);
                } else {
                    // Restore the dropdown value
                    $('input[name="company_email"]').val($('#company_email').val());
                }
            });
            
            // Show/hide password
            $('#show_password').change(function() {
                const passwordField = $('#email_password');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
            });
            
            // Set password field type to password initially
            $('#email_password').attr('type', 'password');
            
            // Set the custom email if it's not in the dropdown
            const currentEmail = '{{ $credential->company_email }}';
            const emailExists = $('#company_email option').filter(function() {
                return $(this).val() === currentEmail;
            }).length > 0;
            
            if (!emailExists && currentEmail) {
                $('#custom_company_email').val(currentEmail);
                $('#company_email').val('').trigger('change');
                $('input[name="company_email"]').val(currentEmail);
            }
        });
    </script>
@stop
