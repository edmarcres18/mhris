@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="card-title mb-2 mb-sm-0 font-weight-bold">My Tasks</h3>
                <div class="mt-2 mt-sm-0">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text border-right-0"><i class="fas fa-filter"></i></span>
                        </div>
                        <select id="readFilter" class="form-control form-control-sm border-left-0">
                            <option value="">All Tasks</option>
                            <option value="read">Read Tasks</option>
                            <option value="new">Unread Tasks</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Desktop View -->
            <div class="d-none d-md-block">
                <div class="table-responsive">
                    <table id="tasksTable" class="table table-hover mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-top-0">Title</th>
                                <th class="border-top-0">Description</th>
                                <th class="border-top-0">Status</th>
                                <th class="border-top-0">Assigned To</th>
                                <th class="border-top-0 read-status">Read Status</th>
                                <th class="border-top-0 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($tasks->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-clipboard-list fa-2x text-muted mb-3 d-block"></i>
                                        <p class="text-muted mb-0">No tasks available</p>
                                    </td>
                                </tr>
                            @else
                                @foreach($tasks as $task)
                                    <tr data-read="{{ $task->is_read ? 'read' : 'new' }}" class="align-middle">
                                        <td class="font-weight-medium">{{ $task->title }}</td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 250px;">
                                                {{ $task->description }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($task->status === 'Pending')
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-hourglass-half mr-1"></i> Pending
                                                </span>
                                            @elseif($task->status === 'On Progress')
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-cog rotating mr-1"></i> On Progress
                                                </span>
                                            @elseif($task->status === 'Done')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i> Done
                                                </span>
                                            @elseif($task->status === 'Abandoned')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle mr-1"></i> Abandoned
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-muted mr-2"></i>
                                                {{ $task->employee->first_name }} {{ $task->employee->last_name }}
                                            </div>
                                        </td>
                                        <td class="read-status">
                                            @if($task->is_read)
                                                <span class="badge badge-soft-success" data-read="read">
                                                    <i class="fas fa-check-circle mr-1"></i> Read
                                                </span>
                                            @else
                                                <span class="badge badge-soft-info" data-read="new">
                                                    <i class="fas fa-bell mr-1"></i> New
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('tasks.show',$task->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye mr-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile View -->
            <div class="d-md-none px-3 py-3">
                @if($tasks->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h5 class="font-weight-medium">No Tasks Available</h5>
                        <p class="text-muted mb-0">When new tasks are assigned, they will appear here.</p>
                    </div>
                @else
                    <div class="task-list">
                        @foreach($tasks as $task)
                            <div class="task-card mb-3" data-read="{{ $task->is_read ? 'read' : 'new' }}">
                                <a href="{{ route('tasks.show',$task->id) }}" class="task-card-content">
                                    <div class="task-header mb-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h5 class="task-title mb-2">{{ $task->title }}</h5>
                                            <div class="task-badges">
                                                @if(!$task->is_read)
                                                    <span class="badge badge-pulse">
                                                        <i class="fas fa-bell"></i> New
                                                    </span>
                                                @endif
                                                <span class="status-badge status-{{ strtolower($task->status) }}">
                                                    @if($task->status === 'Pending')
                                                        <i class="fas fa-hourglass-half"></i>
                                                    @elseif($task->status === 'On Progress')
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                    @elseif($task->status === 'Done')
                                                        <i class="fas fa-check-circle"></i>
                                                    @elseif($task->status === 'Abandoned')
                                                        <i class="fas fa-times-circle"></i>
                                                    @endif
                                                    {{ $task->status }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="task-body mb-3">
                                        <p class="task-description">{{ $task->description }}</p>
                                    </div>

                                    <div class="task-footer">
                                        <div class="task-meta">
                                            <span class="meta-item">
                                                <i class="fas fa-user-circle"></i>
                                                {{ $task->employee->first_name }} {{ $task->employee->last_name }}
                                            </span>
                                        </div>
                                        <i class="fas fa-chevron-right task-arrow"></i>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Common Styles */
    .rotating {
        animation: rotate 2s infinite linear;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .card {
        border: none;
        margin-bottom: 1.5rem;
    }

    .card-header {
        padding: 1.25rem;
    }

    .card-title {
        color: #2c3e50;
        font-size: 1.25rem;
    }

    /* Badge Styles */
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }

    .badge-soft-success {
        color: #0f5132;
        background-color: #d1e7dd;
    }

    .badge-soft-info {
        color: #055160;
        background-color: #cff4fc;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }

    .table td, .table th {
        padding: 1rem;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }

    /* Filter Styles */
    .input-group-text {
        background-color: #fff;
        border-right: none;
    }

    #readFilter {
        border-left: none;
        cursor: pointer;
    }

    /* Mobile Styles */
    @media screen and (max-width: 767px) {
        .task-card {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        .task-card:active {
            transform: translateY(1px);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .task-card-content {
            display: block;
            padding: 1.25rem;
            color: inherit;
            text-decoration: none !important;
        }

        .task-title {
            color: #2c3e50;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .task-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.875rem;
        }

        .task-description {
            color: #6c757d;
            font-size: 0.95rem;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .task-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid rgba(0,0,0,.05);
        }

        .meta-item {
            color: #6c757d;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .task-arrow {
            color: #adb5bd;
            font-size: 0.875rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        let table;
        
        // Initialize DataTable for desktop with enhanced styling
        if ($(window).width() >= 768) {
            if ($('#tasksTable tbody tr').length > 0 && !$('#tasksTable tbody tr td[colspan]').length) {
                table = $('#tasksTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "pageLength": 10,
                    "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                           '<"row"<"col-sm-12"tr>>' +
                           '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    "columnDefs": [
                        { "orderable": false, "targets": 5 }
                    ],
                    "language": {
                        "emptyTable": "No tasks found",
                        "search": "Search:",
                        "lengthMenu": "Show _MENU_ entries",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "paginate": {
                            "first": "First",
                            "last": "Last",
                            "next": "Next",
                            "previous": "Previous"
                        }
                    }
                });

                // Custom filtering function for read status
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    let selectedFilter = $('#readFilter').val();
                    let row = table.row(dataIndex).node();
                    let readStatus = $(row).attr('data-read');

                    return selectedFilter === '' || selectedFilter === readStatus;
                });
            }
        }

        // Enhanced filtering for both desktop and mobile
        $('#readFilter').on('change', function() {
            let selected = $(this).val();
            
            if ($(window).width() >= 768) {
                if (table) {
                    table.draw();
                }
            } else {
                $('.task-card').each(function() {
                    let card = $(this);
                    let readStatus = card.data('read');
                    
                    if (selected === '' || readStatus === selected) {
                        card.fadeIn(200);
                    } else {
                        card.fadeOut(200);
                    }
                });
                
                // Check for empty state after filtering
                setTimeout(function() {
                    let visibleCards = $('.task-card:visible').length;
                    if (visibleCards === 0) {
                        if (!$('.empty-filter-message').length) {
                            $('.task-list').append(`
                                <div class="empty-filter-message text-center py-4">
                                    <i class="fas fa-filter fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">No tasks match the selected filter</p>
                                </div>
                            `);
                        }
                    } else {
                        $('.empty-filter-message').remove();
                    }
                }, 250);
            }
        });
    });
</script>
@endsection
