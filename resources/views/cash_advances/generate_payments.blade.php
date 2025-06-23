<!-- Modal for generating payment for specific employee -->
<div class="modal fade" id="generatePaymentModal" tabindex="-1" role="dialog" aria-labelledby="generatePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generatePaymentModalLabel">Generate Payment for Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('cash_advances.generate_payment_for_employee') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="employee_id">Select Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control" required>
                            <option value="" selected disabled>Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->last_name }}, {{ $employee->first_name }} {{ $employee->middle_name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Generate Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
