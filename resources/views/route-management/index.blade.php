@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
        margin-bottom: 1.5rem;
    }
    
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,.125);
        padding: 1.25rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    #routes-table {
        width: 100% !important;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    
    #routes-table thead th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 0.75rem;
    }
    
    #routes-table tbody td {
        padding: 0.75rem;
        vertical-align: middle;
    }
    
    .btn-group-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .form-control-sm {
        min-height: 32px;
    }
    
    .route-checkbox {
        width: 18px;
        height: 18px;
    }
    
    #select-all {
        width: 18px;
        height: 18px;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 0.25rem;
        transition: all 0.2s;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .card-header h2 {
            margin-bottom: 0;
            text-align: center;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            flex: 1;
            min-width: 120px;
        }
        
        #routes-table {
            font-size: 0.8rem;
        }
        
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter {
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .description-input {
            max-width: 150px;
        }
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        padding: 1rem 0;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
    }
    
    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        display: none;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="spinner"></div>
</div>

<!-- Edit Route Modal -->
<div class="modal fade" id="editRouteModal" tabindex="-1" aria-labelledby="editRouteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRouteModalLabel">Edit Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-route-id">
                <div class="mb-3">
                    <label for="edit-route-name" class="form-label">Route Name</label>
                    <input type="text" class="form-control" id="edit-route-name" readonly>
                </div>
                <div class="mb-3">
                    <label for="edit-route-path" class="form-label">Route Path</label>
                    <input type="text" class="form-control" id="edit-route-path" readonly>
                </div>
                <div class="mb-3">
                    <label for="edit-route-description" class="form-label">Description</label>
                    <textarea class="form-control" id="edit-route-description" rows="3"></textarea>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="edit-route-status">
                    <label class="form-check-label" for="edit-route-status">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveRouteChanges()">Save changes</button>
            </div>
        </div>
    </div>
</div>

@if(auth()->check() && auth()->user()->hasRole('Super Admin'))
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Route Management</h2>
            <div class="action-buttons">
                <button class="btn btn-secondary" onclick="syncRoutes()">Sync Routes</button>
                <button class="btn btn-danger" onclick="bulkDisable()">Bulk Disable</button>
                <button class="btn btn-success" onclick="bulkEnable()">Bulk Enable</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="routes-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>Name</th>
                            <th>Path</th>
                            <th>Method</th>
                            <th>Controller</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="container">
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Access Denied</h4>
        <p>You do not have permission to access the route management system. Only Super Admin users can access this page.</p>
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#routes-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: {
            details: {
                type: 'column',
                target: 'tr'
            }
        },
        ajax: "{{ route('route-management.index') }}",
        columns: [
            { 
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '40px'
            },
            { data: 'route_name', name: 'route_name' },
            { data: 'route_path', name: 'route_path' },
            { 
                data: 'method',
                name: 'method',
                className: 'text-center',
                width: '80px'
            },
            { data: 'controller_action', name: 'controller_action' },
            { 
                data: 'type',
                name: 'type',
                className: 'text-center',
                width: '80px'
            },
            { 
                data: 'status',
                name: 'status',
                orderable: false,
                className: 'text-center',
                width: '80px'
            },
            { 
                data: 'description', 
                name: 'description',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return '<input type="text" class="form-control form-control-sm description-input" value="' + 
                               (data || '') + '" onchange="updateDescription(' + row.id + ', this.value)" placeholder="Add description...">';
                    }
                    return data;
                }
            },
            { 
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '100px'
            }
        ],
        order: [[1, 'asc']],
        drawCallback: function() {
            $('#select-all').prop('checked', false);
        },
        language: {
            search: 'Search:',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
});

function showLoading() {
    document.getElementById('loading-overlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loading-overlay').style.display = 'none';
}

function syncRoutes() {
    showLoading();
    window.location.href = "{{ route('route-management.sync') }}";
}

function toggleStatus(routeId) {
    showLoading();
    fetch(`/route-management/${routeId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            toastr.success('Route status updated successfully');
            $('#routes-table').DataTable().ajax.reload(null, false);
        }
    })
    .catch(error => {
        hideLoading();
        toastr.error('Error updating route status');
        console.error(error);
    });
}

function updateDescription(routeId, description) {
    showLoading();
    fetch(`/route-management/${routeId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            description: description 
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            toastr.success('Description updated successfully');
        }
    })
    .catch(error => {
        hideLoading();
        toastr.error('Error updating description');
        console.error(error);
    });
}

function bulkToggle(status) {
    const selectedRoutes = Array.from(document.querySelectorAll('.route-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedRoutes.length === 0) {
        toastr.warning('Please select at least one route');
        return;
    }

    showLoading();
    fetch('/route-management/bulk-toggle', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            route_ids: selectedRoutes,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            toastr.success('Routes updated successfully');
            $('#routes-table').DataTable().ajax.reload();
        }
    })
    .catch(error => {
        hideLoading();
        toastr.error('Error updating routes');
        console.error(error);
    });
}

function bulkDisable() {
    bulkToggle(false);
}

function bulkEnable() {
    bulkToggle(true);
}

$(document).on('change', '#select-all', function() {
    $('.route-checkbox').prop('checked', this.checked);
});

function editRoute(routeId) {
    showLoading();
    
    // Fetch route details
    fetch(`/route-management/${routeId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.route) {
            // Populate the modal with route data
            document.getElementById('edit-route-id').value = data.route.id;
            document.getElementById('edit-route-name').value = data.route.route_name;
            document.getElementById('edit-route-path').value = data.route.route_path;
            document.getElementById('edit-route-description').value = data.route.description || '';
            document.getElementById('edit-route-status').checked = data.route.is_active;
            
            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('editRouteModal'));
            modal.show();
        } else {
            toastr.error('Failed to load route details');
        }
    })
    .catch(error => {
        hideLoading();
        toastr.error('Error loading route details');
        console.error(error);
    });
}

function saveRouteChanges() {
    const routeId = document.getElementById('edit-route-id').value;
    const description = document.getElementById('edit-route-description').value;
    const isActive = document.getElementById('edit-route-status').checked;
    
    showLoading();
    
    fetch(`/route-management/${routeId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            description: description,
            is_active: isActive
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            // Close the modal
            const modalEl = document.getElementById('editRouteModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            
            // Refresh the table
            $('#routes-table').DataTable().ajax.reload();
            
            toastr.success('Route updated successfully');
        }
    })
    .catch(error => {
        hideLoading();
        toastr.error('Error updating route');
        console.error(error);
    });
}
</script>
@endpush

@endsection