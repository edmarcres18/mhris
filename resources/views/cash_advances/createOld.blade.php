@extends('layouts.app')

@section('content')
<br>
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-info-circle fa-2x mr-3"></i>
        <div>
            @php
                $employee = App\Models\Employee::where('email_address', auth()->user()->email)->first();
                $hasActiveLoan = $employee ? App\Models\CashAdvance::where('employee_id', $employee->id)
                    ->where('status', 'active')
                    ->exists() : false;
                $oneYearAgo = now()->subYear();
                $isEligible = $employee && $employee->date_hired <= $oneYearAgo;
            @endphp

            @if($isEligible)
                <h5 class="alert-heading mb-1">Loan Status Notice</h5>
                @if($hasActiveLoan)
                    <p class="mb-0">Please ensure to regularly check your loan balance. Once fully paid, contact the administrators to update your loan status.</p>
                @else
                    <p class="mb-0">You are eligible to apply for a company loan. Please ensure all previous loans are properly closed before applying for a new one.</p>
                @endif
            @else
                <h5 class="alert-heading mb-1">Important Notice</h5>
                <p class="mb-0">Eligibility for cash advances is restricted to employees with a minimum of one year of service.</p>
            @endif
        </div>
    </div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-{{ auth()->user()->hasRole('Employee') ? '8' : '12' }}">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Apply for Company Loan</h3>
                </div>

                <div class="card-body">
                    @if ($employees->isEmpty())
                        <div class="ineligible-message text-center py-5">
                            <div class="marker-icon mb-4">
                                <div class="icon-wrapper">
                                    <i class="fas fa-exclamation-circle fa-5x text-warning"></i>
                                </div>
                            </div>
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h2 class="mb-3 text-primary" style="font-size: 1.75rem; font-weight: 600;">
                                        Thank you for your interest in Company Loan!
                                    </h2>
                                    <p class="text-muted mb-4" style="font-size: 1.1rem;">
                                        You are currently not eligible for Company Loan.<br>
                                        A minimum of one year service is required to apply.
                                    </p>
                                    <div class="alert alert-info" role="alert">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Keep working and we'll let you know when you can apply again.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <form action="{{ config('app.env') === 'local' ? url(route('cash_advances.store', [], false)) : secure_url(route('cash_advances.store', [], false)) }}" method="POST" id="cashAdvanceForm" autocomplete="off">
                            @csrf
                            {{-- Add CSRF token and nonce for additional security --}}
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="nonce" value="{{ Str::random(32) }}">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_id">Employee <span class="text-danger">*</span></label>
                                        <select name="employee_id" id="employee_id" 
                                            class="form-control select2 @error('employee_id') is-invalid @enderror" 
                                            required 
                                            {{-- Prevent XSS by escaping all values --}}
                                            data-placeholder="{{ e('Select Employee') }}">
                                            <option value="" selected disabled>Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ e($employee->id) }}">
                                                    {{ e($employee->company_id) }} {{ e($employee->last_name) }} {{ e($employee->first_name) }}, 
                                                    {{ e($employee->middle_name ?? '') }} {{ e($employee->suffix ?? '') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ e($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cash_advance_amount">Amount (PHP) <span class="text-danger">*</span></label>
                                        <input type="number" 
                                            name="cash_advance_amount" 
                                            id="cash_advance_amount" 
                                            class="form-control @error('cash_advance_amount') is-invalid @enderror" 
                                            required
                                            {{-- Add input validation constraints --}}
                                            min="1"
                                            max="999999999"
                                            step="0.01"
                                            pattern="[0-9]*"
                                            {{-- Sanitize input --}}
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                        @error('cash_advance_amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ e($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="repayment_term">Repayment Term (Months) <span class="text-danger">*</span></label>
                                        <input type="number" 
                                            name="repayment_term" 
                                            id="repayment_term" 
                                            class="form-control @error('repayment_term') is-invalid @enderror" 
                                            required
                                            {{-- Add strict input validation --}}
                                            min="1"
                                            max="24"
                                            step="1"
                                            pattern="\d*"
                                            {{-- Sanitize input --}}
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                        @error('repayment_term')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ e($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monthly_repayment">Estimated Monthly Repayment (PHP)</label>
                                        <input type="text" 
                                            id="monthly_repayment" 
                                            class="form-control" 
                                            readonly 
                                            {{-- Prevent tampering --}}
                                            tabindex="-1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p><small><span class="text-danger">*</span> Required fields</small></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                        @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agreementModal">Submit Application</button>
                                        @else
                                            <button type="submit" class="btn btn-primary">Submit Application</button>
                                        @endif
                                        &nbsp;&nbsp;
                                        @if(!auth()->user()->hasRole('Employee'))
                                        <a href="{{ route('cash_advances.index') }}" class="btn btn-info">Back</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="signature" id="signature">
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole('Employee'))
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title text-white">
                        <i class="fas fa-history mr-2"></i>My Recent Applications
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $employee = App\Models\Employee::where('email_address', auth()->user()->email)->first();
                        $recentApplications = $employee ? App\Models\CashAdvance::where('employee_id', $employee->id)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get() : collect();
                    @endphp

                    @if($recentApplications->isEmpty())
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                            <p class="mb-0">No loan applications yet</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentApplications as $application)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('cash_advances.show', $application->id) }}" class="text-decoration-none">
                                                <h6 class="mb-1">₱{{ number_format($application->cash_advance_amount, 2) }}</h6>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt mr-1"></i>
                                                    {{ $application->created_at->format('M d, Y') }}
                                                </small>
                                            </a>
                                        </div>
                                        <div>
                                            <span class="badge badge-{{ $application->status === 'pending' ? 'warning' : ($application->status === 'active' ? 'success' : 'secondary') }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                            @if($application->status !== 'pending')
                                                <a href="{{ route('cash_advances.ledger', $application->id) }}" 
                                                   class="btn btn-sm btn-outline-primary ml-2">
                                                    <i class="fas fa-book-open"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('loans.my-loans') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list mr-1"></i>View All Loans
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Agreement Modal -->
<div class="modal fade" id="agreementModal" tabindex="-1" role="dialog" aria-labelledby="agreementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="agreementModalLabel">
                    <i class="fas fa-file-contract mr-2"></i>
                    {{ config('app.company_name') }} Company Loan Agreement
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Reference Number Section -->
                <div class="text-right mb-3">
                    <p class="mb-1"><strong>Reference Number:</strong> <span id="modal-reference-number">-</span></p>
                    <p class="mb-0"><strong>Date of Application:</strong> {{ now()->format('F d, Y') }}</p>
                </div>

                <!-- Loan Details Section -->
                <div class="loan-details mb-4">
                    <h6 class="font-weight-bold border-bottom pb-2 text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Application Details
                    </h6>
                    <div class="card">
                        <div class="card-body bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td><strong>Employee Name:</strong></td>
                                            <td><span id="modal-employee-name">-</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Employee ID:</strong></td>
                                            <td><span id="modal-employee-id">-</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Department:</strong></td>
                                            <td><span id="modal-department">-</span></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td><strong>Loan Amount:</strong></td>
                                            <td>₱<span id="modal-advance-amount">0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Term Length:</strong></td>
                                            <td><span id="modal-repayment-term">0</span> months</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Monthly Payment:</strong></td>
                                            <td>₱<span id="modal-monthly-repayment">0.00</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agreement Content -->
                <div class="agreement-content">
                    <h6 class="font-weight-bold border-bottom pb-2 text-primary">
                        <i class="fas fa-file-alt mr-2"></i>Terms and Conditions
                    </h6>

                    <div class="card">
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <p class="font-weight-bold">This Cash Advance Agreement (the "Agreement") is entered into on {{ now()->format('F d, Y') }} by and between:</p>

                            <div class="mb-4 pl-4">
                                <p><strong>Company:</strong><br>
                                {{ config('app.company_name') }}<br>
                                {{ config('app.company_address') }},{{ config('app.company_city') }}, {{ config('app.company_zip') }}</p>

                                <p><strong>Employee:</strong><br>
                                <span id="modal-employee-name">-</span><br>
                                Employee ID: <span id="modal-employee-id">-</span></p>
                            </div>

                            <h6 class="font-weight-bold">WHEREAS:</h6>
                            <div class="mb-4 pl-4">
                                <p>1. The Employee has requested a cash advance from the Company;</p>
                                <p>2. The Company has agreed to provide the cash advance subject to the terms and conditions set forth herein.</p>
                            </div>

                            <h6 class="font-weight-bold">NOW THEREFORE, the parties agree as follows:</h6>

                            <ol class="terms-list">
                                <li><strong>Loan Amount and Repayment:</strong>
                                    <ul>
                                        <li>Principal Amount: ₱<span id="modal-advance-amount">0.00</span></li>
                                        <li>Repayment Period: <span id="modal-term-text">0</span> months</li>
                                        <li>Monthly Installment: ₱<span id="modal-payment-text">0.00</span></li>
                                        <li>Total Repayment Amount: ₱<span id="modal-total-text">0.00</span></li>
                                    </ul>
                                </li>

                                <li><strong>Repayment Method:</strong>
                                    <ul>
                                        <li>Monthly installments will be automatically deducted from my salary</li>
                                        <li>Deductions will commence from the next salary payment after the cash advance is disbursed</li>
                                        <li>I authorize the company to make these regular deductions</li>
                                    </ul>
                                </li>

                                <li><strong>Early Repayment:</strong>
                                    <ul>
                                        <li>I may make early repayments without penalty</li>
                                        <li>Partial or full settlement of the remaining balance is permitted</li>
                                    </ul>
                                </li>

                                <li><strong>Default and Consequences:</strong>
                                    <ul>
                                        <li>Failure to maintain employment will result in immediate repayment of the remaining balance</li>
                                        <li>The company reserves the right to deduct the outstanding amount from final settlement, benefits, or incentives</li>
                                        <li>Legal action may be pursued for unpaid balances</li>
                                    </ul>
                                </li>

                                <li><strong>Declarations:</strong>
                                    <ul>
                                        <li>I confirm all information provided is true and accurate</li>
                                        <li>I am not under any other loan agreement that would affect my ability to repay</li>
                                        <li>I understand this cash advance is subject to management approval</li>
                                    </ul>
                                </li>
                            </ol>

                            <p class="mt-3"><strong>Note:</strong> This agreement is legally binding upon acceptance. Please read carefully before proceeding.</p>
                        </div>
                    </div>
                </div>

                <!-- Signature Section -->
                <div class="signature-section mt-4">
                    <h6 class="font-weight-bold border-bottom pb-2 text-primary">
                        <i class="fas fa-pen-alt mr-2"></i>Digital Signature
                    </h6>
                    <div class="card">
                        <div class="card-body">
                            <label class="font-weight-bold">Please sign below <span class="text-danger">*</span></label>
                            <div class="signature-container border rounded p-3">
                                <canvas id="signaturePad"></canvas>
                            </div>
                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-sm btn-secondary" id="clearSignature">
                                    <i class="fas fa-eraser"></i> Clear Signature
                                </button>
                                <small class="text-muted"><i class="fas fa-info-circle"></i> Use your mouse or touch screen to sign</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acknowledgment -->
                <div class="acknowledgment mt-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreementCheckbox" hidden>
                                <label class="form-check-label" for="agreementCheckbox">
                                    I, <span id="modal-employee-name-confirm">-</span>, hereby acknowledge that I have read,
                                    understood, and agree to be bound by the terms and conditions of this cash advance agreement.
                                    I confirm that all information provided is true and accurate to the best of my knowledge.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmSubmit" disabled>
                    <i class="fas fa-check mr-1"></i>Confirm and Submit
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <style>
        .terms-list {
            padding-left: 20px;
        }

        .terms-list li {
            margin-bottom: 15px;
        }

        .terms-list ul {
            padding-left: 20px;
            margin-top: 5px;
        }

        .terms-list ul li {
            margin-bottom: 5px;
            list-style-type: disc;
        }

        .agreement-content {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .signature-container {
            background-color: #fff;
            position: relative;
            width: 100%;
            min-height: 200px;
        }

        #signaturePad {
            position: absolute;
            left: 0;
            top: 0;
            touch-action: none;
            width: 100%;
            height: 100%;
        }

        .signature-container::after {
            content: '';
            position: absolute;
            bottom: 50px;
            left: 20px;
            right: 20px;
            border-bottom: 1px dashed #ccc;
        }

        .ineligible-message {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem;
        }

        .icon-wrapper {
            margin-bottom: 1.5rem;
        }

        .icon-wrapper i {
            color: #ffc107;
        }

        .card.shadow-sm {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }

        .card.shadow-sm:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .ineligible-message {
                padding: 1rem;
            }

            .ineligible-message h2 {
                font-size: 1.5rem !important;
            }

            .ineligible-message p {
                font-size: 1rem !important;
            }
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Show success message if exists
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6'
                });
            @endif

            // Show error message if exists
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33'
                });
            @endif

            // Show validation errors if exist
            @if(count($errors) > 0)
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `<div class="text-left">
                            <strong>Please correct the following errors:</strong><br><br>
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                           </div>`,
                    confirmButtonColor: '#d33'
                });
            @endif

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Initialize signature pad
            let signaturePad = null;

            function initSignaturePad() {
                const canvas = document.querySelector('#signaturePad');
                if (!canvas) return;

                // Set canvas dimensions
                const containerWidth = canvas.parentElement.clientWidth;
                canvas.width = containerWidth - 40;
                canvas.height = 200;

                // Initialize signature pad
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)'
                });
            }

            // Calculate monthly repayment
            function calculateMonthlyRepayment() {
                const amount = parseFloat($('#cash_advance_amount').val()) || 0;
                const term = parseInt($('#repayment_term').val()) || 1;
                const monthlyRepayment = amount / term;
                $('#monthly_repayment').val(monthlyRepayment.toFixed(2));
            }

            // Attach event listeners for calculation
            $('#cash_advance_amount, #repayment_term').on('input', calculateMonthlyRepayment);

            // Initialize calculation
            calculateMonthlyRepayment();

            // Modal events
            $('#agreementModal').on('shown.bs.modal', function() {
                initSignaturePad();
            });

            // Clear signature
            $('#clearSignature').click(function(e) {
                e.preventDefault();
                if (signaturePad) {
                    signaturePad.clear();
                }
            });

            // Modified form validation alert
            $('#agreementModal').on('show.bs.modal', function(e) {
                if (!$('#cashAdvanceForm')[0].checkValidity()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Validation',
                        text: 'Please fill in all required fields before proceeding.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                const selectedOption = $('#employee_id option:selected');
                const employeeName = selectedOption.text();
                // Extract company_id from the option text (it's the first part before the name)
                const companyId = selectedOption.text().split(' ')[0];
                const amount = parseFloat($('#cash_advance_amount').val()) || 0;
                const term = parseInt($('#repayment_term').val()) || 1;
                const monthlyRepayment = amount / term;
                const totalRepayment = amount;

                // Generate reference number
                const date = new Date();
                const dateString = date.getFullYear() +
                    String(date.getMonth() + 1).padStart(2, '0') +
                    String(date.getDate()).padStart(2, '0');
                const randomNum = String(Math.floor(Math.random() * 9999) + 1).padStart(4, '0');
                const referenceNumber = `CA-${dateString}-${randomNum}`;

                $('#modal-employee-name, #modal-employee-name-confirm').text(employeeName);
                $('#modal-employee-id').text(companyId);  // Use company_id instead of employee ID
                $('#modal-advance-amount').text(amount.toFixed(2));
                $('#modal-repayment-term, #modal-term-text').text(term);
                $('#modal-monthly-repayment, #modal-payment-text').text(monthlyRepayment.toFixed(2));
                $('#modal-total-repayment, #modal-total-text').text(totalRepayment.toFixed(2));

                $('#modal-reference-number').text(referenceNumber);

                // Add hidden input for reference number
                if (!$('#reference_number').length) {
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'reference_number',
                        name: 'reference_number',
                        value: referenceNumber
                    }).appendTo('#cashAdvanceForm');
                } else {
                    $('#reference_number').val(referenceNumber);
                }

                $('#agreementCheckbox').prop('checked', false);
                $('#confirmSubmit').prop('disabled', true);
            });

            // Modified agreement checkbox validation
            $('#confirmSubmit').click(function(e) {
                e.preventDefault();

                if (!$('#agreementCheckbox').is(':checked')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Terms & Conditions',
                        text: 'Please accept the terms and conditions to proceed.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                if (signaturePad && signaturePad.isEmpty()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Signature Required',
                        text: 'Please provide your digital signature before submitting.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                // Show confirmation dialog before submitting
                Swal.fire({
                    icon: 'question',
                    title: 'Confirm Submission',
                    text: 'Are you sure you want to submit this loan application?',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Get signature data and submit form
                        if (signaturePad) {
                            const signatureData = signaturePad.toDataURL('image/png');
                            $('#signature').val(signatureData);
                        }
                        $('#cashAdvanceForm').submit();
                    }
                });
            });

            // Agreement checkbox
            $('#agreementCheckbox').change(function() {
                $('#confirmSubmit').prop('disabled', !$(this).is(':checked'));
            });

            // Update modal content
            $('#agreementModal').on('show.bs.modal', function(e) {
                if (!$('#cashAdvanceForm')[0].checkValidity()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Validation',
                        text: 'Please fill in all required fields before proceeding.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                const selectedOption = $('#employee_id option:selected');
                const employeeName = selectedOption.text();
                // Extract company_id from the option text (it's the first part before the name)
                const companyId = selectedOption.text().split(' ')[0];
                const amount = parseFloat($('#cash_advance_amount').val()) || 0;
                const term = parseInt($('#repayment_term').val()) || 1;
                const monthlyRepayment = amount / term;
                const totalRepayment = amount;

                // Generate reference number
                const date = new Date();
                const dateString = date.getFullYear() +
                    String(date.getMonth() + 1).padStart(2, '0') +
                    String(date.getDate()).padStart(2, '0');
                const randomNum = String(Math.floor(Math.random() * 9999) + 1).padStart(4, '0');
                const referenceNumber = `CA-${dateString}-${randomNum}`;

                $('#modal-employee-name, #modal-employee-name-confirm').text(employeeName);
                $('#modal-employee-id').text(companyId);  // Use company_id instead of employee ID
                $('#modal-advance-amount').text(amount.toFixed(2));
                $('#modal-repayment-term, #modal-term-text').text(term);
                $('#modal-monthly-repayment, #modal-payment-text').text(monthlyRepayment.toFixed(2));
                $('#modal-total-repayment, #modal-total-text').text(totalRepayment.toFixed(2));

                $('#modal-reference-number').text(referenceNumber);

                // Add hidden input for reference number
                if (!$('#reference_number').length) {
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'reference_number',
                        name: 'reference_number',
                        value: referenceNumber
                    }).appendTo('#cashAdvanceForm');
                } else {
                    $('#reference_number').val(referenceNumber);
                }

                $('#agreementCheckbox').prop('checked', true);
                $('#confirmSubmit').prop('disabled', false);
            });
        });
    </script>
@endsection
