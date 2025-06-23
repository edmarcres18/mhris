<!-- Modal -->
<div class="modal fade" id="loanModal" tabindex="-1" role="dialog" aria-labelledby="loanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loanModalLabel">Apply for PAGIBIG Loan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('loan_pagibig.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="employee_id">Employee<span class="text-danger">*</span></label>
                        <select name="employee_id" id="pagibig_employee_id" class="form-control select2-employee" required data-placeholder="Search for an employee...">
                            <option value="" selected disabled>Select Employee</option>
                            @if(!$employees->isEmpty())
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->last_name }}, {{ $employee->first_name }}{{ !empty($employee->middle_name) ? ' '.$employee->middle_name : '' }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>No eligible employees found</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="loan_type">Loan Type<span class="text-danger">*</span></label>
                        <select name="loan_type" id="loan_type" class="form-control select2-employee" required>
                            @foreach ($loanTypes as $loanType)
                                <option value="{{ $loanType->value }}">{{ ucfirst($loanType->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="loan_amount">Loan Amount<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₱</span>
                            </div>
                            <input type="number" name="loan_amount" id="loan_amount" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label for="interest_rate">Interest Rate (%) <small>(Optional)</small></label>
                        <input type="number" name="interest_rate" id="interest_rate" class="form-control" step="0.01">
                    </div> --}}
                    <div class="form-group">
                        <label for="loan_term_months">Loan Term (months)<span class="text-danger">*</span></label>
                        <input type="number" name="loan_term_months" id="loan_term_months" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Loan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    /* Simple Select2 styling for modal */
    .select2-container {
        z-index: 1060;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize select2 for the loan modal
    $('#loanModal').on('shown.bs.modal', function () {
        $('.select2-employee').select2({
            theme: 'bootstrap4',
            width: '100%',
            dropdownParent: $('#loanModal .modal-content')
        });
    });
    
    const loanTypeSelect = document.getElementById('loan_type');
    const interestRateInput = document.getElementById('interest_rate');
    const loanAmountInput = document.getElementById('loan_amount');

    loanTypeSelect.addEventListener('change', function() {
        if (this.value === 'HOUSING') {
            interestRateInput.value = '';
            interestRateInput.disabled = true;
        } else {
            interestRateInput.disabled = false;
        }
    });

    loanAmountInput.addEventListener('input', function() {
        if (loanTypeSelect.value === 'HOUSING') {
            const amount = parseFloat(this.value);
            let rate;
            if (amount <= 500000) {
                rate = 5.75;
            } else if (amount <= 1000000) {
                rate = 6.375;
            } else if (amount <= 1500000) {
                rate = 7.0;
            } else {
                rate = 8.0;
            }
            interestRateInput.value = rate;
        }
    });
});
</script>
@endpush
