@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">{{ $message }}</div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger">{{ $message }}</div>
        @endif
        <!-- Step Indicators -->
        <div class="step-indicator-container">
        <ul class="step-indicator">
            <div class="progress-bar"></div>
            <li class="step-item">
                <div class="step-icon" data-step="1"></div>
                <div class="step-text">Basic Information</div>
            </li>
            <li class="step-item">
                <div class="step-icon" data-step="2"></div>
                <div class="step-text">Contact Information</div>
            </li>
            <li class="step-item">
                <div class="step-icon" data-step="3"></div>
                <div class="step-text">School & Position</div>
            </li>
            <li class="step-item">
                <div class="step-icon" data-step="4"></div>
                <div class="step-text">Birth Place</div>
            </li>
            <li class="step-item">
                <div class="step-icon" data-step="5"></div>
                <div class="step-text">Home Address</div>
            </li>
            <li class="step-item">
                <div class="step-icon" data-step="6"></div>
                <div class="step-text">ID Numbers</div>
            </li>
        </ul>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Employee</h3>
                    <p style="text-align: right;">Make sure to fill those have <a style="color:red">*</a> to avoid error.</p>
                </div>
                    <div class="card-body">
                        <form id="createEmployeeForm" action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
                            @csrf
                            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                            <div id="step1" class="step">
                                <h5>Basic Information</h5>
                                <div id="imagePreview"></div>
                                <div class="row">
                                <div class="col-md-6">
                                 <div class="form-group">
                                        <label for="profile">Profile Picture</label>
                                        <input type="file" id="profile" name="profile" class="form-control" accept="image/*" onchange="previewImage(event)">
                                 </div>
                              </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="company_id">Company ID<span class="text-danger">*</span></label>
                                    <input type="text" id="company_id" name="company_id" class="form-control" placeholder="Enter employee company id" required>
                                    <small class="form-text text-muted">Format: 3 to 5 letters followed by 8 to 11 digits</small>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var companyIdInput = document.getElementById('company_id');

                                        companyIdInput.addEventListener('input', function() {
                                            // Remove any non-alphanumeric characters
                                            var currentValue = this.value.replace(/[^a-zA-Z0-9]/g, '');

                                            // Ensure the first 3-5 characters are letters
                                            var letters = currentValue.substr(0, 5).replace(/[^a-zA-Z]/g, '').toUpperCase();
                                            var numbers = currentValue.substr(letters.length).replace(/\D/g, '');

                                            // Limit letters to 5 and numbers to 10 digits
                                            letters = letters.substr(0, 5);
                                            numbers = numbers.substr(0, 11);

                                            // Combine letters and numbers
                                            var formattedValue = letters + (numbers.length > 0 ? '-' + numbers : '');

                                            // Update the input value
                                            this.value = formattedValue;

                                            // Validate the input
                                            if (letters.length >= 3 && letters.length <= 5 && numbers.length >= 8 && numbers.length <= 11) {
                                                this.setCustomValidity('');
                                            } else {
                                                this.setCustomValidity('Company ID must have 3 to 5 letters followed by 8 to 11 digits');
                                            }
                                        });
                                    });
                                </script>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name<span class="text-danger">*</span></label>
                                        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter employee first name"required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="middle_name">Middle Name:</label>
                                        <input type="text" id="middle_name" name="middle_name" class="form-control" placeholder="Enter employee middle name (optional)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter employee last name"required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="suffix">Suffix:</label>
                                        <input type="text" id="suffix" name="suffix" class="form-control" placeholder="Enter employee suffix ex:(jr, sr, etc...)">
                                    </div>
                                </div>
                                    <!-- Your basic information fields -->
                                </div>
                                <a href="{{ route('employees.index') }}" type="button" class="btn btn-info">Back</a>
                                <button type="button" class="btn btn-primary nextBtn">Next</button>
                            </div>
                            <div id="step2" class="step">
                                <p>Contact Information</p>
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_address">Email Address<span class="text-danger">*</span></label>
                                        <input type="email" id="email_address" name="email_address" class="form-control" placeholder="Enter employee email address"required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_no">Contact No<span class="text-danger">*</span></label>
                                    <input type="tel" id="contact_no" name="contact_no" class="form-control" placeholder="Enter your contact number" required>
                                    <small class="form-text text-muted">Please enter 11 digits (numbers only).</small>
                                </div>

                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var contactInput = document.getElementById('contact_no');

                                    contactInput.addEventListener('input', function() {
                                        // Remove any non-digit characters
                                        var cleanedValue = this.value.replace(/\D/g, '');

                                        // Limit to 11 digits
                                        cleanedValue = cleanedValue.slice(0, 11);

                                        // Update the input value
                                        this.value = cleanedValue;
                                    });
                                });
                                </script>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emergency_name">Emergency Contact Name<span class="text-danger">*</span></label>
                                        <input type="text" id="emergency_name" name="emergency_name" class="form-control" placeholder="Enter employee emergency contact name"required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_no">Emergency Contact No<span class="text-danger">*</span></label>
                                    <input type="tel" id="emergency_no" name="emergency_no" class="form-control" placeholder="Enter your emergency contact number" required>
                                    <small class="form-text text-muted">Please enter 11 digits (numbers only).</small>
                                </div>

                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var emergencyInput = document.getElementById('emergency_no');

                                    emergencyInput.addEventListener('input', function() {
                                        // Remove any non-digit characters
                                        var cleanedValue = this.value.replace(/\D/g, '');

                                        // Limit to 11 digits
                                        cleanedValue = cleanedValue.slice(0, 11);

                                        // Update the input value
                                        this.value = cleanedValue;
                                    });
                                });
                                </script>
                                </div>
                                    <!-- Your contact information fields -->
                                </div>
                                <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                <button type="button" class="btn btn-primary nextBtn">Next</button>
                            </div>
                            <div id="step3" class="step">
                                <p>School Attainment, Gender, Position and Department</p>
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="elementary">Elementary:</label>
                                        <input type="text" id="elementary" name="elementary" class="form-control" placeholder="Enter elementary"></input>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="secondary">Secondary:</label>
                                        <input type="text" id="secondary" name="secondary" class="form-control" placeholder="Enter secondary"></input>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tertiary">Tertiary:</label>
                                        <input type="text" id="tertiary" name="tertiary" class="form-control" placeholder="Enter tertiary"></input>
                                    </div>
                                </div>
                                <div class="col-md-6">
                <div class="form-group">
                    <label for="gender_id">Gender<span class="text-danger">*</span></label>
                    <select id="gender_id" name="gender_id" class="form-control" required>
                        <option value="">Select gender</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="department_id">Department<span class="text-danger">*</span></label>
                        <select id="department_id" name="department_id" class="form-control" required>
                            <option value="">Select department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="position_id">Position<span class="text-danger">*</span></label>
                        <select id="position_id" name="position_id" class="form-control" required>
                            <option value="">Select position</option>
                        </select>
                    </div>
                </div>
                        <!-- JavaScript to populate positions based on department selection -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Store the positions data in a JavaScript variable
                            const positions = @json($positions);
                            const cities = @json($city);
                            const barangays = @json($barangay);

                            // Department change handler
                            document.getElementById('department_id').addEventListener('change', function() {
                                const departmentId = this.value;
                                const positionDropdown = document.getElementById('position_id');
                                positionDropdown.innerHTML = '<option value="">Select position</option>';
                                
                                positions.forEach(position => {
                                    if (position.department_id == departmentId) {
                                        positionDropdown.innerHTML += `<option value="${position.id}">${position.name}</option>`;
                                    }
                                });
                            });

                            // Province change handler using jQuery and Select2
                            $('#province_id').on('change', function() {
                                const provinceId = this.value;
                                const cityDropdown = $('#city_id');
                                
                                cityDropdown.empty().append('<option value="">Select city</option>');
                                
                                cities.forEach(city => {
                                    if (city.province_id == provinceId) {
                                        cityDropdown.append(`<option value="${city.id}">${city.name}</option>`);
                                    }
                                });
                                
                                cityDropdown.trigger('change');
                            });

                            // City change handler
                            $('#city_id').on('change', function() {
                                const cityId = this.value;
                                const barangayDropdown = $('#barangay_id');
                                
                                barangayDropdown.empty().append('<option value="">Select barangay</option>');
                                
                                barangays.forEach(barangay => {
                                    if (barangay.city_id == cityId) {
                                        barangayDropdown.append(`<option value="${barangay.id}">${barangay.name}</option>`);
                                    }
                                });
                                
                                barangayDropdown.trigger('change');
                            });

                            // Initialize Select2
                            $('select').select2({
                                theme: 'bootstrap4',
                                width: '100%'
                            });
                        });
                    </script>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birth_date">Birth Date<span class="text-danger">*</span></label>
                                        <input type="date" id="birth_date" name="birth_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_hired">Date Hired<span class="text-danger">*</span></label>
                                        <input type="date" id="date_hired" name="date_hired" class="form-control" required>
                                    </div>
                                </div>
                                    <!-- Your school attainment, gender, position, and department fields -->
                                </div>
                                <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                <button type="button" class="btn btn-primary nextBtn">Next</button>
                            </div>
                            <div id="step4" class="step">
                                <p>Birth Place</p>
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birth_place_province">Province</label>
                                        <input type="text" id="birth_place_province" name="birth_place_province" class="form-control" placeholder="Enter province">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birth_place_city">City</label>
                                        <input type="text" id="birth_place_city" name="birth_place_city" class="form-control" placeholder="Enter city">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birth_place_barangay">Barangay</label>
                                        <input type="text" id="birth_place_barangay" name="birth_place_barangay" class="form-control" placeholder="Enter barangay">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="salary">Monthly Salary<span class="text-danger">*</span></label>
                                        <input type="number" id="salary" name="salary" class="form-control" placeholder="Enter monthly salary" step="0.01" required>
                                    </div>
                                </div>
                                    <!-- Your birth place fields -->
                                </div>
                                <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                <button type="button" class="btn btn-primary nextBtn">Next</button>
                            </div>
                            <div id="step5" class="step">
                                <p>Home Address</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="province_id">Province<span class="text-danger">*</span></label>
                                            <select id="province_id" name="province_id" class="form-control" required>
                                                <option value="">Select province</option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city_id">City<span class="text-danger">*</span></label>
                                            <select id="city_id" name="city_id" class="form-control" required>
                                                <option value="">Select city</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barangay_id">Barangay<span class="text-danger">*</span></label>
                                            <select id="barangay_id" name="barangay_id" class="form-control" required>
                                                <option value="">Select barangay</option>
                                            </select>
                                        </div>
                                    </div>
                            <!-- Address script -->
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

            // Existing event listeners
            $('#department_id').on('change', function() {
                var departmentId = this.value;
                var positionDropdown = $('#position_id');
                positionDropdown.empty().append('<option value="">Select position</option>');
                @foreach($positions as $position)
                    if ("{{ $position->department_id }}" == departmentId) {
                        positionDropdown.append('<option value="{{ $position->id }}">{{ $position->name }}</option>');
                    }
                @endforeach
                positionDropdown.trigger('change'); // Refresh Select2
            });

            $('#province_id').on('change', function() {
                var provinceId = this.value;
                var cityDropdown = $('#city_id');
                cityDropdown.empty().append('<option value="">Select city</option>');
                @foreach($city as $city)
                    if ("{{ $city->province_id }}" == provinceId) {
                        cityDropdown.append('<option value="{{ $city->id }}">{{ $city->name }}</option>');
                    }
                @endforeach
                cityDropdown.trigger('change'); // Refresh Select2
            });

            $('#city_id').on('change', function() {
                var cityId = this.value;
                var barangayDropdown = $('#barangay_id');
                barangayDropdown.empty().append('<option value="">Select barangay</option>');
                @foreach($barangay as $barangay)
                    if ("{{ $barangay->city_id }}" == cityId) {
                        barangayDropdown.append('<option value="{{ $barangay->id }}">{{ $barangay->name }}</option>');
                    }
                @endforeach
                barangayDropdown.trigger('change'); // Refresh Select2
            });
        });
    </script>
@stop
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zip_code">Zip Code<span class="text-danger">*</span></label>
                                        <input type="number" id="zip_code" name="zip_code" class="form-control" placeholder="Enter zip code">
                                    </div>
                                </div>
                                    <!-- Your home address fields -->
                                </div>
                                <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                <button type="button" class="btn btn-primary nextBtn">Next</button>
                            </div>
                            <div id="step6" class="step">
                                <p>Identification Card Numbers</p>
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sss_no">SSS No:</label>
                                        <input type="text" id="sss_no" name="sss_no" class="form-control" placeholder="Enter SSS number">
                                    </div>
                                </div>

                                <script>
                                    document.getElementById('sss_no').addEventListener('input', function (e) {
                                        let sssNumber = e.target.value.replace(/\D/g, ''); // Remove non-digit characters
                                        if (sssNumber.length > 10) {
                                            sssNumber = sssNumber.substr(0, 10); // Limit to 10 digits
                                        }
                                        // Format: XX-XXXXXXXXX-X
                                        let formattedSSS = '';
                                        if (sssNumber.length > 2) {
                                            formattedSSS = sssNumber.substr(0, 2) + '-';
                                            if (sssNumber.length > 3) {
                                                formattedSSS += sssNumber.substr(2, sssNumber.length - 3) + '-';
                                            }
                                            formattedSSS += sssNumber.substr(sssNumber.length - 1);
                                        } else {
                                            formattedSSS = sssNumber;
                                        }
                                        e.target.value = formattedSSS;
                                    });
                                </script>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="pagibig_no">PAGIBIG No:</label>
                                    <input type="text" id="pagibig_no" name="pagibig_no" class="form-control" placeholder="Enter PAGIBIG number">
                                </div>

                                <script>
                                    document.getElementById('pagibig_no').addEventListener('input', function (e) {
                                        // Remove non-numeric characters
                                        let input = e.target.value.replace(/\D/g, '');

                                        // Format as XXXX-XXXX-XXXX (12 digits)
                                        if (input.length > 12) {
                                            input = input.slice(0, 12);
                                        }

                                        let formatted = '';
                                        for (let i = 0; i < input.length; i++) {
                                            if (i === 4 || i === 8) {
                                                formatted += '-';
                                            }
                                            formatted += input[i];
                                        }

                                        e.target.value = formatted;
                                    });
                                </script>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tin_no">TIN No:</label>
                                        <input type="number" id="tin_no" name="tin_no" class="form-control" placeholder="Enter tin number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="philhealth_no">Phil Health No:</label>
                                    <input type="text" id="philhealth_no" name="philhealth_no" class="form-control" placeholder="Enter PhilHealth number">
                                </div>

                                <script>
                                    document.getElementById('philhealth_no').addEventListener('input', function (e) {
                                        // Remove non-numeric characters
                                        let input = e.target.value.replace(/\D/g, '');

                                        // Format as XXXX-XXXXXX-X (12 digits)
                                        if (input.length > 12) {
                                            input = input.slice(0, 12);
                                        }

                                        let formatted = '';
                                        for (let i = 0; i < input.length; i++) {
                                            if (i === 2 || i === 11) {
                                                formatted += '-';
                                            }
                                            formatted += input[i];
                                        }

                                        e.target.value = formatted;
                                    });
                                </script>
                                </div>
                                    <!-- Your identification card numbers fields -->
                                </div>
                                <button type="button" class="btn btn-secondary prevBtn">Previous</button>
                                <button type="submit" class="btn btn-primary" name="action" value="save">Create</button>
                                <button type="submit" class="btn btn-success" name="action" value="save_and_create">Save & Create Another</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color: #007bff;
            --success-color: #28a745;
            --neutral-color: #e9ecef;
            --text-color: #495057;
            --step-size: 32px;
            --transition-speed: 0.3s;
        }

        .step-indicator-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            position: relative;
            padding: 0;
            list-style-type: none;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: calc(var(--step-size) / 2);
            left: 0;
            width: 100%;
            height: 4px;
            background-color: var(--neutral-color);
            z-index: 1;
        }

        .progress-bar {
            position: absolute;
            top: calc(var(--step-size) / 2);
            left: 0;
            width: 0;
            height: 4px;
            background-color: var(--primary-color);
            transition: width var(--transition-speed) ease;
            z-index: 2;
        }

        .step-item {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 3;
        }

        .step-icon {
            width: var(--step-size);
            height: var(--step-size);
            border-radius: 50%;
            background-color: var(--neutral-color);
            color: var(--text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            transition: all var(--transition-speed) ease;
            position: relative;
            overflow: hidden;
        }

        .step-icon::after {
            content: attr(data-step);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all var(--transition-speed) ease;
        }

        .step-item.active .step-icon,
        .step-item.completed .step-icon {
            background-color: var(--success-color);
            color: white;
        }

        .step-item.completed .step-icon::after {
            content: '✓';
            transform: translate(-50%, -50%) scale(1.2);
        }

        .step-text {
            font-size: 14px;
            color: var(--text-color);
            margin-top: 8px;
            font-weight: 500;
            transition: color var(--transition-speed) ease;
        }

        .step-item.active .step-text {
            color: var(--primary-color);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .step-indicator {
                flex-direction: column;
                align-items: flex-start;
            }

            .step-indicator::before {
                top: 0;
                left: calc(var(--step-size) / 2);
                width: 4px;
                height: 100%;
            }

            .progress-bar {
                top: 0;
                left: calc(var(--step-size) / 2);
                width: 4px;
                height: 0;
            }

            .step-item {
                display: flex;
                align-items: center;
                margin-bottom: 20px;
            }

            .step-icon {
                margin: 0 15px 0 0;
            }

            .step-text {
                margin-top: 0;
                text-align: left;
            }
        }
    </style>

<script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('createEmployeeForm');
            const steps = form.querySelectorAll('.step');
            const stepItems = document.querySelectorAll('.step-item');
            const progressBar = document.querySelector('.progress-bar');
            let currentStep = 0;

            function showStep(step) {
                steps.forEach((s, index) => {
                    s.style.display = index === step ? 'block' : 'none';
                });

                updateStepIndicator(step);
            }

            function updateStepIndicator(step) {
                stepItems.forEach((item, index) => {
                    if (index < step) {
                        item.classList.add('completed');
                        item.classList.remove('active');
                    } else if (index === step) {
                        item.classList.add('active');
                        item.classList.remove('completed');
                    } else {
                        item.classList.remove('completed', 'active');
                    }
                });

                const progress = (step / (stepItems.length - 1)) * 100;
                progressBar.style.width = `${progress}%`;

                // For mobile layout
                if (window.innerWidth <= 768) {
                    progressBar.style.height = `${progress}%`;
                }
            }

            // Navigation buttons
            form.querySelectorAll('.prevBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            form.querySelectorAll('.nextBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (validateStep(currentStep)) {
                        if (currentStep < steps.length - 1) {
                            currentStep++;
                            showStep(currentStep);
                        }
                    }
                });
            });

            function validateStep(step) {
                const inputs = steps[step].querySelectorAll('input, select, textarea');
                let isValid = true;
                inputs.forEach(input => {
                    if (input.hasAttribute('required') && !input.value.trim()) {
                        isValid = false;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                return isValid;
            }

            // Initialize
            showStep(currentStep);

            // Handle window resize for responsive behavior
            window.addEventListener('resize', () => {
                updateStepIndicator(currentStep);
            });
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function capitalizeInput(event) {
            event.target.value = event.target.value.toUpperCase();
        }

        const inputsToCapitalize = [
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'elementary',
            'secondary',
            'tertiary',
            'birth_place_province',
            'birth_place_city',
            'birth_place_barangay',
            'emergency_name'
        ];

        inputsToCapitalize.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', capitalizeInput);
            }
        });
    });

</script>

@endsection
