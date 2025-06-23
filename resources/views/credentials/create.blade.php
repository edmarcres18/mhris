@extends('layouts.app')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Credential</h3>
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

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('credentials.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">Employee<span class="text-danger">*</span></label>
                                        <select name="employee_id" id="employee_id" class="form-control" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->company_id }} {{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name ?? ' ' }} {{ $employee->suffix ?? ' ' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_number">Company Number<span class="text-danger">*</span></label>
                                        <input type="number" id="company_number" name="company_number" class="form-control" placeholder="Enter company number" value="{{ old('company_number') }}" >
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
                                                <option value="{{ $email->email }}" {{ old('company_email') == $email->email ? 'selected' : '' }}>
                                                    {{ $email->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_password">Email Password<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" id="email_password" name="email_password" class="form-control" 
                                                placeholder="Password will auto-fill when selecting a registered email" 
                                                value="{{ old('email_password') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="toggle_password">
                                                    <i class="fa fa-eye-slash" id="eye_icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Password will be automatically populated when selecting a registered email</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        <button type="submit" class="btn btn-primary" name="action" value="save">Create</button>&nbsp;&nbsp;
                                        <button type="submit" class="btn btn-success" name="action" value="save_and_create">Save & Create Another</button>&nbsp;&nbsp;
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <style>
        .input-group-append .btn {
            border-top-right-radius: 0.25rem !important;
            border-bottom-right-radius: 0.25rem !important;
        }
        .input-group-append .btn:focus {
            box-shadow: none;
        }
    </style>
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
                            } else {
                                $('#email_password').val('');
                            }
                        }
                    });
                } else {
                    $('#email_password').val('');
                }
            });
            
            // Handle custom email input
            $('#custom_company_email').on('input', function() {
                const customEmail = $(this).val();
                if (customEmail) {
                    // Clear the dropdown and set the custom email
                    $('#company_email').val('').trigger('change');
                    $('input[name="company_email"]').val(customEmail);
                    $('#email_password').val(''); // Clear password field for custom email
                } else {
                    // Restore the dropdown value
                    $('input[name="company_email"]').val($('#company_email').val());
                }
            });
            
            // Show/hide password
            $('#toggle_password').click(function() {
                const passwordField = $('#email_password');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                const eyeIcon = $('#eye_icon');
                eyeIcon.toggleClass('fa-eye fa-eye-slash');
            });
            
            // Set password field type to password initially
            $('#email_password').attr('type', 'password');
        });
    </script>
@stop
