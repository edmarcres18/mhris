@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-history text-primary me-2"></i> Login History
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Search by user, email, or IP...">
                                <button class="btn btn-outline-secondary border-start-0" type="button" id="clearSearch">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-md-end mt-3 mt-md-0 gap-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary date-filter" id="todayFilter" data-period="today">
                                        <i class="fas fa-calendar-day me-1"></i>Today
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary date-filter" id="weekFilter" data-period="week">
                                        <i class="fas fa-calendar-week me-1"></i>This Week
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary date-filter" id="monthFilter" data-period="month">
                                        <i class="fas fa-calendar-alt me-1"></i>This Month
                                    </button>
                                </div>
                                <button class="btn btn-outline-danger" id="clearFilters" style="display: none;">
                                    <i class="fas fa-filter-circle-xmark me-1"></i>Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="activeFilters" class="mb-3" style="display: none;">
                        <div class="d-flex flex-wrap gap-2">
                            <span class="text-muted me-2">Active Filters:</span>
                            <div id="filterBadges"></div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fas fa-user text-muted me-1"></i> User
                                    </th>
                                    <th class="text-nowrap">
                                        <i class="fas fa-globe text-muted me-1"></i> IP Address
                                    </th>
                                    <th class="text-nowrap">
                                        <i class="fas fa-desktop text-muted me-1"></i> Device Info
                                    </th>
                                    <th class="text-nowrap">
                                        <i class="fas fa-check-circle text-muted me-1"></i> Status
                                    </th>
                                    <th class="text-nowrap">
                                        <i class="fas fa-clock text-muted me-1"></i> Date & Time
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($loginHistory as $log)
                                    <tr class="login-entry" data-status="{{ $log->login_successful ? 'success' : 'failed' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($log->user)
                                                    <div class="me-3">
                                                        @if($log->user->profile_image)
                                                            <img src="{{ asset('storage/' . $log->user->profile_image) }}" 
                                                                class="rounded-circle" 
                                                                width="40" 
                                                                height="40"
                                                                alt="{{ $log->user->name }}">
                                                        @else
                                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                                style="width: 40px; height: 40px;">
                                                                {{ substr($log->user->first_name, 0, 1) }}{{ substr($log->user->last_name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $log->user->first_name }} {{ $log->user->last_name }}</div>
                                                        <div class="text-muted small">{{ $log->user->email }}</div>
                                                    </div>
                                                @else
                                                    <div class="me-3">
                                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                            style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user-slash"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-muted">Deleted User</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                {{ $log->ip_address }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="max-width: 300px;">
                                                <small class="text-muted">{{ $log->user_agent }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($log->login_successful)
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Success
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Failed
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-body" title="{{ $log->login_at }}">
                                                    {{ $log->login_at->format('M d, Y h:i A') }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ $log->login_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>No login history found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            Showing {{ $loginHistory->firstItem() ?? 0 }} to {{ $loginHistory->lastItem() ?? 0 }} of {{ $loginHistory->total() }} entries
                        </div>
                        <div>
                            {{ $loginHistory->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
    }
    .page-link {
        padding: 0.375rem 0.75rem;
    }
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1);
    }
    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1);
    }
    .text-wrap {
        word-break: break-word;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    .btn-group > .btn {
        font-size: 0.875rem;
    }
    .input-group-text {
        background-color: transparent;
    }
    #searchInput:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }
    .filter-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        background-color: #e9ecef;
        border-radius: 50rem;
        font-size: 0.875rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .filter-badge .close {
        margin-left: 0.5rem;
        font-size: 0.875rem;
        cursor: pointer;
        opacity: 0.5;
    }

    .filter-badge .close:hover {
        opacity: 1;
    }

    .btn.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    .dropdown-item.active {
        background-color: #e9ecef;
        color: var(--bs-body-color);
    }

    .dropdown-item i {
        width: 16px;
        text-align: center;
    }
    
    .dropdown-item:active {
        background-color: #e9ecef;
        color: var(--bs-body-color);
    }

    .gap-2 {
        gap: 0.5rem !important;
    }

    #clearSearch {
        display: none;
    }

    .no-results {
        display: none;
        text-align: center;
        padding: 2rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const loginEntries = document.querySelectorAll('.login-entry');
    const filterButtons = document.querySelectorAll('.filter-status');
    const dateFilters = document.querySelectorAll('.date-filter');
    const clearFilters = document.getElementById('clearFilters');
    const activeFilters = document.getElementById('activeFilters');
    const filterBadges = document.getElementById('filterBadges');

    let currentFilters = {
        search: '',
        status: 'all',
        date: ''
    };

    function updateFilterBadges() {
        filterBadges.innerHTML = '';
        let hasActiveFilters = false;

        if (currentFilters.search) {
            addFilterBadge('Search: ' + currentFilters.search, 'search');
            hasActiveFilters = true;
        }

        if (currentFilters.status && currentFilters.status !== 'all') {
            addFilterBadge('Status: ' + currentFilters.status.charAt(0).toUpperCase() + currentFilters.status.slice(1), 'status');
            hasActiveFilters = true;
        }

        if (currentFilters.date) {
            addFilterBadge('Date: ' + currentFilters.date.charAt(0).toUpperCase() + currentFilters.date.slice(1), 'date');
            hasActiveFilters = true;
        }

        activeFilters.style.display = hasActiveFilters ? 'block' : 'none';
        clearFilters.style.display = hasActiveFilters ? 'block' : 'none';
    }

    function addFilterBadge(text, type) {
        const badge = document.createElement('span');
        badge.className = 'filter-badge';
        badge.innerHTML = `${text}<span class="close" data-type="${type}"><i class="fas fa-times"></i></span>`;
        filterBadges.appendChild(badge);

        badge.querySelector('.close').addEventListener('click', function() {
            removeFilter(type);
        });
    }

    function removeFilter(type) {
        if (type === 'search') {
            searchInput.value = '';
            currentFilters.search = '';
            clearSearch.style.display = 'none';
        } else if (type === 'status') {
            currentFilters.status = 'all';
            filterButtons.forEach(btn => btn.classList.remove('active'));
        } else if (type === 'date') {
            currentFilters.date = '';
            dateFilters.forEach(btn => btn.classList.remove('active'));
        }
        applyFilters();
    }

    function applyFilters() {
        let visibleCount = 0;
        
        loginEntries.forEach(entry => {
            let showEntry = true;

            // Search filter
            if (currentFilters.search) {
                const text = entry.textContent.toLowerCase();
                showEntry = showEntry && text.includes(currentFilters.search.toLowerCase());
            }

            // Status filter
            if (currentFilters.status && currentFilters.status !== 'all') {
                showEntry = showEntry && entry.dataset.status === currentFilters.status;
            }

            // Date filter
            if (currentFilters.date) {
                const dateCell = entry.querySelector('td:last-child');
                const entryDate = new Date(dateCell.querySelector('[title]').title);
                const now = new Date();
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                const weekAgo = new Date(now - 7 * 24 * 60 * 60 * 1000);
                const monthAgo = new Date(now - 30 * 24 * 60 * 60 * 1000);

                if (currentFilters.date === 'today') {
                    showEntry = showEntry && entryDate >= today;
                } else if (currentFilters.date === 'week') {
                    showEntry = showEntry && entryDate >= weekAgo;
                } else if (currentFilters.date === 'month') {
                    showEntry = showEntry && entryDate >= monthAgo;
                }
            }

            entry.style.display = showEntry ? '' : 'none';
            if (showEntry) visibleCount++;
        });

        updateFilterBadges();
        
        // Show no results message if needed
        const noResults = document.querySelector('.no-results') || createNoResultsElement();
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    function createNoResultsElement() {
        const div = document.createElement('div');
        div.className = 'no-results';
        div.innerHTML = `
            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
            <h5>No Results Found</h5>
            <p class="text-muted">Try adjusting your search or filter criteria</p>
        `;
        document.querySelector('.table-responsive').insertAdjacentElement('afterend', div);
        return div;
    }

    // Search functionality
    searchInput.addEventListener('input', function() {
        currentFilters.search = this.value;
        clearSearch.style.display = this.value ? 'block' : 'none';
        applyFilters();
    });

    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        currentFilters.search = '';
        this.style.display = 'none';
        applyFilters();
    });

    // Status filter
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const status = this.dataset.status;
            currentFilters.status = status;
            
            // Update dropdown label
            const filterLabel = document.querySelector('.filter-label');
            filterLabel.textContent = this.textContent.trim();
            
            // Update active state in dropdown
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            applyFilters();
        });
    });

    // Date filters
    dateFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            const period = this.dataset.period;
            
            // Toggle active state
            if (this.classList.contains('active')) {
                this.classList.remove('active');
                currentFilters.date = '';
            } else {
                dateFilters.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                currentFilters.date = period;
            }
            
            applyFilters();
        });
    });

    // Clear all filters
    clearFilters.addEventListener('click', function() {
        currentFilters = {
            search: '',
            status: 'all',
            date: ''
        };
        searchInput.value = '';
        clearSearch.style.display = 'none';
        filterButtons.forEach(btn => btn.classList.remove('active'));
        dateFilters.forEach(btn => btn.classList.remove('active'));
        applyFilters();
    });
});
</script>
@endsection
@endsection