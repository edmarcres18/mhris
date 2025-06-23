@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
    <div class="mb-4">
        <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
            <a href="{{ route('hirings.index') }}" class="contribution-link {{ request()->routeIs('hirings.index') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">Hirings</span>
                    <small class="description">Job Available List</small>
                </div>
            </a>
            <a href="{{ url('/all-careers') }}" class="contribution-link {{ request()->routeIs('careers.all') ? 'active' : '' }}">
                <div class="icon-wrapper">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="text-wrapper">
                    <span class="title">
                        Applicants
                        @php
                            $unreadCareersCount = \App\Models\Career::where('is_read', false)->count();
                        @endphp
                        <span id="all-careers-unread-badge" class="badge badge-danger" style="{{ $unreadCareersCount > 0 ? '' : 'display: none;' }}">{{ $unreadCareersCount }}</span>
                    </span>
                    <small class="description">Applicants List</small>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Careers</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                            </div>
                            <select id="read-filter" class="form-control">
                                <option value="">All Applications</option>
                                <option value="read">Read Applications</option>
                                <option value="unread">Unread Applications</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="careers-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Experience</th>
                                    <th>Applied At</th>
                                    <th>Read</th>
                                    <th>Action</th> <!-- New column -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($careers as $career)
                                    <tr>
                                        <td>{{ $career->hiring->position ?? 'N/A' }}</td>
                                        <td>{{ $career->first_name }}</td>
                                        <td>{{ $career->last_name }}</td>
                                        <td>{{ $career->email }}</td>
                                        <td>{{ $career->experience }} years</td>
                                        <td>{{ $career->created_at->format('F j, Y \a\t g:i A') }}</td>
                                        <td>
                                            @if($career->is_read)
                                                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Read</span>
                                            @else
                                                <span class="badge badge-info"><i class="fas fa-bell mr-1"></i> New</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('showApplicant', $career->id) }}" class="btn btn-primary btn-sm">View Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection

@section('css')
<style>
    #read-filter {
        max-width: 250px;
    }
    .input-group-text {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff;
    }

    /* Add responsive styles for the contribution nav */
    @media (max-width: 768px) {
        .contribution-nav {
            flex-direction: column;
            gap: 10px;
        }
        .contribution-link {
            width: 100%;
        }
        #read-filter {
            max-width: 100%;
        }
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        var table = $('#careers-table').DataTable({
            responsive: true,
            scrollX: true,
            autoWidth: false,
            language: {
                emptyTable: "No applicants available at the moment."
            },
            columnDefs: [
                { responsivePriority: 1, targets: 0 }, // Position
                { responsivePriority: 2, targets: 6 }, // Read status
                { responsivePriority: 3, targets: 7 }, // Action
                { responsivePriority: 4, targets: '_all' },
                { orderable: false, targets: 7 }
            ],
            // Maintain the filter functionality
            drawCallback: function() {
                var status = $('#read-filter').val();
                if (status !== "") {
                    table.draw();
                }
            }
        });

        $('#read-filter').on('change', function() {
            table.draw();
        });

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var status = $('#read-filter').val();
                var isRead = $(table.row(dataIndex).node()).find('td:eq(6) span').hasClass('badge-success');

                if (status === "") return true;
                if (status === "read" && isRead) return true;
                if (status === "unread" && !isRead) return true;

                return false;
            }
        );
    });
</script>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Function to update the unread careers count
        function updateUnreadCareersCount() {
            $.ajax({
                url: '{{ route("careers.unread-count") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var badge = $('#all-careers-unread-badge');
                    if (response.count > 0) {
                        badge.text(response.count).show();
                    } else {
                        badge.hide();
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching unread careers count:', xhr.responseText);
                }
            });
        }
        
        // Update count every 30 seconds
        setInterval(updateUnreadCareersCount, 30000);
    });
</script>
@endsection
