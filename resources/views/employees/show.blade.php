@extends('layouts.app')

@section('content')
<br>
@if (Session::has('success') || Session::has('error'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: '{{ Session::has("success") ? "success" : "error" }}',
            title: '{{ Session::has("success") ? Session::get("success") : Session::get("error") }}',
            padding: '1em'
        });
    </script>
@endif

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="card-title">Employee Details</h2>
                    <a href="{{ route('employees.index') }}" class="btn btn-primary btn-sm ml-auto"><i class="fas fa-list"></i> Back to List</a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-4 col-sm-12 text-center mb-3">
                            @if($employee->profile)
                                <img src="{{ asset('storage/' . $employee->profile) }}" alt="Profile Image" 
                                     class="profile-img img-fluid rounded shadow-sm" 
                                     style="max-width: 200px; width: 100%; object-fit: cover;">
                            @else
                                <div class="border rounded p-3 bg-light">
                                    <i class="fas fa-user-circle fa-5x text-secondary"></i>
                                    <p class="mt-2 text-muted">No profile image</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-12 position-relative">
                            <div class="qr-container mb-3">
                                <button class="btn btn-sm btn-outline-primary" onclick="toggleQR()">
                                    <i class="fas fa-qrcode"></i> <span class="d-none d-sm-inline">Toggle QR Code</span>
                                </button>
                                <div id="qrCode" class="mt-2 p-3 border rounded bg-white shadow-sm" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ route('employees.public', $employee->slug) }}" 
                                             alt="Employee QR Code" 
                                             style="width: 100px; height: 100px;">
                                        <div class="ms-3 text-muted" style="font-family: 'Helvetica Neue', sans-serif;">
                                            <div style="transform: rotate(-5deg);">
                                                <span style="font-size: 1.2rem; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
                                                    ✨ Scan Me! ✨
                                                </span>
                                                <div class="curved-arrow-container" style="margin-top: 8px;">
                                                    <i class="fas fa-arrow-left curved-arrow"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4 class="text-primary">{{ $employee->last_name }} {{ $employee->first_name }}, {{ $employee->middle_name ?? '' }} {{ $employee->suffix ?? '' }}</h4>
                            <p><strong>Email:</strong> {{ $employee->email_address }}</p>
                            <p><strong>Contact No.:</strong> {{ $employee->contact_no }}</p>
                            <p><strong>Gender:</strong> {{ $employee->gender->name }}</p>
                            <p><strong>Position:</strong> {{ $employee->position->name }}</p>
                            <p><strong>Employment Status:</strong> {{ $employee->employment_status }}</p>
                            <p>
                                <strong>Employee Status:</strong>
                                <span class="{{ $employee->employee_status === 'Active' ? 'status-active' : 'status-inactive' }}">
                                    {{ $employee->employee_status }}
                                </span>
                            </p>
                            <p>
                                <strong>Place of Birth:</strong>
                                {{ $employee->birth_place_barangay || $employee->birth_place_city || $employee->birth_place_province ?
                                    ($employee->birth_place_barangay ?? 'N/A') . ', ' .
                                    ($employee->birth_place_city ?? 'N/A') . ', ' .
                                    ($employee->birth_place_province ?? 'N/A') :
                                    'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header bg-secondary text-white">
                                    Personal Information
                                </div>
                                <div class="card-body">
                                    <p><strong>Company ID:</strong> {{ $employee->company_id }}</p>
                                    <p><strong>Birth Date:</strong> {{ \Carbon\Carbon::parse($employee->birth_date ?: 'N/A')->format('F j, Y') }}</p>
                                    <p><strong>Emergency Contact Name:</strong> {{ ucfirst($employee->emergency_name) }}</p>
                                    <p><strong>Emergency Contact No:</strong> {{ $employee->emergency_no }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header bg-secondary text-white">
                                    Hired Details
                                </div>
                                <div class="card-body">
                                    <p><strong>Date Hired:</strong> {{ \Carbon\Carbon::parse($employee->date_hired ?: 'N/A')->format('F j, Y') }}</p>
                                    <p><strong>Position:</strong> {{ $employee->position->name }}</p>
                                    <p><strong>Department:</strong> {{ $employee->department->name }} - <strong>Head:</strong> {{ $employee->department->head_name }}</p>
                                    <p>
                                        <strong>Monthly Salary:</strong> 
                                        @if($employee->rank == 'Managerial' && !Auth::user()->hasAnyRole(['Admin', 'Super Admin', 'Finance']))
                                            <span class="text-muted">Restricted</span>
                                        @else
                                            &#8369;{{ number_format($employee->salary, 2) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 mb-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header bg-secondary text-white">
                                    School Attainment & Home Address
                                </div>
                                <div class="card-body">
                                    <p><strong>Elementary:</strong> {{ $employee->elementary ?: 'N/A'}}</p>
                                    <p><strong>Secondary:</strong> {{ $employee->secondary ?: 'N/A'}}</p>
                                    <p><strong>Tertiary:</strong> {{ $employee->tertiary ?: 'N/A'}}</p>
                                    <p><strong>Home Address:</strong> {{ $employee->barangay->name }}, {{ $employee->city->name }},  {{ $employee->province->name }}, {{ $employee->zip_code }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 mb-3">
                            <div class="card border-secondary mb-3">
                                <div class="card-header bg-secondary text-white">
                                    Government ID's
                                </div>
                                <div class="card-body">
                                    <p><strong>TIN:</strong> {{ $employee->tin_no ?: 'N/A' }}</p>
                                    <p><strong>SSS:</strong> {{ $employee->sss_no ?: 'N/A'}}</p>
                                    <p><strong>PAGIBIG:</strong> {{ $employee->pagibig_no ?: 'N/A'}}</p>
                                    <p><strong>PHILHEALTH:</strong> {{ $employee->philhealth_no ?: 'N/A'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <div class="btn-group flex-wrap">
                            <a href="{{ route('employees.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list"></i> <span class="d-none d-sm-inline">Back to List</span>
                            </a>
                            @if($employee->employee_status !== 'Resigned')
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#additionalDetailsModal">
                                    <i class="fas fa-balance-scale"></i> <span class="d-none d-sm-inline">Leave Balance</span>
                                </button>
                                <a href="{{ route('employees.edit', $employee->slug) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Edit</span>
                                </a>
                                @if(Auth::user()->hasRole(['Super Admin', 'Admin']))
                                    <button type="submit" class="btn btn-danger btn-sm delete-btn">
                                        <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Delete</span>
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="additionalDetailsModal" tabindex="-1" role="dialog" aria-labelledby="additionalDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="additionalDetailsModalLabel">Leave Balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="card border-secondary mb-3">
                        <!-- <div class="card-header bg-secondary text-white">
                            Additional Details
                        </div> -->
                        <div class="card-body">
                            <p><strong>Sick Leave:</strong> {{$employee->sick_leave}} Hours - is equivalent - {{($employee->sick_leave) / 24}} Day</p>
                            <p><strong>Vacation Leave:</strong> {{$employee->vacation_leave}} Hours - is equivalent - {{($employee->vacation_leave) / 24}} Day</p>
                            <p><strong>Emergency Leave:</strong> {{$employee->emergency_leave}} Hours - is equivalent - {{($employee->emergency_leave) / 24}} Day</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleQR() {
    const qrCode = document.getElementById('qrCode');
    qrCode.style.display = qrCode.style.display === 'none' ? 'block' : 'none';
    
    if (qrCode.style.display === 'block') {
        qrCode.classList.add('animate__animated', 'animate__fadeIn');
    }
}

// Enhanced delete confirmation
document.querySelector('.delete-btn')?.addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            this.closest('form').submit();
        }
    });
});
</script>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}

.btn-group {
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .btn-group {
        justify-content: center;
        width: 100%;
    }
    
    .btn {
        margin: 0.25rem;
    }
}

@keyframes bounceLeft {
    0%, 100% {
        transform: translateX(0) rotate(-10deg) scale(1);
    }
    50% {
        transform: translateX(-10px) rotate(-10deg) scale(1.1);
    }
}

@keyframes sparkle {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.curved-arrow {
    display: inline-block;
    font-size: 1.8rem;
    color: #444;
    transform: rotate(-10deg);
    animation: bounceLeft 2s ease-in-out infinite;
    position: relative;
    text-shadow: 2px 2px 3px rgba(0,0,0,0.2);
    font-weight: 900;
}

.curved-arrow::before {
    content: '⤾';
    position: absolute;
    font-size: 2rem;
    left: -5px;
    top: -5px;
    opacity: 0.4;
    font-weight: bold;
    color: #333;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.15);
}

.curved-arrow-container {
    position: relative;
    padding-left: 15px;
    margin-top: 12px;
}

#qrCode span {
    animation: sparkle 2s ease-in-out infinite;
}

.profile-img {
    transition: transform 0.3s ease;
}

.profile-img:hover {
    transform: scale(1.05);
}

.delete-btn {
    transition: all 0.3s ease;
}

.delete-btn:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}
</style>
@endsection
