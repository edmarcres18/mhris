<!-- Modal -->
<div class="modal fade" id="loanModal" tabindex="-1" role="dialog" aria-labelledby="loanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loanModalLabel">Apply for SSS Loan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('loan_sss.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="employee_id">Employee<span class="text-danger">*</span></label>
                        <select name="employee_id" id="loan_employee_id" class="form-control select2-employee" required data-placeholder="Search for an employee...">
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
                        <label for="loan_amount">Loan Amount (PHP)<span class="text-danger">*</span></label>
                        <input type="number" name="loan_amount" id="loan_amount" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="repayment_term">Repayment Term (Months)<span class="text-danger">*</span></label>
                        <input type="number" name="repayment_term" id="repayment_term" class="form-control" min="1" max="24" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Apply Loan</button>
                    </div>
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
    });
</script>
@endpush
