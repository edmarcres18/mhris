@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">My Night Premium History</h5>
                        <div class="card-tools">
                            <a href="{{ route('overtime.apply') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle"></i> Apply New Overtime
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div id="loading-spinner" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading overtime records...</p>
                    </div>

                    <div id="overtime-data" style="display: none;">
                        @if($overtimeRecords->isEmpty())
                            <div class="alert alert-info">
                                You haven't submitted any overtime requests yet. Click the "Apply for New Overtime" button to create your first request.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table id="overtime-history-table" class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Hours</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($overtimeRecords as $record)
                                            <tr>
                                                <td>{{ date('M d, Y', strtotime($record->date)) }}</td>
                                                <td>{{ date('h:i A', strtotime($record->time_in)) }}</td>
                                                <td>{{ date('h:i A', strtotime($record->time_out)) }}</td>
                                                <td>{{ number_format($record->overtime_hours, 2) }}</td>
                                                <td>{{ number_format($record->overtime_rate, 2) }}x</td>
                                                <td class="text-success">₱{{ number_format($record->overtime_pay, 2) }}</td>
                                                <td>
                                                    @if($record->approval_status == 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif(str_contains($record->approval_status, 'approved'))
                                                        <span class="badge bg-success">
                                                            @if($record->approval_status == 'approvedBySupervisor')
                                                                Approved by Supervisor
                                                            @elseif($record->approval_status == 'approvedByFinance')
                                                                Approved by Finance
                                                            @elseif($record->approval_status == 'approvedByVPFinance')
                                                                Fully Approved
                                                            @endif
                                                        </span>
                                                    @elseif(str_contains($record->approval_status, 'rejected'))
                                                        <span class="badge bg-danger">
                                                            @if($record->approval_status == 'rejectedBySupervisor')
                                                                Rejected by Supervisor
                                                            @elseif($record->approval_status == 'rejectedByFinance')
                                                                Rejected by Finance
                                                            @elseif($record->approval_status == 'rejectedByVPFinance')
                                                                Rejected by VP Finance
                                                            @endif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $record->created_at->format('M d, Y') }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary view-details" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#overtimeModal{{ $record->id }}"
                                                            data-id="{{ $record->id }}">
                                                        View
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal for overtime details -->
                                            <div class="modal fade" id="overtimeModal{{ $record->id }}" tabindex="-1" 
                                                aria-labelledby="overtimeModalLabel{{ $record->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="overtimeModalLabel{{ $record->id }}">
                                                                Overtime Details
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Status Banner -->
                                                            <div class="alert 
                                                                @if($record->approval_status == 'pending') alert-warning 
                                                                @elseif(str_contains($record->approval_status, 'approved')) alert-success 
                                                                @elseif(str_contains($record->approval_status, 'rejected')) alert-danger 
                                                                @endif">
                                                                @if($record->approval_status == 'pending')
                                                                    <strong>Status:</strong> Pending Approval
                                                                @elseif(str_contains($record->approval_status, 'approved'))
                                                                    <strong>Status:</strong> 
                                                                    @if($record->approval_status == 'approvedBySupervisor')
                                                                        Approved by Supervisor
                                                                    @elseif($record->approval_status == 'approvedByFinance')
                                                                        Approved by Finance
                                                                    @elseif($record->approval_status == 'approvedByVPFinance')
                                                                        Fully Approved
                                                                    @endif
                                                                @elseif(str_contains($record->approval_status, 'rejected'))
                                                                    <strong>Status:</strong> 
                                                                    @if($record->approval_status == 'rejectedBySupervisor')
                                                                        Rejected by Supervisor
                                                                    @elseif($record->approval_status == 'rejectedByFinance')
                                                                        Rejected by Finance
                                                                    @elseif($record->approval_status == 'rejectedByVPFinance')
                                                                        Rejected by VP Finance
                                                                    @endif
                                                                @endif
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <strong>Date:</strong> {{ date('M d, Y', strtotime($record->date)) }}
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <strong>Time:</strong> {{ date('h:i A', strtotime($record->time_in)) }} - {{ date('h:i A', strtotime($record->time_out)) }}
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-4">
                                                                    <strong>Hours:</strong> {{ number_format($record->overtime_hours, 2) }}
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <strong>Rate:</strong> {{ number_format($record->overtime_rate, 2) }}x
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <strong>Amount:</strong> ₱{{ number_format($record->overtime_pay, 2) }}
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <strong>Reason Provided:</strong>
                                                                <p class="mt-2">{{ $record->reason ?? 'No reason provided' }}</p>
                                                            </div>

                                                            @if($record->rejection_reason)
                                                                <div class="alert alert-danger">
                                                                    <strong>Rejection Reason:</strong>
                                                                    <p class="mb-0 mt-1">{{ $record->rejection_reason }}</p>
                                                                </div>
                                                            @endif

                                                            <div>
                                                                <strong>Application Timeline:</strong>
                                                                <ul class="list-group mt-2">
                                                                    <li class="list-group-item">
                                                                        <strong>Submitted:</strong> {{ $record->created_at->format('M d, Y h:i A') }}
                                                                    </li>
                                                                    @if($record->is_read_by_supervisor)
                                                                        <li class="list-group-item">
                                                                            <strong>Reviewed by Supervisor:</strong> 
                                                                            {{ \Carbon\Carbon::parse($record->is_read_at_supervisor)->format('M d, Y h:i A') }}
                                                                        </li>
                                                                    @endif
                                                                    @if($record->is_read_by_finance)
                                                                        <li class="list-group-item">
                                                                            <strong>Reviewed by Finance:</strong> 
                                                                            {{ \Carbon\Carbon::parse($record->is_read_at_finance)->format('M d, Y h:i A') }}
                                                                        </li>
                                                                    @endif
                                                                    @if($record->is_read_by_vpfinance)
                                                                        <li class="list-group-item">
                                                                            <strong>Reviewed by VP Finance:</strong> 
                                                                            {{ \Carbon\Carbon::parse($record->is_read_at_vpfinance)->format('M d, Y h:i A') }}
                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            @if($record->approval_status == 'rejectedBySupervisor' || $record->approval_status == 'rejectedByFinance' || $record->approval_status == 'rejectedByVPFinance')
                                                            <a href="{{ route('overtime.apply') }}" class="btn btn-primary">
                                                                Submit New Request
                                                            </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        // Show loading spinner when page loads
        const loadingSpinner = document.getElementById('loading-spinner');
        const overtimeData = document.getElementById('overtime-data');
        
        // Initialize DataTable
        let table = $('#overtime-history-table').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search records...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)"
            },
            columnDefs: [
                { responsivePriority: 1, targets: [0, 5, 6, 8] },
                { responsivePriority: 2, targets: [1, 2] },
                { responsivePriority: 3, targets: [3, 4, 7] }
            ],
            order: [[0, 'desc']]
        });
        
        // Simulate loading time (for demonstration purposes)
        setTimeout(function() {
            loadingSpinner.style.display = 'none';
            overtimeData.style.display = 'block';
            
            // Adjust the DataTable after display is set to block
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        }, 500);
        
        // Add click handler for detail view buttons
        const viewButtons = document.querySelectorAll('.view-details');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const recordId = this.getAttribute('data-id');
                console.log('Viewing details for record:', recordId);
            });
        });
    });
</script>
@endsection 