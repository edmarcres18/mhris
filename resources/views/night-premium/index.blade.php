@extends('layouts.app')

@section('content')
<br>
<div class="container-fluid">
        <!-- Enhanced professional-looking link buttons -->
<div class="mb-4">
    <div class="contribution-nav" role="navigation" aria-label="Contribution Types">
    @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Finance') || Auth::user()->hasRole('VP Finance') || Auth::user()->hasRole('HR ComBen') || Auth::user()->hasRole('Supervisor'))
    <a href="{{ route('attendances.index') }}" class="contribution-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-clock"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Attendance</span>
                <small class="description">Attendance List</small>
            </div>
        </a>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Supervisor'))
        <a href="{{ route('attendances.create') }}" class="contribution-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Time In/Time Out</span>
                <small class="description">Attendance Create</small>
            </div>
        </a>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Finance') || Auth::user()->hasRole('VP Finance') || Auth::user()->hasRole('HR ComBen') || Auth::user()->hasRole('Supervisor'))
        <a href="{{ url('/timesheets') }}" class="contribution-link {{ request()->routeIs('attendances.timesheets') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Timesheets</span>
                <small class="description">Employee attendance records</small>
            </div>
        </a>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Finance') || Auth::user()->hasRole('VP Finance') || Auth::user()->hasRole('HR ComBen') || Auth::user()->hasRole('Supervisor'))
        <a href="{{ route('overtime.index') }}" class="contribution-link {{ request()->routeIs('overtime.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-clock"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Overtime</span>
                <small class="description">Employee overtime records</small>
            </div>
        </a>
        @endif
        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Finance') || Auth::user()->hasRole('VP Finance') || Auth::user()->hasRole('HR ComBen') || Auth::user()->hasRole('Supervisor'))
        <a href="{{ route('night-premium.index') }}" class="contribution-link {{ request()->routeIs('night-premium.index') ? 'active' : '' }}">
            <div class="icon-wrapper">
                <i class="fas fa-moon"></i>
            </div>
            <div class="text-wrapper">
                <span class="title">Night Premium</span>
                <small class="description">Employee night premium records</small>
            </div>
        </a>
        @endif
    </div>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Night Premium List</h3>
                    <div class="card-tools">
                        @canany(['super-admin', 'hrcomben'])
                        <a href="{{ route('night-premium.create') }}" class="btn btn-success btn-sm rounded-pill">
                            Add Night Premium <i class="fas fa-plus-circle"></i>
                        </a>
                        @endcanany
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="night-premium-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Night Hours</th>
                                <th>Night Rate</th>
                                <th>Night Premium Pay</th>
                                <th>Status</th>
                                <th>Approved By</th>
                                <th>Rejected By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nightPremiums as $nightPremium)
                                <tr>
                                    <td>{{ $nightPremium->employee->company_id }} {{ $nightPremium->employee->last_name }} {{ $nightPremium->employee->first_name }} {{ $nightPremium->employee->middle_name ?? ' ' }} {{ $nightPremium->employee->suffix ?? ' ' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($nightPremium->date)->format('F j, Y') }}</td>
                                    <td>{{ $nightPremium->night_hours }}</td>
                                    <td>{{ $nightPremium->night_rate }}</td>
                                    <td>{{ number_format($nightPremium->night_premium_pay, 2) }}</td>
                                    <td>
                                        @if($nightPremium->approval_status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($nightPremium->approval_status == 'approvedBySupervisor')
                                            <span class="badge badge-success">Approved by Supervisor</span>
                                            <small>{{ $nightPremium->approved_at_supervisor ? \Carbon\Carbon::parse($nightPremium->approved_at_supervisor)->format('M j, Y g:i A') : '' }}</small>
                                        @elseif($nightPremium->approval_status == 'approvedByFinance')
                                            <span class="badge badge-success">Approved by Finance</span>
                                            <small>{{ $nightPremium->approved_at_finance ? \Carbon\Carbon::parse($nightPremium->approved_at_finance)->format('M j, Y g:i A') : '' }}</small>
                                        @elseif($nightPremium->approval_status == 'approvedByVPFinance')
                                            <span class="badge badge-success">Approved by VP Finance</span>
                                            <small>{{ $nightPremium->approved_at_vpfinance ? \Carbon\Carbon::parse($nightPremium->approved_at_vpfinance)->format('M j, Y g:i A') : '' }}</small>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                            <small>{{ $nightPremium->rejected_at ? \Carbon\Carbon::parse($nightPremium->rejected_at)->format('M j, Y g:i A') : '' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                    @if($nightPremium->approval_status == 'approvedBySupervisor')
                                        @if($nightPremium->supervisor)
                                            {{ $nightPremium->supervisor->first_name }} {{ $nightPremium->supervisor->last_name }}
                                        @else
                                            -
                                        @endif
                                    @elseif($nightPremium->approval_status == 'approvedByFinance')
                                        @if($nightPremium->financeHead)
                                            {{ $nightPremium->financeHead->first_name }} {{ $nightPremium->financeHead->last_name }}
                                        @else
                                            -
                                        @endif
                                    @elseif($nightPremium->approval_status == 'approvedByVPFinance')
                                        @if($nightPremium->vpFinance)
                                            {{ $nightPremium->vpFinance->first_name }} {{ $nightPremium->vpFinance->last_name }}
                                        @else
                                            -
                                        @endif
                                    @endif
                                    </td>
                                    <td>
                                    @if($nightPremium->approval_status == 'rejectedBySupervisor')
                                        @if($nightPremium->supervisor)
                                            {{ $nightPremium->supervisor->first_name }} {{ $nightPremium->supervisor->last_name }}
                                        @else
                                            -
                                        @endif
                                    @elseif($nightPremium->approval_status == 'rejectedByFinance')
                                        @if($nightPremium->financeHead)
                                            {{ $nightPremium->financeHead->first_name }} {{ $nightPremium->financeHead->last_name }}
                                        @else
                                            -
                                        @endif
                                    @elseif($nightPremium->approval_status == 'rejectedByVPFinance')
                                        @if($nightPremium->vpFinance)
                                            {{ $nightPremium->vpFinance->first_name }} {{ $nightPremium->vpFinance->last_name }}
                                        @else
                                            -
                                        @endif
                                    @endif
                                    </td>
                                    <td>
                                        @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Finance') || Auth::user()->hasRole('HR ComBen') || Auth::user()->hasRole('Supervisor') || Auth::user()->hasRole('VP Finance'))
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                            @if(Auth::user()->hasRole('Supervisor') || Auth::user()->hasRole('Super Admin'))
                                                @if($nightPremium->approval_status == 'pending' && $nightPremium->employee->rank == 'Rank File')
                                                            <form action="{{ route('night-premium.approvedBySupervisor', $nightPremium->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="dropdown-item"><i class="fas fa-check"></i>&nbsp;Approve</button>
                                                            </form>
                                                            <form action="{{ route('night-premium.rejectedBySupervisor', $nightPremium->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="dropdown-item"><i class="fas fa-times"></i>&nbsp;Reject</button>
                                                            </form>
                                                @endif
                                            @endif
                                            @if(Auth::user()->hasRole('Finance') || Auth::user()->hasRole('Super Admin'))
                                                @if(($nightPremium->approval_status == 'approvedBySupervisor' && $nightPremium->employee->rank == 'Rank File') || 
                                                    ($nightPremium->approval_status == 'pending' && $nightPremium->employee->rank == 'Managerial'))
                                                        <form action="{{ route('night-premium.approvedByFinance', $nightPremium->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item"><i class="fas fa-check"></i>&nbsp;Approve</button>
                                                        </form>
                                                        <form action="{{ route('night-premium.rejectedByFinance', $nightPremium->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item"><i class="fas fa-times"></i>&nbsp;Reject</button>
                                                        </form>
                                                @endif
                                            @endif
                                            @if(Auth::user()->hasRole('VP Finance') || Auth::user()->hasRole('Super Admin'))
                                            @if($nightPremium->approval_status == 'approvedByFinance')
                                                        <form action="{{ route('night-premium.approvedByVPFinance', $nightPremium->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item"><i class="fas fa-check"></i>&nbsp;Approve</button>
                                                        </form>
                                                        <form action="{{ route('night-premium.rejectedByVPFinance', $nightPremium->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item"><i class="fas fa-times"></i>&nbsp;Reject</button>
                                                        </form>
                                                    @endif
                                            @endif

                                            @if(Auth::user()->hasRole('Finance') || Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('HR ComBen') || Auth::user()->hasRole('Supervisor') || Auth::user()->hasRole('VP Finance'))
                                                <a href="{{ route('night-premium.show', $nightPremium->id) }}" class="dropdown-item"><i class="fas fa-eye"></i>&nbsp;View</a>
                                            @endif
                                            @if(Auth::user()->hasRole('Super Admin'))
                                                <form action="{{ route('night-premium.destroy', $nightPremium->id) }}" method="POST">
                                                    @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"><i class="fas fa-trash"></i>&nbsp;Delete</button>
                                                    </form>
                                            @endif
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // SweetAlert toast configuration
        const toastConfig = {
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            background: '#fff',
            color: '#424242',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast'
            }
        };

        // Success toast
        @if(Session::has('success'))
            Swal.fire({
                ...toastConfig,
                icon: 'success',
                title: 'Success',
                text: "{{ Session::get('success') }}",
                background: '#28a745',
                color: '#fff'
            });
        @endif

        // Error toast
        @if(Session::has('error'))
            Swal.fire({
                ...toastConfig,
                icon: 'error',
                title: 'Error',
                text: "{{ Session::get('error') }}",
                background: '#dc3545',
                color: '#fff'
            });
        @endif

        // Delete confirmation
        $(document).on('click', '.dropdown-item[type="submit"]', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            let action = form.attr('action');
            let confirmTitle, confirmText, confirmButtonText, confirmIcon;
            
            // Set appropriate messages based on the action
            if (action.includes('approve')) {
                confirmTitle = 'Approve Night Premium?';
                confirmText = "Are you sure you want to approve this night premium request?";
                confirmButtonText = 'Yes, approve it!';
                confirmIcon = 'question';
            } else if (action.includes('reject')) {
                confirmTitle = 'Reject Night Premium?';
                confirmText = "Are you sure you want to reject this night premium request?";
                confirmButtonText = 'Yes, reject it!';
                confirmIcon = 'question';
            } else {
                confirmTitle = 'Delete Night Premium?';
                confirmText = "You won't be able to revert this!";
                confirmButtonText = 'Yes, delete it!';
                confirmIcon = 'warning';
            }

            Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: confirmIcon,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // DataTable initialization
        $('#night-premium-table').DataTable();
    });
</script>
@endsection 