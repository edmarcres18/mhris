@extends('layouts.app')

@section('styles')
<style>
    .notification-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 8px;
        margin-bottom: 1rem;
        background: #fff;
        position: relative;
        overflow: hidden;
    }
    
    .notification-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        transition: all 0.3s ease;
    }
    
    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .notification-card.unread::before {
        background: #007bff;
    }
    
    .notification-card.leave::before {
        background: #28a745;
    }
    
    .notification-card.cash_advance::before {
        background: #ffc107;
    }
    
    .notification-card.pending::before {
        background: #17a2b8;
    }
    
    .notification-card.approved::before {
        background: #28a745;
    }
    
    .notification-card.rejected::before,
    .notification-card.declined::before {
        background: #dc3545;
    }
    
    .notification-date-divider {
        position: relative;
        margin: 2rem 0 1rem;
        display: flex;
        align-items: center;
    }
    
    .notification-date-divider-label {
        background: #f8f9fa;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        color: #495057;
        z-index: 1;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .dark-mode .notification-date-divider-label {
        background: #343a40;
        color: #adb5bd;
        border-color: rgba(255,255,255,0.1);
    }
    
    .notification-date-divider hr {
        position: absolute;
        width: 100%;
        margin: 0;
        border-top: 1px solid rgba(0,0,0,0.08);
    }
    
    .dark-mode .notification-date-divider hr {
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    
    .notification-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 1rem;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    
    .notification-time {
        font-size: 0.8rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .notification-filter {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    
    .dark-mode .notification-filter {
        background: #343a40;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .notification-details {
        display: none;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0,0,0,0.08);
    }
    
    .dark-mode .notification-details {
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    
    .notification-details.show {
        display: block;
    }
    
    .notification-details-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .notification-details-table td {
        padding: 0.75rem;
        border-bottom: 1px solid rgba(0,0,0,0.08);
    }
    
    .dark-mode .notification-details-table td {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .notification-details-table tr:last-child td {
        border-bottom: none;
    }
    
    .notification-details-table td:first-child {
        font-weight: 600;
        width: 35%;
        color: #495057;
    }
    
    .dark-mode .notification-details-table td:first-child {
        color: #adb5bd;
    }
    
    .loading-spinner-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 200px;
    }
    
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
        border-radius: 6px;
    }
    
    .btn-link {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-link:hover {
        color: #0056b3;
        text-decoration: none;
    }
    
    .dark-mode .btn-link {
        color: #66b0ff;
    }
    
    .dark-mode .btn-link:hover {
        color: #99c9ff;
    }
    
    @media (max-width: 767.98px) {
        .notification-filter {
            padding: 1rem;
        }
        
        .notification-icon {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }
        
        .notification-details-table td:first-child {
            width: 40%;
        }
        
        .notification-time {
            font-size: 0.75rem;
        }
    }

    /* Enhanced Toast Styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 350px;
        width: 100%;
    }

    .toast {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-bottom: 10px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        border: none;
    }

    .toast.show {
        opacity: 1;
        transform: translateX(0);
    }

    .toast-header {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 0.75rem 1rem;
    }

    .toast-body {
        padding: 1rem;
        color: #495057;
    }

    .toast.success .toast-header {
        color: #28a745;
    }

    .toast.error .toast-header {
        color: #dc3545;
    }

    .toast.warning .toast-header {
        color: #ffc107;
    }

    .toast.info .toast-header {
        color: #17a2b8;
    }

    .dark-mode .toast {
        background: #343a40;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dark-mode .toast-header {
        border-bottom-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .dark-mode .toast-body {
        color: #adb5bd;
    }

    /* Fix for Mark All As Read button alignment */
    .notification-filter .form-group {
        margin-bottom: 0;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .notification-filter .form-group label {
        margin-bottom: 0.5rem;
    }

    .notification-filter .form-group .form-control {
        flex: 1;
    }

    .notification-filter .form-group:last-child {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    .notification-filter .form-group:last-child .btn {
        width: 100%;
        margin-top: 0.5rem;
    }

    @media (max-width: 767.98px) {
        .notification-filter .form-group {
            margin-bottom: 1rem;
        }

        .notification-filter .form-group:last-child {
            margin-bottom: 0;
        }
    }

    /* Button and filter alignment fixes */
    .notification-filter .form-control,
    .notification-filter .btn {
        height: 38px;
        border-radius: 4px;
    }
    
    .notification-filter .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .notification-filter .form-group {
        margin-bottom: 0;
    }
    
    .notification-filter label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }
    
    /* Ensure consistent heights across all devices */
    @media (max-width: 767.98px) {
        .notification-filter .form-group {
            margin-bottom: 1rem;
        }
        
        .notification-filter .form-group:last-child {
            margin-bottom: 0;
        }
        
        .notification-filter .btn,
        .notification-filter .form-control {
            height: 38px;
        }
    }
</style>
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Notifications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">All Notifications</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-bell mr-1"></i> All Notifications
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row notification-filter">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type-filter" class="form-label">Notification Type</label>
                        <select class="form-control filter-control" id="type-filter">
                            <option value="all">All Types</option>
                            <option value="leave">Leave Requests</option>
                            <option value="cash_advance">Cash Advances</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status-filter" class="form-label">Status</label>
                        <select class="form-control filter-control" id="status-filter">
                            <option value="all">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="active">Active</option>
                            <option value="rejected">Rejected</option>
                            <option value="declined">Declined</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="read-filter" class="form-label">Read Status</label>
                        <select class="form-control filter-control" id="read-filter">
                            <option value="all">All</option>
                            <option value="unread">Unread Only</option>
                            <option value="read">Read Only</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Mark Status</label>
                        <button id="mark-all-read" class="btn btn-primary btn-block">
                            <i class="fas fa-check mr-1"></i> Mark All as Read
                        </button>
                    </div>
                </div>
            </div>

            <div id="notifications-container">
                @if(count($allNotifications) === 0)
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No notifications found</h5>
                        <p class="text-muted">You don't have any notifications at the moment.</p>
                    </div>
                @else
                    @foreach($allNotifications as $date => $notifications)
                        <div class="notification-date-divider">
                            <hr>
                            <div class="notification-date-divider-label">
                                @if(\Carbon\Carbon::parse($date)->isToday())
                                    Today
                                @elseif(\Carbon\Carbon::parse($date)->isYesterday())
                                    Yesterday
                                @else
                                    {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                                @endif
                            </div>
                        </div>
                        
                        @foreach($notifications as $notification)
                            <div class="notification-card {{ !$notification['is_read'] ? 'unread' : '' }} {{ $notification['type'] }} {{ $notification['status'] }}" 
                                 data-type="{{ $notification['type'] }}" 
                                 data-status="{{ $notification['status'] }}" 
                                 data-read="{{ $notification['is_read'] ? 'read' : 'unread' }}">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="notification-icon bg-{{ $notification['type'] == 'leave' ? 'success' : 'warning' }} text-white">
                                            <i class="{{ $notification['icon'] }}"></i>
                                        </div>
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="mb-1">{{ $notification['title'] }}</h5>
                                                <span class="notification-time">
                                                    <i class="far fa-clock"></i>
                                                    {{ $notification['time_human'] }}
                                                </span>
                                            </div>
                                            <p class="mb-2 text-muted">{{ $notification['text'] }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge badge-{{ $notification['status'] == 'pending' ? 'info' : ($notification['status'] == 'approved' || $notification['status'] == 'active' ? 'success' : 'danger') }}">
                                                        {{ ucfirst($notification['status']) }}
                                                    </span>
                                                    @if(!$notification['is_read'])
                                                        <span class="badge badge-primary ml-1">New</span>
                                                    @endif
                                                </div>
                                                <button class="btn btn-link toggle-details" data-id="{{ $notification['id'] }}">
                                                    <i class="fas fa-chevron-down"></i> View Details
                                                </button>
                                            </div>
                                            <div class="notification-details" id="details-{{ $notification['id'] }}">
                                                <table class="notification-details-table">
                                                    <tbody>
                                                        @foreach($notification['details'] as $key => $value)
                                                            <tr>
                                                                <td>{{ $key }}</td>
                                                                <td>{{ $value }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endif
            </div>

            <div id="loading-indicator" class="loading-spinner-container d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Toggle notification details with smooth animation
        $('.toggle-details').on('click', function() {
            const id = $(this).data('id');
            const $details = $(`#details-${id}`);
            const $icon = $(this).find('i');
            
            $details.slideToggle(200);
            
            if ($details.is(':visible')) {
                $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                $(this).text('Hide Details');
            } else {
                $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                $(this).text('View Details');
            }
            
            // Mark as read when expanded
            if (!$details.is(':visible')) {
                const $card = $(this).closest('.notification-card');
                if ($card.hasClass('unread')) {
                    const notificationId = id.split('_');
                    if (notificationId.length > 1) {
                        markAsRead(notificationId[0], notificationId[1]);
                    }
                }
            }
        });
        
        // Apply filters with debounce
        let filterTimeout;
        $('.filter-control').on('change', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(applyFilters, 300);
        });
        
        // Mark all as read with loading state
        $('#mark-all-read').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();
            
            $btn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');
            
            $.ajax({
                url: '{{ route("notifications.mark-all-read") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#loading-indicator').removeClass('d-none');
                },
                success: function(response) {
                    if (response.success) {
                        // Update UI with animation
                        $('.notification-card.unread').each(function(index) {
                            $(this).delay(index * 100).queue(function(next) {
                                $(this).removeClass('unread');
                                $(this).find('.badge.badge-primary').fadeOut(200, function() {
                                    $(this).remove();
                                });
                            });
                        });
                        
                        window.showToast('All notifications marked as read', 'success');
                    } else {
                        window.showToast('Failed to mark notifications as read', 'error');
                    }
                },
                error: function() {
                    window.showToast('An error occurred while processing your request', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalText);
                    $('#loading-indicator').addClass('d-none');
                }
            });
        });
        
        function applyFilters() {
            const typeFilter = $('#type-filter').val();
            const statusFilter = $('#status-filter').val();
            const readFilter = $('#read-filter').val();
            
            $('.notification-card').each(function() {
                const $card = $(this);
                const type = $card.data('type');
                const status = $card.data('status');
                const readStatus = $card.data('read');
                
                const typeMatch = typeFilter === 'all' || type === typeFilter;
                const statusMatch = statusFilter === 'all' || status === statusFilter;
                const readMatch = readFilter === 'all' || readStatus === readFilter;
                
                if (typeMatch && statusMatch && readMatch) {
                    $card.slideDown(200);
                } else {
                    $card.slideUp(200);
                }
            });
            
            // Show/hide date dividers based on visible notifications
            $('.notification-date-divider').each(function() {
                const $divider = $(this);
                const $nextDivider = $divider.nextUntil('.notification-date-divider', '.notification-card:visible');
                
                if ($nextDivider.length === 0) {
                    $divider.slideUp(200);
                } else {
                    $divider.slideDown(200);
                }
            });
            
            // Show no results message if all filtered out
            const visibleNotifications = $('.notification-card:visible').length;
            if (visibleNotifications === 0) {
                if ($('#no-results-message').length === 0) {
                    const noResultsHTML = `
                        <div id="no-results-message" class="text-center py-5">
                            <i class="fas fa-filter fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No matching notifications</h5>
                            <p class="text-muted">Try changing your filter criteria</p>
                        </div>
                    `;
                    $('#notifications-container').append(noResultsHTML);
                }
            } else {
                $('#no-results-message').fadeOut(200, function() {
                    $(this).remove();
                });
            }
        }
        
        function markAsRead(type, id) {
            $.ajax({
                url: '{{ route("notifications.mark-read") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    notification_type: type,
                    notification_id: id
                },
                success: function(response) {
                    if (response.success) {
                        const $card = $(`#details-${type}_${id}`).closest('.notification-card');
                        $card.removeClass('unread');
                        $card.find('.badge.badge-primary').fadeOut(200, function() {
                            $(this).remove();
                        });
                    }
                }
            });
        }

        // Enhanced toast function
        window.showToast = function(message, type = 'info', duration = 3000) {
            const toastContainer = $('#toast-container');
            if (toastContainer.length === 0) {
                $('body').append('<div id="toast-container"></div>');
                toastContainer = $('#toast-container');
            }

            const toast = $(`
                <div class="toast ${type}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <i class="fas ${getToastIcon(type)} mr-2"></i>
                        <strong class="mr-auto">${getToastTitle(type)}</strong>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `);

            toastContainer.append(toast);
            toast.toast({ delay: duration }).toast('show');

            toast.on('hidden.bs.toast', function() {
                $(this).remove();
            });
        };

        function getToastIcon(type) {
            switch(type) {
                case 'success':
                    return 'fa-check-circle';
                case 'error':
                    return 'fa-exclamation-circle';
                case 'warning':
                    return 'fa-exclamation-triangle';
                default:
                    return 'fa-info-circle';
            }
        }

        function getToastTitle(type) {
            switch(type) {
                case 'success':
                    return 'Success';
                case 'error':
                    return 'Error';
                case 'warning':
                    return 'Warning';
                default:
                    return 'Information';
            }
        }
    });
</script>
@endsection
