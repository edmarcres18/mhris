@extends('layouts.medical-products')

@section('title', 'Quotation Requests List')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">

<style>
.quotations-container {
    padding: 20px;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Enhanced Table Styling */
.table-header {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-tools {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.table-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #344767;
    margin: 0;
}

/* Enhanced DataTables Styling */
.dataTables_wrapper {
    padding: 1rem;
}

.dataTables_length {
    margin-bottom: 1rem;
}

.dataTables_length label {
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #344767;
}

.dataTables_length select {
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.5rem 2rem 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #344767;
    cursor: pointer;
    transition: all 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
}

.dataTables_length select:hover {
    border-color: #adb5bd;
}

.dataTables_length select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    outline: 0;
}

.dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_filter label {
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #344767;
}

.dataTables_filter input {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    width: 200px;
}

.dataTables_filter input:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    outline: 0;
}

.dataTables_info {
    font-size: 0.875rem;
    color: #6c757d;
    padding-top: 1rem !important;
}

.dataTables_paginate {
    padding-top: 1rem !important;
}

.paginate_button {
    padding: 0.5rem 0.75rem !important;
    margin: 0 2px !important;
    border: 1px solid #dee2e6 !important;
    background: #fff !important;
    border-radius: 6px !important;
    color: #344767 !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.paginate_button.current {
    background: #0d6efd !important;
    border-color: #0d6efd !important;
    color: #fff !important;
    font-weight: 600 !important;
}

.paginate_button:hover:not(.current) {
    background: #e9ecef !important;
    border-color: #dee2e6 !important;
    color: #000 !important;
}

.paginate_button.disabled {
    opacity: 0.5 !important;
    cursor: not-allowed !important;
}

/* Status Badge Enhancements */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background-color: #fff8dd !important;
    color: #b95000 !important;
}

.status-processed {
    background-color: #e8f4ff !important;
    color: #0058b9 !important;
}

.status-completed {
    background-color: #e6fff3 !important;
    color: #00894a !important;
}

.status-rejected {
    background-color: #ffe8e8 !important;
    color: #b90000 !important;
}

/* Filter Section Enhancement */
.filter-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.filter-section .row {
    margin: 0 -0.5rem;
}

.filter-section .col-md-3,
.filter-section .col-md-6 {
    padding: 0 0.5rem;
}

.filter-title {
    font-size: 1rem;
    font-weight: 600;
    color: #344767;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.date-range-filter {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.date-range-filter span {
    color: #6c757d;
    font-weight: 500;
}

.filter-active {
    background-color: #e8f4ff;
    border-color: #86b7fe;
}

.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    background: #e8f4ff;
    color: #0d6efd;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.clear-filter {
    color: #dc3545;
    cursor: pointer;
    padding: 0.25rem;
    margin-left: 0.25rem;
    transition: all 0.2s ease;
}

.clear-filter:hover {
    color: #bb2d3b;
}

/* Responsive Enhancement for Filters */
@media (max-width: 768px) {
    .filter-section {
        padding: 1rem;
    }

    .filter-section .col-md-3,
    .filter-section .col-md-6 {
        margin-bottom: 1rem;
    }

    .date-range-filter {
        flex-direction: column;
        gap: 0.5rem;
    }

    .date-range-filter span {
        display: none;
    }

    .filter-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
    }
}

/* Table Enhancement */
.table {
    margin-bottom: 0;
}

.table thead th {
    background: #f8f9fa;
    font-weight: 600;
    color: #344767;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
    color: #344767;
    border-bottom: 1px solid #dee2e6;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Button Enhancements */
.btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-outline-secondary {
    border-color: #dee2e6;
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    border-color: #adb5bd;
}

/* Responsive Enhancement */
@media (max-width: 768px) {
    .table-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .table-tools {
        width: 100%;
        justify-content: space-between;
    }
    
    .dataTables_filter input {
        width: 100%;
    }
    
    .filter-section .row {
        margin: 0;
    }
}
</style>
@endpush

@section('content')
<div class="quotations-container">
    <div class="card mb-4">
        <div class="table-header">
            <h2 class="table-title">
                <i class="fas fa-file-invoice me-2"></i>Quotation Requests
            </h2>
            <div class="table-tools">
                <button type="button" class="btn btn-outline-secondary" id="toggleFilters">
                    <i class="fas fa-filter me-1"></i> Filters
                </button>
                <button type="button" class="btn btn-outline-secondary" id="refreshTable">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Advanced Filter Section -->
            <div class="filter-section mb-4" id="filterSection" style="display: none;">
                <div class="filter-title">
                    <i class="fas fa-sliders-h me-2"></i>Advanced Filters
                    <div class="filter-badge" id="activeFiltersCount" style="display: none">
                        Active Filters: <span>0</span>
                        <i class="fas fa-times clear-filter" id="clearAllFilters" title="Clear all filters"></i>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="processed">Processed</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Hospital</label>
                            <input type="text" class="form-control" id="hospitalFilter" placeholder="Filter by hospital">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Date Range</label>
                            <div class="date-range-filter">
                                <input type="date" class="form-control" id="dateFrom" placeholder="From">
                                <span>to</span>
                                <input type="date" class="form-control" id="dateTo" placeholder="To">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="quotationsTable">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Customer Name</th>
                            <th>Hospital</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotations as $quotation)
                        <tr data-quotation-id="{{ $quotation->id }}">
                            <td>{{ $quotation->product_name }}</td>
                            <td>{{ $quotation->name }}</td>
                            <td>{{ $quotation->hospital_name }}</td>
                            <td>{{ $quotation->email }}</td>
                            <td>{{ $quotation->phone }}</td>
                            <td>
                                <span class="status-badge status-{{ $quotation->status }} badge bg-{{ $quotation->status === 'pending' ? 'warning' : ($quotation->status === 'processed' ? 'info' : ($quotation->status === 'completed' ? 'success' : 'danger')) }}">
                                    {{ ucfirst($quotation->status) }}
                                </span>
                            </td>
                            <td data-order="{{ $quotation->created_at->format('Y-m-d') }}">
                                {{ $quotation->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#quotationModal{{ $quotation->id }}">
                                    <i class="fas fa-edit me-1"></i> Update
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modals -->
@foreach($quotations as $quotation)
<div class="modal fade" id="quotationModal{{ $quotation->id }}" tabindex="-1" aria-labelledby="quotationModalLabel{{ $quotation->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quotationModalLabel{{ $quotation->id }}">Update Quotation Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('quotations.update', $quotation->id) }}" method="POST" class="quotation-form" data-quotation-id="{{ $quotation->id }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Status</label>
                        <div class="current-status">
                            <span class="status-badge status-{{ $quotation->status }}">
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </div>
                    </div>

                    @if($quotation->status === 'completed')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-1"></i>
                        This quotation has been completed and cannot be modified further.
                    </div>
                    @elseif($quotation->status === 'rejected')
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-1"></i>
                        This quotation has been rejected and cannot be modified further.
                    </div>
                    @else
                    <div class="mb-3">
                        <label for="status{{ $quotation->id }}" class="form-label fw-bold">Update Status</label>
                        <select class="form-select" id="status{{ $quotation->id }}" name="status" required>
                            <option value="">Select new status</option>
                            @if($quotation->status === 'pending')
                                <option value="pending" selected>Pending</option>
                                <option value="processed">Processed</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            @elseif($quotation->status === 'processed')
                                <option value="processed" selected>Processed</option>
                                <option value="completed">Completed</option>
                            @endif
                        </select>
                        <div class="invalid-feedback">Please select a status.</div>
                    </div>

                    <div class="mb-3">
                        <label for="notes{{ $quotation->id }}" class="form-label fw-bold">Notes</label>
                        <textarea class="form-control" id="notes{{ $quotation->id }}" name="notes" rows="3" placeholder="Add any relevant notes about this status update">{{ $quotation->notes }}</textarea>
                    </div>
                    @endif

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Last updated: {{ $quotation->updated_at->format('M d, Y H:i:s') }}
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @if(!in_array($quotation->status, ['completed', 'rejected']))
                    <button type="submit" class="btn btn-primary update-status-btn">
                        <i class="fas fa-save me-1"></i> Save changes
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable with enhanced options
    const table = $('#quotationsTable').DataTable({
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            ['10 rows', '25 rows', '50 rows', '100 rows', 'Show All']
        ],
        order: [[6, 'desc']],
        language: {
            search: "<i class='fas fa-search me-1'></i>Search:",
            lengthMenu: "_MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries available",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                last: '<i class="fas fa-angle-double-right"></i>'
            },
            emptyTable: "No quotation requests available",
            zeroRecords: "No matching records found"
        },
        drawCallback: function(settings) {
            // Update status badges after table redraw
            $('.status-badge').each(function() {
                const status = $(this).text().toLowerCase().trim();
                $(this).addClass('status-' + status);
            });
        }
    });

    // Refresh button functionality
    $('#refreshTable').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Refreshing...');
        
        // Reload the page after a short delay
        setTimeout(() => {
            window.location.reload();
        }, 500);
    });

    // Enhanced filter functionality
    const filterSection = $('#filterSection');
    const toggleFiltersBtn = $('#toggleFilters');
    const activeFiltersCount = $('#activeFiltersCount');
    const clearAllFiltersBtn = $('#clearAllFilters');
    let activeFilters = 0;

    function updateActiveFiltersCount() {
        activeFilters = 0;
        if ($('#statusFilter').val()) activeFilters++;
        if ($('#hospitalFilter').val()) activeFilters++;
        if ($('#dateFrom').val() || $('#dateTo').val()) activeFilters++;

        if (activeFilters > 0) {
            activeFiltersCount.show().find('span').text(activeFilters);
            filterSection.addClass('filter-active');
            toggleFiltersBtn.addClass('btn-primary').removeClass('btn-outline-secondary');
        } else {
            activeFiltersCount.hide();
            filterSection.removeClass('filter-active');
            toggleFiltersBtn.removeClass('btn-primary').addClass('btn-outline-secondary');
        }
    }

    function clearAllFilters() {
        $('#statusFilter').val('');
        $('#hospitalFilter').val('');
        $('#dateFrom').val('');
        $('#dateTo').val('');
        table.search('').columns().search('').draw();
        updateActiveFiltersCount();
    }

    // Toggle filters button
    toggleFiltersBtn.on('click', function() {
        const icon = $(this).find('i');
        filterSection.slideToggle({
            duration: 200,
            complete: function() {
                if (filterSection.is(':visible')) {
                    icon.removeClass('fa-filter').addClass('fa-times');
                    toggleFiltersBtn.html('<i class="fas fa-times me-1"></i> Hide Filters');
                } else {
                    icon.removeClass('fa-times').addClass('fa-filter');
                    toggleFiltersBtn.html('<i class="fas fa-filter me-1"></i> Filters');
                }
            }
        });
    });

    // Clear all filters
    clearAllFiltersBtn.on('click', function(e) {
        e.stopPropagation();
        clearAllFilters();
    });

    // Status filter with enhanced UI feedback
    $('#statusFilter').on('change', function() {
        const value = this.value;
        table.column(5).search(value).draw();
        updateActiveFiltersCount();
    });

    // Enhanced hospital filter with debounce
    let hospitalFilterTimeout;
    $('#hospitalFilter').on('input', function() {
        clearTimeout(hospitalFilterTimeout);
        const input = $(this);
        
        hospitalFilterTimeout = setTimeout(() => {
            table.column(2).search(input.val()).draw();
            updateActiveFiltersCount();
        }, 300);
    });

    // Enhanced date range filter
    const dateFrom = $('#dateFrom');
    const dateTo = $('#dateTo');

    function validateDateRange() {
        if (dateFrom.val() && dateTo.val()) {
            const min = new Date(dateFrom.val());
            const max = new Date(dateTo.val());
            
            if (min > max) {
                alert('Start date cannot be later than end date');
                dateTo.val('');
                return false;
            }
        }
        return true;
    }

    dateFrom.add(dateTo).on('change', function() {
        if (validateDateRange()) {
            table.draw();
            updateActiveFiltersCount();
        }
    });

    // Initialize filters state
    updateActiveFiltersCount();

    // Handle form submissions
    const quotationForms = document.querySelectorAll('.quotation-form');
    
    quotationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const quotationId = this.dataset.quotationId;
            const submitButton = this.querySelector('.update-status-btn');
            const modal = this.closest('.modal');
            const modalInstance = bootstrap.Modal.getInstance(modal);

            // Disable submit button and show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';

            fetch(`/quotations/${quotationId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            // Try to parse as JSON first
                            return Promise.reject(JSON.parse(text));
                        } catch (e) {
                            // If not JSON, return a generic error with the text
                            return Promise.reject({
                                success: false,
                                message: 'Server Error: ' + text.substring(0, 100) // Only show first 100 chars
                            });
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update the status badge in the table
                    const row = document.querySelector(`tr[data-quotation-id="${quotationId}"]`);
                    if (row) {
                        const statusBadge = row.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.className = `status-badge status-${data.status}`;
                            statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                        }
                    }

                    // Show success message using Bootstrap Toast
                    if (window.bootstrap && window.bootstrap.Toast) {
                        const toast = new bootstrap.Toast(document.getElementById('successToast'));
                        document.getElementById('toastMessage').textContent = data.message;
                        toast.show();
                    } else {
                        // Fallback to alert if Toast is not available
                        alert(data.message || 'Status updated successfully');
                    }

                    // Close modal
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    
                    // Optionally reload the page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = error.message || 'An error occurred while updating the status';
                if (typeof error === 'object' && error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                }
                alert(errorMessage);
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-save me-1"></i> Save changes';
            });
        });
    });
});
</script>
@endpush

<!-- Add Toast Component -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                Status updated successfully
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection