@extends('layouts.app')


@section('content')
<style>
    @page {
        size: landscape;
    }

    .leave-form {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .logo {
        width: 200px;
        margin-right: 20px;
    }

    .logo img {
        width: 100%;
    }

    .title {
        flex-grow: 1;
        text-align: right;
        font-size: 24px;
        font-weight: bold;
    }

    .form-row {
        display: flex;
        margin-bottom: 15px;
        gap: 60px;
    }

    .form-group {
        flex: 1;
    }

    .form-group label {
        display: inline-block;
        font-weight: bold;
    }

    .form-group label:after {
        content: ":";
    }

    .input-line {
        display: inline-block;
        border-bottom: 1px solid #000;
        min-width: 50px;
        width: auto;
        padding: 0 5px;
        margin-left: 5px;
    }

    .employee-request {
        margin: 20px 0;
    }

    .request-text {
        margin-bottom: 10px;
    }

    .checkbox-group {
        margin-left: 20px;
    }

    .checkbox-item {
        margin: 5px 0;
    }

    .checkbox-box {
        display: inline-block;
        width: 15px;
        height: 15px;
        border: 1px solid #000;
        margin-right: 10px;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        margin: 15px auto;
    }

    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
    }

    .others-section {
        margin: 20px 0;
    }

    .others-line {
        border-bottom: 1px solid #000;
        width: 100%;
        margin: 5px 0;
    }

    .recommendation-section {
        margin: 20px 0;
    }

    .radio-group {
        margin: 10px 0;
    }

    .radio-circle {
        display: inline-block;
        width: 15px;
        height: 15px;
        border: 1px solid #000;
        border-radius: 50%;
        margin-right: 10px;
    }

    .signature-section {
        margin-top: 30px;
        display: flex;
        justify-content: space-between;
        padding: 0 50px;
    }

    .signature-block {
        width: 250px;
        text-align: center;
    }

    .signature-container {
        height: 100px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .signature-line {
        width: 100%;
        border-bottom: 1px solid #000;
        position: relative;
        min-height: 60px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .signature-image {
        max-height: 60px;
        max-width: 200px;
        position: absolute;
        bottom: 0;
        mix-blend-mode: darken;
        opacity: 0.8;
    }

    .signature-label {
        margin-top: 5px;
        font-weight: normal;
    }

    .signature-pad {
        width: 100%;
        height: 200px;
        background-color: white;
        border: 1px solid #ccc;
    }

    .status-indicator {
        position: relative;
        z-index: 1;
    }

    .approval-details img {
        mix-blend-mode: darken;
    }

    .btn-responsive {
        border-radius: 5px;
        padding: 10px 20px;
        transition: background-color 0.3s;
    }

    /* Add these responsive styles */
    @media screen and (max-width: 1024px) {
        .leave-form {
            max-width: 95%;
            padding: 15px;
        }

        .form-row {
            gap: 30px;
        }

        .signature-section {
            padding: 0 25px;
        }
    }

    @media screen and (max-width: 768px) {
        .leave-form {
            padding: 10px;
        }

        .header {
            flex-direction: column;
            text-align: center;
        }

        .logo {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .title {
            text-align: center;
        }

        .form-row {
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            width: 100%;
        }

        .input-line {
            width: 100%;
            display: block;
            margin: 5px 0;
        }

        table {
            width: 100%;
            font-size: 14px;
        }

        .signature-section {
            flex-direction: column;
            align-items: center;
            gap: 30px;
            padding: 0;
        }

        .signature-block {
            width: 100%;
            max-width: 300px;
        }

        /* Modal responsiveness */
        .modal-dialog {
            margin: 10px;
        }

        .signature-pad {
            width: 100%;
            height: 150px;
        }
    }

    @media screen and (max-width: 480px) {
        .header {
            margin-bottom: 15px;
        }

        .logo {
            width: 150px;
        }

        .title {
            font-size: 20px;
        }

        .checkbox-group {
            margin-left: 10px;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 5px;
        }

        .btn-responsive {
            width: 100%;
            margin-bottom: 10px;
        }

        /* Improve form readability on small screens */
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .checkbox-item {
            margin: 8px 0;
        }

        .recommendation-section {
            font-size: 14px;
        }

        .radio-group {
            margin: 8px 0;
        }
    }

    /* Print-specific styles */
    @media print {
        .leave-form {
            max-width: 100%;
            margin: 0;
            padding: 10px;
        }

        .form-row {
            page-break-inside: avoid;
        }

        .signature-section {
            page-break-inside: avoid;
        }

        table {
            page-break-inside: avoid;
        }
    }

    /* Utility classes for better mobile display */
    .text-break-mobile {
        word-break: break-word;
    }

    .d-flex-mobile {
        display: flex;
        flex-wrap: wrap;
    }

    /* Button container responsiveness */
    .button-container {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    @media screen and (max-width: 480px) {
        .button-container {
            flex-direction: column;
        }
    }
</style>
<div class="button-container">
    <button type="button" class="btn btn-info btn-responsive" onclick="printLeaveForm()">
        <i class="fas fa-print"></i> Print
    </button>
</div>
<div class="leave-form">
    <div class="header">
        <div class="logo">
            <img src="{{ asset('vendor/adminlte/dist/img/LOGO4.png') }}" alt="MHR Property">
        </div>
        <div class="title">
            APPLICATION LEAVE
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>NAME</label>
            <span class="input-line">{{ $leave->employee->last_name }}, {{ $leave->employee->first_name }} {{ $leave->employee->middle_name ?? ' ' }} {{ $leave->employee->suffix ?? ' ' }}</span>
        </div>
        <div class="form-group">
            <label>DEPARTMENT</label>
            <span class="input-line" data-field="department">{{ $leave->employee->department->name }}</span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>POSITION</label>
            <span class="input-line" data-field="position">{{ $leave->employee->position->name }}</span>
        </div>
        <div class="form-group">
            <label>DATE FILED</label>
            <span class="input-line" data-field="date_filed">{{ $leave->created_at ? $leave->created_at->format('m/d/Y') : '' }}</span>
        </div>
    </div>

    <div class="employee-request">
        <div class="request-text">
            I hereby request for <span class="input-line" data-field="duration">
                @if($leave->diffhours)
                    @php
                        $hours = $leave->diffhours['hours'];
                        $minutes = $leave->diffhours['minutes'] ?? 0;

                        // Convert everything to days with decimal places
                        $totalDays = $hours / 24 + $minutes / (24 * 60);

                        // Round to 2 decimal places
                        $totalDays = round($totalDays, 2);
                    @endphp
                    {{ $totalDays }}
                @else
                    {{ $leave->duration ?? '' }}
                @endif
            </span> day/s
        </div>
        <div class="checkbox-group">
            <div class="checkbox-item">
                <span class="checkbox-box" data-leave-type="Vacation Leave" style="{{ $leave->type->name === 'Vacation Leave' ? 'background-image: url(\'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" fill="%23000"/></svg>\'); background-position: center; background-repeat: no-repeat;' : '' }}"></span> Vacation Leave
            </div>
            <div class="checkbox-item">
                <span class="checkbox-box" data-leave-type="Sick Leave" style="{{ $leave->type->name === 'Sick Leave' ? 'background-image: url(\'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" fill="%23000"/></svg>\'); background-position: center; background-repeat: no-repeat;' : '' }}"></span> Sick Leave
            </div>
            <div class="checkbox-item">
                <span class="checkbox-box" data-leave-type="Emergency Leave" style="{{ $leave->type->name === 'Emergency Leave' ? 'background-image: url(\'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" fill="%23000"/></svg>\'); background-position: center; background-repeat: no-repeat;' : '' }}"></span> Emergency Leave
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th></th>
                <th>Credits</th>
                <th>Taken</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Vacation Leave</td>
                <td>5</td>
                <td data-field="vacation-taken">{{ $vacationTaken }}</td>
                <td data-field="vacation-balance">{{ $vacationBalance }}</td>
            </tr>
            <tr>
                <td>Sick Leave</td>
                <td>7</td>
                <td data-field="sick-taken">{{ $sickTaken }}</td>
                <td data-field="sick-balance">{{ $sickBalance }}</td>
            </tr>
            <tr>
                <td>Emergency Leave</td>
                <td>3</td>
                <td data-field="emergency-taken">{{ $emergencyTaken }}</td>
                <td data-field="emergency-balance">{{ $emergencyBalance }}</td>
            </tr>
        </tbody>
    </table>

    <div class="others-section">
        <div>Others:</div>
        <div class="others-line"></div>
    </div>

    <div class="form-group">
        <label>Date of Leave</label>
        <span class="input-line">{{ $leave->date_from ? \Carbon\Carbon::parse($leave->date_from)->format('m/d/Y') : '' }}</span>
    </div>

    <div class="form-group">
        <label>Reasons for leave</label>
        <span class="input-line" data-field="reason">{{ $leave->reason_to_leave ?? '' }}</span>
    </div>

    <div class="checkbox-group" style="margin-top: 20px;">
        @php
            $paymentStatus = $leave->approved_by_signature
                ? $leave->payment_status
                : $leave->getLeavePaymentStatus();
        @endphp
        <span class="checkbox-box" data-payment="With Pay" style="{{ $paymentStatus === 'With Pay' ? 'background-image: url(\'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" fill="%23000"/></svg>\'); background-position: center; background-repeat: no-repeat;' : '' }}"></span> With Pay
        <span class="checkbox-box" data-payment="Without Pay" style="margin-left: 20px; {{ $paymentStatus === 'Without Pay' ? 'background-image: url(\'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z" fill="%23000"/></svg>\'); background-position: center; background-repeat: no-repeat;' : '' }}"></span> Without Pay
    </div>

    <div class="recommendation-section">
        <div>Recommendation from Immediate Supervisor: Recommended:</div>
        <div class="radio-group">
            <span class="radio-circle"></span> Approved for __________ day/s
        </div>
        <div class="radio-group">
            <span class="radio-circle"></span> DISAPPROVED for __________
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-block">
            <div class="signature-container">
                <div class="signature-line" data-signature="employee">
                    @if($leave->employee->signature)
                        <img src="{{ Storage::url($leave->employee->signature) }}"
                             alt="Employee Signature"
                             class="signature-image">
                    @endif
                </div>
            </div>
            <div class="signature-label">Employee Signature</div>
        </div>
        <div class="signature-block">
            <div class="signature-container">
                <div class="signature-line" data-signature="approved">
                    @if($leave->approved_by_signature)
                        <img src="{{ Storage::url($leave->approved_by_signature) }}"
                             alt="Approver Signature"
                             class="signature-image">
                    @endif
                    <br>
                    <span data-name="approved">{{ $leave->approvedByUser->first_name ?? ' ' }} {{ $leave->approvedByUser->last_name ?? ' ' }}</span>
                </div>
            </div>
            <div class="signature-label">Approved by</div>
        </div>
        <div class="signature-block">
            <div class="signature-container">
                <div class="signature-line" data-signature="validated">
                    @if($leave->validated_by_signature)
                        <img src="{{ Storage::url($leave->validated_by_signature) }}"
                             alt="Validator Signature"
                             class="signature-image">
                    @endif
                    <br>
                    <span data-name="validated">
                        @if($leave->validated_by_signature)
                            @php
                                $hrComben = App\Models\User::role('HR ComBen')->first();
                            @endphp
                            {{ $hrComben->first_name ?? '' }} {{ $hrComben->last_name ?? '' }}
                        @endif
                    </span>
                </div>
            </div>
            <div class="signature-label">Validated By</div>
        </div>
    </div>

    <!-- Update the Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Leave Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('leaves.update-status', $leave->id) }}" method="POST" id="statusUpdateForm">
                        @csrf
                        @method('PUT')

                        <!-- Status Selection -->
                        <div class="form-group">
                            <label for="status">Status Decision</label>
                            <select class="form-control @error('status') is-invalid @enderror"
                                    name="status"
                                    id="status"
                                    required>
                                <option value="">Select Status</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approve</option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Reject</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Approver's Digital Signature Pad -->
                        <div class="form-group mt-3">
                            <label>Approver's Signature</label>
                            <div class="border rounded p-3">
                                <canvas id="signaturePad" class="signature-pad" width="400" height="200"></canvas>
                                <input type="hidden" name="approved_by_signature" id="signatureInput">
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-secondary" id="clearSignature">
                                    Clear Signature
                                </button>
                            </div>
                            @error('approved_by_signature')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary" id="submitStatus">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Validation Signature Modal -->
    <div class="modal fade" id="validateSignatureModal" tabindex="-1" role="dialog" aria-labelledby="validateSignatureModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validateSignatureModalLabel">Add Validation Signature</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('leaves.update-validation', $leave->id) }}" method="POST" id="validationForm">
                        @csrf
                        @method('PUT')

                        <!-- Validator's Digital Signature Pad -->
                        <div class="form-group mt-3">
                            <label>Validator's Signature</label>
                            <div class="border rounded p-3">
                                <canvas id="validationSignaturePad" class="signature-pad" width="400" height="200"></canvas>
                                <input type="hidden" name="validated_by_signature" id="validationSignatureInput">
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-secondary" id="clearValidationSignature">
                                    Clear Signature
                                </button>
                            </div>
                            @error('validated_by_signature')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-success">
                                Add Validation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize signature pads
        const initSignaturePad = (canvasId, clearButtonId) => {
            const canvas = document.getElementById(canvasId);
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Clear signature button
            document.getElementById(clearButtonId).addEventListener('click', function() {
                signaturePad.clear();
            });

            return signaturePad;
        };

        // Initialize both signature pads
        const approverSignaturePad = initSignaturePad('signaturePad', 'clearSignature');
        const validationSignaturePad = initSignaturePad('validationSignaturePad', 'clearValidationSignature');

        // Status Update Form submission
        document.getElementById('statusUpdateForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (approverSignaturePad.isEmpty()) {
                alert('Please provide approver\'s signature');
                return;
            }

            // Get signature data
            document.getElementById('signatureInput').value = approverSignaturePad.toDataURL();

            // Submit form
            this.submit();
        });

        // Validation Form submission
        document.getElementById('validationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (validationSignaturePad.isEmpty()) {
                alert('Please provide validator\'s signature');
                return;
            }

            // Get signature data
            document.getElementById('validationSignatureInput').value = validationSignaturePad.toDataURL();

            // Submit form
            this.submit();
        });
    });

    function printLeaveForm() {
        const printWindow = window.open('', '_blank');

        const printStyles = `
            <style>
                @page {
                    size: 8.5in 11in;
                    margin: 0.5in;
                }

                body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial, sans-serif;
                }

                .page-container {
                    width: 7.5in;
                    margin: 0 auto;
                }

                .leave-form {
                    padding: 13px;
                    transform: scale(0.83);
                    transform-origin: top center;
                }

                /* Header - further reduced sizes */
                .header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 13px;
                }

                .logo {
                    width: 148px;
                    margin-right: 13px;
                }

                .logo img {
                    width: 100%;
                    height: auto;
                }

                .title {
                    flex-grow: 1;
                    text-align: right;
                    font-size: 18px;
                    font-weight: bold;
                }

                /* Form rows - further reduced margins */
                .form-row {
                    display: flex;
                    margin-bottom: 8px;
                    gap: 38px;
                }

                .form-group {
                    flex: 1;
                }

                .form-group label {
                    display: inline-block;
                    font-weight: bold;
                    font-size: 11px;
                }

                .input-line {
                    display: inline-block;
                    border-bottom: 1px solid #000;
                    min-width: 38px;
                    padding: 0 3px;
                    margin-left: 3px;
                    font-size: 11px;
                }

                /* Table - further reduced sizes */
                table {
                    width: 80%;
                    border-collapse: collapse;
                    margin: 8px auto;
                    font-size: 11px;
                }

                th, td {
                    border: 1px solid #000;
                    padding: 4px;
                    text-align: center;
                }

                /* Checkbox group - further reduced margins */
                .checkbox-group {
                    margin-left: 13px;
                    font-size: 11px;
                }

                .checkbox-item {
                    margin: 2px 0;
                }

                .checkbox-box {
                    display: inline-block;
                    width: 10px;
                    height: 10px;
                    border: 1px solid #000;
                    margin-right: 6px;
                }

                /* Others section */
                .others-section {
                    margin: 13px 0;
                    font-size: 11px;
                }

                .others-line {
                    border-bottom: 1px solid #000;
                    width: 100%;
                    margin: 2px 0;
                }

                /* Recommendation section */
                .recommendation-section {
                    margin: 13px 0;
                    font-size: 11px;
                }

                .radio-group {
                    margin: 4px 0;
                }

                .radio-circle {
                    display: inline-block;
                    width: 10px;
                    height: 10px;
                    border: 1px solid #000;
                    border-radius: 50%;
                    margin-right: 6px;
                }

                /* Signature section - further reduced sizes */
                .signature-section {
                    margin-top: 18px;
                    display: flex;
                    justify-content: space-between;
                    padding: 0 28px;
                }

                .signature-block {
                    width: 198px;
                    text-align: center;
                }

                .signature-line {
                    width: 100%;
                    border-bottom: 1px solid #000;
                    position: relative;
                    min-height: 43px;
                    display: flex;
                    align-items: flex-end;
                    justify-content: center;
                }

                .signature-image {
                    max-height: 43px;
                    max-width: 148px;
                    position: absolute;
                    bottom: 0;
                    mix-blend-mode: darken;
                    opacity: 0.8;
                }

                .signature-label {
                    margin-top: 2px;
                    font-weight: normal;
                    font-size: 10px;
                }

                /* Hide UI elements in print */
                @media print {
                    .btn-responsive,
                    .modal,
                    #updateStatusModal,
                    #validateSignatureModal {
                        display: none !important;
                    }
                }

                /* Checkbox styles for leave types */
                .checkbox-group {
                    margin-left: 13px;
                    font-size: 11px;
                }

                .checkbox-item {
                    margin: 2px 0;
                }

                .checkbox-box {
                    display: inline-block;
                    width: 10px;
                    height: 10px;
                    border: 1px solid #000;
                    margin-right: 6px;
                    position: relative;
                    vertical-align: middle;
                }

                /* Style for checked checkboxes */
                .checkbox-box[data-leave-type="Vacation Leave"][style*="background-image"],
                .checkbox-box[data-leave-type="Sick Leave"][style*="background-image"],
                .checkbox-box[data-leave-type="Emergency Leave"][style*="background-image"],
                .checkbox-box[data-payment="With Pay"][style*="background-image"],
                .checkbox-box[data-payment="Without Pay"][style*="background-image"] {
                    background-size: 8px;
                    background-position: center;
                    background-repeat: no-repeat;
                }

                /* Payment status checkboxes specific styles */
                .checkbox-group[style*="margin-top"] {
                    margin-top: 15px !important;  /* Reduced from 20px */
                    display: flex;
                    align-items: center;
                }

                .checkbox-group[style*="margin-top"] .checkbox-box {
                    margin-right: 4px;
                }

                .checkbox-group[style*="margin-top"] .checkbox-box[data-payment="Without Pay"] {
                    margin-left: 15px;  /* Reduced from 20px */
                }

                /* Ensure text alignment */
                .checkbox-item,
                .checkbox-group[style*="margin-top"] {
                    display: flex;
                    align-items: center;
                    line-height: 1.2;
                }

                /* Hide UI elements in print */
                @media print {
                    .checkbox-box[style*="background-image"] {
                        -webkit-print-color-adjust: exact !important;
                        color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                }
            </style>
        `;

        // Get the content of the filled leave form
        const filledContent = document.querySelector('.leave-form').cloneNode(true);

        // Remove unnecessary elements
        filledContent.querySelectorAll('.btn-responsive, .modal').forEach(element => {
            element.remove();
        });

        // Write the content to the new window
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Leave Application Form</title>
                ${printStyles}
            </head>
            <body>
                <div class="page-container">
                    ${filledContent.outerHTML}
                </div>
            </body>
            </html>
        `);

        // Close the document writing
        printWindow.document.close();

        // Wait for all images to load before printing
        const images = printWindow.document.getElementsByTagName('img');
        let loadedImages = 0;

        function tryPrint() {
            loadedImages++;
            if (loadedImages === images.length) {
                // Small delay to ensure proper rendering
                setTimeout(() => {
                    printWindow.print();
                    // Close window only after print dialog is closed
                    printWindow.onafterprint = function() {
                        printWindow.close();
                    };
                }, 500);
            }
        }

        // If there are no images, print immediately
        if (images.length === 0) {
            setTimeout(() => {
                printWindow.print();
                printWindow.onafterprint = function() {
                    printWindow.close();
                };
            }, 500);
        } else {
            // Add load event listener to each image
            for (let img of images) {
                if (img.complete) {
                    tryPrint();
                } else {
                    img.addEventListener('load', tryPrint);
                    // Handle error cases
                    img.addEventListener('error', tryPrint);
                }
            }
        }
    }
</script>
@endpush
