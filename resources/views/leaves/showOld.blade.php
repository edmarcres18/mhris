@extends('layouts.app')

<style>
    /* Card and Modal */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        border-radius: 15px 15px 0 0;
        padding: 20px;
    }

    .card-body {
        padding: 30px;
    }

    .card-footer {
        border-radius: 0 0 15px 15px;
        padding: 20px;
    }

    .modal-content {
        border-radius: 15px;
    }

    .modal-header {
        border-radius: 15px 15px 0 0;
        padding: 20px;
    }

    .modal-body {
        padding: 30px;
    }

    .modal-footer {
        border-radius: 0 0 15px 15px;
        padding: 20px;
    }

    /* Typography */
    .card-title {
        font-weight: 600;
    }

    .font-weight-bold {
        font-weight: 600;
    }

    /* Form elements */
    .form-control {
        border-radius: 10px;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Status icons */
    .status-icon {
        font-size: 1.5rem;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }
</style>

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center fade-in">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Leave Details</h3>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ $message }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $message }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="font-weight-bold text-muted">Employee:</label>
                                <p class="lead">{{ $leave->employee->company_id }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="font-weight-bold text-muted">From:</label>
                                <p class="lead">{{ \Carbon\Carbon::parse($leave->date_from)->format('F j, Y \a\t h:i A') }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="font-weight-bold text-muted">To:</label>
                                <p class="lead">{{ \Carbon\Carbon::parse($leave->date_to)->format('F j, Y \a\t h:i A') }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="font-weight-bold text-muted">Reason:</label>
                                <p class="lead">{{ $leave->reason_to_leave }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="font-weight-bold text-muted">Type:</label>
                                <p class="lead">{{ $leave->type->name }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="font-weight-bold text-muted">Status:</label>
                                <p class="lead">
                                    @if($leave->status == 'approved')
                                        <i class="fas fa-check-circle text-success status-icon"></i>
                                    @elseif($leave->status == 'rejected')
                                        <i class="fas fa-times-circle text-danger status-icon"></i>
                                    @else
                                        <i class="fas fa-clock text-warning status-icon"></i>
                                    @endif
                                    &nbsp;{{ ucfirst($leave->status) }}
                                </p>
                            </div>
                            <div class="mb-4">
                                <label class="font-weight-bold text-muted">
                                    @if($leave->status === 'approved')
                                        Approved By:
                                    @elseif($leave->status === 'rejected')
                                        Rejected By:
                                    @else
                                        Pending Approval
                                    @endif
                                </label>
                                @if(($leave->status === 'approved' || $leave->status === 'rejected') && $approvedByUser)
                                    <p class="lead">{{ $approvedByUser->first_name }} {{ $approvedByUser->last_name }}</p>
                                @endif
                            </div>
                            <div class="mb-4">
                                <label class="font-weight-bold text-muted">Duration:</label>
                                @if($leave->diffhours)
                                    <p class="lead">
                                        @if($leave->diffhours['hours'] > 0)
                                            {{ $leave->diffhours['hours'] }} hours
                                        @endif
                                        @if($leave->diffhours['minutes'] > 0)
                                            @if($leave->diffhours['hours'] > 0)
                                                {{ $leave->diffhours['minutes'] }} minutes
                                            @else
                                                {{ $leave->diffhours['minutes'] }} minutes
                                            @endif
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div class="mb-4">
                                <label class="font-weight-bold text-muted">Payment Status:</label>
                                <p class="lead">
                                    {{$leave->payment_status}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>
                        @if($leave->status == 'pending')
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateStatusModal-{{ $leave->id }}">
                                <i class="fas fa-edit mr-2"></i>Update Status
                            </button>
                        @else
                            <button type="button" class="btn btn-primary" disabled>
                                <i class="fas fa-edit mr-2"></i>Update Status
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="updateStatusModal-{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel-{{ $leave->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateStatusModalLabel-{{ $leave->id }}">Update Status for {{ $leave->type->name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('leaves.updateStatus', $leave->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status" class="font-weight-bold">Status:</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $leave->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ $leave->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="approved" {{ $leave->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Fade out loader
        $('#loader').fadeOut(500);

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Add smooth scrolling
        $('a[href*="#"]').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate(
                {
                    scrollTop: $($(this).attr('href')).offset().top,
                },
                500,
                'linear'
            );
        });
    });
</script>
@endpush
