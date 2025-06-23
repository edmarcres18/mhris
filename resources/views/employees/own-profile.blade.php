@extends('layouts.employee-profile')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Left column with photo and basic info -->
        <div class="col-lg-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block">
                        <img class="rounded-circle mb-3 shadow"
                             src="{{ $employee->profile ? asset('storage/' . $employee->profile) : asset('images/default-avatar.png') }}"
                             alt="{{ $employee->first_name }} {{ $employee->last_name }}"
                             style="width: 180px; height: 180px; object-fit: cover;">
                        @if(auth()->user()->email === $employee->email_address)
                            <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 mb-3 me-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#updateProfileImageModal">
                                <i class="fas fa-camera"></i>
                            </button>
                        @endif
                    </div>
                    <h2 class="font-weight-bold">{{ $employee->first_name }} {{ $employee->last_name }}</h2>
                    <p class="text-muted mb-2">{{ $employee->email_address }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $employee->contact_no }}</p>
                    <p class="mb-1"><strong>Gender:</strong> {{ $employee->gender->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Birthplace:</strong> {{ $employee->birth_place_province ?? 'N/A' }}, {{ $employee->birth_place_city ?? 'N/A' }}, {{ $employee->birth_place_barangay ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Right column with detailed information -->
        <div class="col-lg-9">
            <div id="infoSections">
                <!-- Personal Information -->
                <div id="personal" class="info-section">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user mr-2"></i>Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Birth Date:</strong> {{ $employee->birth_date ? \Carbon\Carbon::parse($employee->birth_date)->format('F d, Y') : 'N/A' }}</p>
                            <p><strong>Emergency Contact Name:</strong> {{ $employee->emergency_name ?? 'N/A' }}</p>
                            <p><strong>Emergency Contact #:</strong> {{ $employee->emergency_no ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Work Details -->
                <div id="work" class="info-section">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-briefcase mr-2"></i>Work Details</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Date Hired:</strong> {{ $employee->date_hired ? \Carbon\Carbon::parse($employee->date_hired)->format('F d, Y') : 'N/A' }}</p>
                            <p><strong>Company ID:</strong> {{ $employee->company_id }}</p>
                            <p><strong>Position:</strong> {{ $employee->position->name }}</p>
                            <p><strong>Department:</strong> {{ $employee->department->name }}</p>
                            <p><strong>Head:</strong> {{ $employee->department->head_name }}</p>
                            <p><strong>Employement:</strong> {{ $employee->employment_status }}</p>
                            <p><strong>Employee Status:</strong> <span class="badge bg-success">{{ $employee->employee_status }}</span></p>
                            <p><strong>Monthly Salary:</strong> ₱{{ number_format($employee->salary, 2, '.', ',') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Education -->
                <div id="education" class="info-section">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Education</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Elementary:</strong> {{ $employee->elementary ?? 'N/A' }}</p>
                            <p><strong>Secondary:</strong> {{ $employee->secondary ?? 'N/A' }}</p>
                            <p><strong>Tertiary:</strong> {{ $employee->tertiary ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div id="address" class="info-section">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0"><i class="fas fa-home mr-2"></i>Home Address</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Province:</strong> {{ $employee->province->name }}</p>
                            <p><strong>City:</strong> {{ $employee->city->name }}</p>
                            <p><strong>Barangay:</strong> {{ $employee->barangay->name }}</p>
                            <p><strong>Zip Code:</strong> {{ $employee->zip_code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Government IDs -->
                <div id="government" class="info-section">
                    <div class="card shadow-sm">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Government IDs</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- TIN ID Card -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="gov-id-card tin-card h-100">
                                        <div class="id-header">
                                            <img src="{{ asset('vendor/adminlte/dist/img/tin.png') }}" alt="BIR Logo" class="gov-logo">
                                            <div class="header-text">
                                                <h6 class="mb-0">Republic of the Philippines</h6>
                                                <small>Bureau of Internal Revenue</small>
                                            </div>
                                        </div>
                                        <div class="id-body">
                                            <h5 class="id-title">TAX IDENTIFICATION NUMBER</h5>
                                            <div class="id-number">
                                                @if($employee->tin_no)
                                                    @php
                                                        $clean_tin = preg_replace('/[^0-9]/', '', $employee->tin_no);
                                                        $tin = str_pad($clean_tin, 12, '0', STR_PAD_LEFT);
                                                        $formatted_tin = substr($tin, 0, 3) . '-' . 
                                                                       substr($tin, 3, 3) . '-' . 
                                                                       substr($tin, 6, 3) . '-' . 
                                                                       substr($tin, 9, 3);
                                                    @endphp
                                                    <span class="number">{{ $formatted_tin }}</span>
                                                @else
                                                    <span class="text-muted">Not Available</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SSS ID Card -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="gov-id-card sss-card h-100">
                                        <div class="id-header">
                                            <img src="{{ asset('vendor/adminlte/dist/img/sss.png') }}" alt="SSS Logo" class="gov-logo">
                                            <div class="header-text">
                                                <h6 class="mb-0">Social Security System</h6>
                                                <small>Republic of the Philippines</small>
                                            </div>
                                        </div>
                                        <div class="id-body">
                                            <h5 class="id-title">SSS NUMBER</h5>
                                            <div class="id-number">
                                                @php
                                                    if ($employee->sss_no) {
                                                        $clean_sss = preg_replace('/[^0-9]/', '', $employee->sss_no);
                                                        $sss = str_pad($clean_sss, 10, '0', STR_PAD_LEFT);
                                                        $formatted_sss = substr($sss, 0, 2) . '-' . 
                                                                       substr($sss, 2, 7) . '-' . 
                                                                       substr($sss, 9, 1);
                                                    } else {
                                                        $formatted_sss = 'Not Available';
                                                    }
                                                @endphp
                                                <span class="number">{{ $formatted_sss }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PAGIBIG ID Card -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="gov-id-card pagibig-card h-100">
                                        <div class="id-header">
                                            <img src="{{ asset('vendor/adminlte/dist/img/pagibig.png') }}" alt="PAGIBIG Logo" class="gov-logo">
                                            <div class="header-text">
                                                <h6 class="mb-0">Home Development Mutual Fund</h6>
                                                <small>Pag-IBIG Fund</small>
                                            </div>
                                        </div>
                                        <div class="id-body">
                                            <h5 class="id-title">PAGIBIG MID NUMBER</h5>
                                            <div class="id-number">
                                                @if($employee->pagibig_no)
                                                    @php
                                                        $clean_pagibig = preg_replace('/[^0-9]/', '', $employee->pagibig_no);
                                                        $pagibig = str_pad($clean_pagibig, 12, '0', STR_PAD_LEFT);
                                                        $formatted_pagibig = substr($pagibig, 0, 4) . '-' . 
                                                                          substr($pagibig, 4, 4) . '-' . 
                                                                          substr($pagibig, 8, 4);
                                                    @endphp
                                                    <span class="number">{{ $formatted_pagibig }}</span>
                                                @else
                                                    <span class="text-muted">Not Available</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PHILHEALTH ID Card -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="gov-id-card philhealth-card h-100">
                                        <div class="id-header">
                                            <img src="{{ asset('vendor/adminlte/dist/img/philhealth.png') }}" alt="PhilHealth Logo" class="gov-logo">
                                            <div class="header-text">
                                                <h6 class="mb-0">Philippine Health Insurance</h6>
                                                <small>PhilHealth</small>
                                            </div>
                                        </div>
                                        <div class="id-body">
                                            <h5 class="id-title">PHILHEALTH NUMBER</h5>
                                            <div class="id-number">
                                                @if($employee->philhealth_no)
                                                    @php
                                                        $clean_philhealth = preg_replace('/[^0-9]/', '', $employee->philhealth_no);
                                                        $philhealth = str_pad($clean_philhealth, 12, '0', STR_PAD_LEFT);
                                                        $formatted_philhealth = substr($philhealth, 0, 2) . '-' . 
                                                                             substr($philhealth, 2, 9) . '-' . 
                                                                             substr($philhealth, 11, 1);
                                                    @endphp
                                                    <span class="number">{{ $formatted_philhealth }}</span>
                                                @else
                                                    <span class="text-muted">Not Available</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Signature -->
                <div id="signature" class="info-section">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-signature mr-2"></i>Digital Signature</h5>
                            @if(auth()->user()->email === $employee->email_address && !$employee->signature)
                                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#signatureModal">
                                    <i class="fas fa-plus mr-1"></i> Add Signature
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($employee->signature)
                                <div class="text-center">
                                    <div class="border rounded p-4 d-inline-block bg-white">
                                        <img src="{{ Storage::url($employee->signature) }}"
                                             alt="Employee Signature"
                                             class="img-fluid"
                                             style="max-height: 100px;">
                                    </div>

                                    <div class="mt-3">
                                        <small class="text-muted d-block">
                                            Last updated: {{ \Carbon\Carbon::parse($employee->updated_at)->format('F d, Y h:i A') }}
                                        </small>

                                        @if(auth()->user()->email === $employee->email_address)
                                            <button class="btn btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#signatureModal">
                                                <i class="fas fa-edit me-2"></i> Update Signature
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="text-muted mb-3">
                                        <i class="fas fa-signature fa-2x"></i>
                                        <p class="mt-2">No signature has been uploaded yet.</p>
                                    </div>

                                    @if(auth()->user()->email === $employee->email_address)
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#signatureModal">
                                            <i class="fas fa-plus me-2"></i> Add Your Signature
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Signature Modal -->
<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">
                    <i class="fas fa-signature me-2"></i>
                    {{ $employee->signature ? 'Update Your Signature' : 'Add Your Signature' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                @if($employee->signature)
                    <div class="alert alert-info m-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Your existing signature will be replaced when you save a new one.
                    </div>
                @endif
                <div class="signature-container position-relative" style="height: calc(100vh - 180px);">
                    <canvas id="signatureCanvas" class="signature-canvas"></canvas>
                    <div class="signature-helper-text position-absolute top-50 start-50 translate-middle text-muted">
                        Sign here
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="clearSignature()">
                    <i class="fas fa-eraser me-2"></i> Clear
                </button>
                <button type="button" class="btn btn-primary" onclick="saveSignature()">
                    <i class="fas fa-save me-2"></i> 
                    {{ $employee->signature ? 'Update Signature' : 'Save Signature' }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Profile Image Modal -->
<div class="modal fade" id="updateProfileImageModal" tabindex="-1" role="dialog" aria-labelledby="updateProfileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title h6 font-weight-bold" id="updateProfileImageModalLabel">
                    <i class="fas fa-camera me-2"></i>Update Profile Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                @if($employee->profile_updated_at && now()->diffInDays($employee->profile_updated_at) < 60)
                    @php
                        $nextUpdateDate = \Carbon\Carbon::parse($employee->profile_updated_at)->addDays(60);
                        $daysRemaining = now()->diffInDays($nextUpdateDate);
                    @endphp
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold mb-2">Profile updates are limited to once every 60 days.</span>
                            <span>Next update available on: <strong>{{ $nextUpdateDate->format('F d, Y') }}</strong></span>
                            <span class="mt-1">({{ $daysRemaining }} days remaining)</span>
                        </div>
                    </div>
                @else
                <div class="alert alert-info mb-3">
                    <h5 class="font-weight-bold mb-3" style="font-size: 1.1rem; color: #1a3353;">
                        <i class="fas fa-info-circle me-2"></i>Photo Requirements:
                    </h5>
                    <ul class="list-unstyled mb-0" style="font-size: 1rem; line-height: 1.6;">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-check-circle text-warning me-2" style="font-size: 1.2rem;"></i>
                            <span>Must be presentable</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="fas fa-check-circle text-warning me-2" style="font-size: 1.2rem;"></i>
                            <span>White background</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-warning me-2" style="font-size: 1.2rem;"></i>
                            <span>Proper business attire</span>
                        </li>
                    </ul>
                </div>
                    <form id="profileImageForm" enctype="multipart/form-data">
                        <div class="upload-box">
                            <input type="file" class="file-input" id="profile" name="profile" accept="image/jpeg,image/png,image/jpg">
                            <div class="upload-content p-4">
                                <div id="defaultUploadContent" class="text-center">
                                    <i class="fas fa-cloud-upload-alt upload-icon fa-3x mb-3 text-primary"></i>
                                    <p class="upload-text h6 mb-2">
                                        <span class="d-none d-sm-inline">Drag and drop your image here<br>or </span>
                                        <span>click to browse</span>
                                    </p>
                                    <p class="upload-formats small text-muted">Accepted formats: JPG, JPEG, PNG</p>
                                </div>
                                <div id="imagePreview" style="display: none;" class="text-center">
                                    <div class="preview-wrapper mb-3">
                                        <img src="" alt="Preview" class="preview-image img-fluid rounded">
                                    </div>
                                    <p class="file-name small text-muted mb-2"></p>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                        <i class="fas fa-times"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light font-weight-bold" data-bs-dismiss="modal">Cancel</button>
                @if(!$employee->profile_updated_at || now()->diffInDays($employee->profile_updated_at) >= 60)
                    <button type="button" class="btn btn-primary font-weight-bold px-4" onclick="updateProfileImage()">
                        Save changes
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Add SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* Core Layout & Components */
    .card {
        border: none;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        background: #fff;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Profile Section Enhancements */
    .profile-image-wrapper {
        position: relative;
        display: inline-block;
        margin: 1rem auto;
    }

    .profile-image {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .profile-image:hover {
        transform: scale(1.02);
    }

    /* Enhanced Navigation Buttons */
    .nav-buttons-wrapper {
        background: rgba(255,255,255,0.95);
        padding: 1rem 0;
        position: sticky;
        top: 0;
        z-index: 100;
        backdrop-filter: blur(8px);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .nav-button {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        min-width: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .nav-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Section Content Styling */
    .info-section {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .info-section.active {
        opacity: 1;
        transform: translateY(0);
    }

    .section-content {
        padding: 1.5rem;
    }

    /* Responsive Breakpoints */
    @media (max-width: 1400px) {
        .container-fluid {
            max-width: 1140px;
            margin: 0 auto;
        }
    }

    @media (max-width: 1200px) {
        .profile-image {
            width: 150px;
            height: 150px;
        }

        .nav-button {
            min-width: 120px;
            padding: 0.6rem 1rem;
        }
    }

    @media (max-width: 992px) {
        .container-fluid {
            padding: 1rem;
        }

        .profile-section {
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .nav-buttons-wrapper .d-flex {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .nav-buttons-wrapper .d-flex::-webkit-scrollbar {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .profile-image {
            width: 120px;
            height: 120px;
        }

        .card-body {
            padding: 1rem;
        }

        .section-content {
            padding: 1rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        .nav-button {
            min-width: auto;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .custom-select {
            font-size: 0.95rem;
            padding: 0.6rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding: 0.75rem;
        }

        .profile-image {
            width: 100px;
            height: 100px;
        }

        .card {
            border-radius: 8px;
        }

        .nav-button {
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }

        .section-content {
            padding: 0.75rem;
        }

        .custom-select {
            font-size: 0.9rem;
            padding: 0.5rem;
        }
    }

    /* Enhanced Modal Styles */
    .modal-fullscreen {
        padding: 0 !important;
    }

    .modal-fullscreen .modal-content {
        border-radius: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .modal-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 1.5rem;
    }

    /* Upload Box Refinements */
    .upload-box {
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-box.dragover {
        border-color: #007bff;
        background: rgba(0,123,255,0.05);
    }

    .upload-content {
        text-align: center;
        padding: 2rem;
    }

    /* Animation Keyframes */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideIn {
        from { transform: translateX(-10px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* Utility Classes */
    .smooth-transition {
        transition: all 0.3s ease;
    }

    .hover-lift {
        transition: transform 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
    }

    /* Enhanced Mobile Dropdown Styling */
    .mobile-nav-wrapper {
        position: relative;
        margin: 1rem 0;
    }

    .custom-select {
        width: 100%;
        padding: 1.25rem 1.5rem;
        height: 60px;
        font-size: 1.1rem;
        font-weight: 500;
        line-height: 1.5;
        color: #2c3e50;
        background-color: #ffffff;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.25s ease-in-out;
        cursor: pointer;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .custom-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.15);
        outline: none;
    }

    .custom-select:hover {
        border-color: #3498db;
        background-color: #f8f9fa;
    }

    /* Select Arrow Styling */
    .select-arrow {
        position: absolute;
        right: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        transition: transform 0.2s ease;
    }

    .select-arrow i {
        color: #3498db;
        font-size: 1rem;
    }

    .custom-select:focus + .select-arrow {
        transform: translateY(-50%) rotate(180deg);
    }

    /* Option Styling */
    .select-option {
        padding: 12px 16px;
        margin: 4px 0;
        font-weight: 500;
    }

    .option-icon {
        margin-right: 8px;
        font-size: 1.1em;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 768px) {
        .mobile-nav-wrapper {
            margin: 0.875rem 0;
        }

        .custom-select {
            height: 55px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .mobile-nav-wrapper {
            margin: 0.75rem 0;
        }

        .custom-select {
            height: 50px;
            padding: 0.875rem 1.125rem;
            font-size: 0.95rem;
        }

        .select-arrow {
            right: 1rem;
        }

        .select-arrow i {
            font-size: 0.875rem;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .custom-select {
            background-color: #2c3e50;
            color: #ffffff;
            border-color: #34495e;
        }

        .custom-select:hover {
            background-color: #34495e;
        }

        .select-arrow i {
            color: #3498db;
        }
    }

    /* Smooth Animation for Section Changes */
    .info-section {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    /* Loading State */
    .custom-select:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        background-color: #f8f9fa;
    }

    /* Active State */
    .custom-select:active {
        transform: scale(0.99);
    }

    /* Better Touch Area for Mobile */
    @media (hover: none) and (pointer: coarse) {
        .custom-select {
            min-height: 60px;
        }
    }

    /* Enhanced Modal Responsive Styles */
    #updateProfileImageModal .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }

    #updateProfileImageModal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    #updateProfileImageModal .upload-box {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        position: relative;
        min-height: 200px;
    }

    #updateProfileImageModal .upload-box.dragover {
        border-color: #007bff;
        background: rgba(0, 123, 255, 0.05);
    }

    #updateProfileImageModal .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    #updateProfileImageModal .preview-wrapper {
        max-width: 300px;
        margin: 0 auto;
    }

    #updateProfileImageModal .preview-image {
        max-height: 200px;
        width: auto;
        object-fit: contain;
    }

    /* Mobile Specific Styles */
    @media (max-width: 576px) {
        #updateProfileImageModal .modal-dialog {
            margin: 0.5rem;
        }

        #updateProfileImageModal .modal-content {
            border-radius: 12px;
        }

        #updateProfileImageModal .modal-body {
            padding: 1rem;
        }

        #updateProfileImageModal .upload-box {
            min-height: 180px;
        }

        #updateProfileImageModal .upload-icon {
            font-size: 2em;
        }

        #updateProfileImageModal .upload-text {
            font-size: 0.9rem;
        }

        #updateProfileImageModal .upload-formats {
            font-size: 0.8rem;
        }

        #updateProfileImageModal .preview-image {
            max-height: 150px;
        }
    }

    /* Tablet Specific Styles */
    @media (min-width: 577px) and (max-width: 768px) {
        #updateProfileImageModal .modal-dialog {
            max-width: 450px;
            margin: 1rem auto;
        }

        #updateProfileImageModal .upload-box {
            min-height: 220px;
        }
    }

    /* Touch Device Optimizations */
    @media (hover: none) and (pointer: coarse) {
        #updateProfileImageModal .upload-box {
            cursor: pointer;
        }

        #updateProfileImageModal .btn {
            padding: 0.5rem 1rem;
            min-height: 44px;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        #updateProfileImageModal .upload-box {
            background: #2d3436;
            border-color: #4a5568;
        }

        #updateProfileImageModal .modal-content {
            background: #1a202c;
            color: #fff;
        }

        #updateProfileImageModal .text-muted {
            color: #a0aec0 !important;
        }

        #updateProfileImageModal .close {
            color: #fff;
        }

        #updateProfileImageModal .btn-light {
            background: #2d3436;
            color: #fff;
            border-color: #4a5568;
        }
    }

    /* Signature Modal Specific Styles */
    #signatureModal .modal-dialog {
        margin: 0;
        padding: 0;
        max-width: 100%;
    }

    #signatureModal .modal-content {
        border-radius: 0;
        min-height: 100vh;
    }

    #signatureModal .modal-body {
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .signature-container {
        flex: 1;
        width: 100%;
        background: #fff;
        position: relative;
    }

    .signature-canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        touch-action: none;
        cursor: crosshair;
    }

    .signature-helper-text {
        font-size: 2rem;
        opacity: 0.3;
        pointer-events: none;
        z-index: 1;
    }

    .signature-container.drawing .signature-helper-text {
        display: none;
    }

    /* Modal footer positioning */
    #signatureModal .modal-footer {
        position: relative;
        z-index: 2;
        background: #fff;
    }

    /* Ensure buttons are large enough for touch */
    #signatureModal .btn {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }

    /* Government ID Cards */
    .gov-id-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .gov-id-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .tin-card::before { background: linear-gradient(to right, #2c3e50, #3498db); }
    .sss-card::before { background: linear-gradient(to right, #e74c3c, #c0392b); }
    .pagibig-card::before { background: linear-gradient(to right, #27ae60, #2ecc71); }
    .philhealth-card::before { background: linear-gradient(to right, #8e44ad, #9b59b6); }

    .gov-id-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .id-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .gov-logo {
        width: 48px;
        height: 48px;
        object-fit: contain;
    }

    .header-text {
        flex: 1;
    }

    .header-text h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2c3e50;
        line-height: 1.2;
    }

    .header-text small {
        font-size: 0.75rem;
        color: #7f8c8d;
    }

    .id-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .id-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #7f8c8d;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .id-number {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .id-number .number {
        font-family: 'Courier New', monospace;
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        letter-spacing: 1px;
        text-align: center;
        background: rgba(0,0,0,0.03);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        width: 100%;
    }

    .text-muted {
        font-size: 0.9rem;
        font-style: italic;
    }

    /* Responsive Adjustments */
    @media (max-width: 1200px) {
        .gov-id-card {
            padding: 1.25rem;
        }

        .id-number .number {
            font-size: 1.1rem;
            padding: 0.5rem 0.75rem;
        }
    }

    @media (max-width: 991px) {
        .gov-id-card {
            margin-bottom: 1rem;
        }

        .id-header {
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
        }

        .gov-logo {
            width: 40px;
            height: 40px;
        }
    }

    @media (max-width: 767px) {
        .gov-id-card {
            padding: 1rem;
        }

        .header-text h6 {
            font-size: 0.85rem;
        }

        .header-text small {
            font-size: 0.7rem;
        }

        .id-title {
            font-size: 0.8rem;
            margin-bottom: 0.75rem;
        }

        .id-number .number {
            font-size: 1rem;
            padding: 0.5rem;
        }
    }

    @media (max-width: 575px) {
        .gov-id-card {
            margin-bottom: 0.75rem;
        }

        .id-header {
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .gov-logo {
            width: 36px;
            height: 36px;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .gov-id-card {
            background: #1a202c;
        }

        .header-text h6 {
            color: #e2e8f0;
        }

        .header-text small {
            color: #a0aec0;
        }

        .id-title {
            color: #a0aec0;
        }

        .id-number .number {
            color: #e2e8f0;
            background: rgba(255,255,255,0.05);
        }

        .text-muted {
            color: #a0aec0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Add SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show initial section
        showSection('personal');
        
        // Initialize modals
        const signatureModal = new bootstrap.Modal(document.getElementById('signatureModal'), {
            keyboard: false,
            backdrop: 'static'
        });

        // Initialize components
        initializeSignatureCanvas();
        initializeProfileUpload();
        
        // Update active state of navigation pills
        const navLinks = document.querySelectorAll('#profileTabs .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Handle signature modal events
        const signatureModalEl = document.getElementById('signatureModal');
        signatureModalEl.addEventListener('shown.bs.modal', function() {
            resizeCanvas();
            setTimeout(resizeCanvas, 100); // Additional resize after modal animation
        });

        signatureModalEl.addEventListener('hidden.bs.modal', function() {
            clearSignature();
        });

        // Initialize Profile Image Modal
        const profileImageModal = new bootstrap.Modal(document.getElementById('updateProfileImageModal'), {
            keyboard: false
        });

        // Handle modal close event to reset form
        const updateProfileImageModalEl = document.getElementById('updateProfileImageModal');
        updateProfileImageModalEl.addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('profileImageForm');
            if (form) {
                form.reset();
                removeImage();
            }
        });
    });

    function showSection(sectionId) {
        // Update mobile dropdown if it exists
        const mobileSelect = document.querySelector('.custom-select');
        if (mobileSelect) {
            mobileSelect.value = sectionId;
        }

        // Hide all sections with fade out
        const sections = document.querySelectorAll('.info-section');
        sections.forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            setTimeout(() => {
                section.style.display = 'none';
            }, 300);
        });

        // Show selected section with fade in
        setTimeout(() => {
            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.style.display = 'block';
                selectedSection.offsetHeight; // Trigger reflow
                selectedSection.style.opacity = '1';
                selectedSection.style.transform = 'translateY(0)';
            }
        }, 300);

        // Update navigation pills
        const navLinks = document.querySelectorAll('#profileTabs .nav-link');
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('onclick').includes(sectionId)) {
                link.classList.add('active');
            }
        });
    }

    let canvas = null;
    let ctx = null;
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // Signature Canvas Functions
    function initializeSignatureCanvas() {
        canvas = document.getElementById('signatureCanvas');
        if (!canvas) return;

        ctx = canvas.getContext('2d');

        // Prevent scrolling on touch devices
        document.body.addEventListener('touchmove', function(e) {
            if (e.target === canvas) {
                e.preventDefault();
            }
        }, { passive: false });

        // Enhanced touch handling
        canvas.addEventListener('touchstart', handleTouchStart, { passive: false });
        canvas.addEventListener('touchmove', handleTouchMove, { passive: false });
        canvas.addEventListener('touchend', handleTouchEnd, { passive: false });

        // Mouse events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Resize handling
        window.addEventListener('resize', debounce(resizeCanvas, 250));
        
        // Initialize canvas on modal show
        const signatureModal = document.getElementById('signatureModal');
        if (signatureModal) {
            signatureModal.addEventListener('shown.bs.modal', function() {
                resizeCanvas();
                setTimeout(resizeCanvas, 100); // Additional resize after modal animation
            });
        }

        // Add drawing class for helper text
        canvas.addEventListener('mousedown', () => {
            canvas.parentElement.classList.add('drawing');
        });

        canvas.addEventListener('touchstart', () => {
            canvas.parentElement.classList.add('drawing');
        });
    }

    function handleTouchStart(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const rect = canvas.getBoundingClientRect();
        isDrawing = true;
        lastX = touch.clientX - rect.left;
        lastY = touch.clientY - rect.top;

        // Initial dot for better touch response
        ctx.beginPath();
        ctx.arc(lastX, lastY, ctx.lineWidth / 2, 0, Math.PI * 2);
        ctx.fillStyle = '#000000';
        ctx.fill();
    }

    function handleTouchMove(e) {
        e.preventDefault();
        if (!isDrawing) return;
        
        const touch = e.touches[0];
        const rect = canvas.getBoundingClientRect();
        const currentX = touch.clientX - rect.left;
        const currentY = touch.clientY - rect.top;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.strokeStyle = '#000000';
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.stroke();

        lastX = currentX;
        lastY = currentY;
    }

    function handleTouchEnd(e) {
        e.preventDefault();
        isDrawing = false;
    }

    function startDrawing(e) {
        isDrawing = true;
        const rect = canvas.getBoundingClientRect();
        lastX = e.clientX - rect.left;
        lastY = e.clientY - rect.top;
    }

    function draw(e) {
        if (!isDrawing) return;
        
        const currentX = e.clientX - canvas.getBoundingClientRect().left;
        const currentY = e.clientY - canvas.getBoundingClientRect().top;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(currentX, currentY);
        ctx.strokeStyle = '#000000';
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.stroke();

        lastX = currentX;
        lastY = currentY;
    }

    function stopDrawing() {
        isDrawing = false;
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function resizeCanvas() {
        const container = canvas.parentElement;
        canvas.width = container.offsetWidth;
        canvas.height = container.offsetHeight;
        
        // Maintain drawing settings after resize
        ctx = canvas.getContext('2d');
        ctx.strokeStyle = '#000000';
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        // Add light grid background for better visibility
        drawGrid();
    }

    function drawGrid() {
        const gridSize = 50;
        const lightColor = '#f0f0f0';

        ctx.beginPath();
        ctx.strokeStyle = lightColor;
        ctx.lineWidth = 0.5;

        // Draw vertical lines
        for (let x = 0; x <= canvas.width; x += gridSize) {
            ctx.moveTo(x, 0);
            ctx.lineTo(x, canvas.height);
        }

        // Draw horizontal lines
        for (let y = 0; y <= canvas.height; y += gridSize) {
            ctx.moveTo(0, y);
            ctx.lineTo(canvas.width, y);
        }

        ctx.stroke();

        // Add a horizontal guide line in the middle
        ctx.beginPath();
        ctx.strokeStyle = '#ccc';
        ctx.lineWidth = 1;
        ctx.moveTo(0, canvas.height / 2);
        ctx.lineTo(canvas.width, canvas.height / 2);
        ctx.stroke();

        // Reset context for signature drawing
        ctx.strokeStyle = '#000000';
        ctx.lineWidth = 3;
    }

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawGrid();
        canvas.parentElement.classList.remove('drawing'); // Show helper text again
    }

    function saveSignature() {
        // Check if user is authorized
        const userEmail = '{{ auth()->user()->email }}';
        const employeeEmail = '{{ $employee->email_address }}';

        if (userEmail !== employeeEmail) {
            Swal.fire({
                icon: 'error',
                title: 'Unauthorized',
                text: 'You are not authorized to update this signature.'
            });
            return;
        }

        // Validate if signature is empty
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const pixels = imageData.data;
        let isEmpty = true;

        for (let i = 0; i < pixels.length; i += 4) {
            if (pixels[i + 3] !== 0) {
                isEmpty = false;
                break;
            }
        }

        if (isEmpty) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please draw your signature before saving.'
            });
            return;
        }

        // Confirm if updating existing signature
        const hasExistingSignature = Boolean('{{ $employee->signature }}');
        if (hasExistingSignature) {
            Swal.fire({
                title: 'Update Signature?',
                text: 'This will replace your existing signature. Continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitSignature();
                }
            });
        } else {
            submitSignature();
        }
    }

    function submitSignature() {
        // Get signature data
        const signatureData = canvas.toDataURL('image/png');

        // Show loading state
        const saveButton = document.querySelector('[onclick="saveSignature()"]');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveButton.disabled = true;

        // Send AJAX request
        axios.post('/employee/signature', {
            signature: signatureData
        })
        .then(response => {
            if (response.data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Signature has been saved successfully.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            }
        })
        .catch(error => {
            console.error('Signature save error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.response?.data?.message || 'Failed to save signature'
            });
        })
        .finally(() => {
            // Reset button state
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        });
    }

    // Profile Upload Functions
    function initializeProfileUpload() {
        const uploadBox = document.querySelector('.upload-box');
        if (!uploadBox) return;

        const fileInput = document.getElementById('profile');
        const defaultContent = document.getElementById('defaultUploadContent');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = imagePreview.querySelector('img');
        const fileName = imagePreview.querySelector('.file-name');

        // Drag and drop handlers
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadBox.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        // Highlight effects
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadBox.addEventListener(eventName, () => uploadBox.classList.add('dragover'));
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadBox.addEventListener(eventName, () => uploadBox.classList.remove('dragover'));
        });

        // File handling
        uploadBox.addEventListener('drop', e => handleFile(e.dataTransfer.files[0]));
        fileInput.addEventListener('change', e => handleFile(e.target.files[0]));
    }

    function handleFile(file) {
        if (!file) return;

        const fileInput = document.getElementById('profile');
        const defaultContent = document.getElementById('defaultUploadContent');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = imagePreview.querySelector('img');
        const fileName = imagePreview.querySelector('.file-name');

        if (['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImage.src = e.target.result;
                fileName.textContent = file.name;
                defaultContent.style.display = 'none';
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File',
                text: 'Please upload only JPG, JPEG, or PNG files.'
            });
        }
    }

    function removeImage() {
        const fileInput = document.getElementById('profile');
        const defaultContent = document.getElementById('defaultUploadContent');
        const imagePreview = document.getElementById('imagePreview');
        
        if (fileInput) fileInput.value = '';
        if (defaultContent) defaultContent.style.display = 'block';
        if (imagePreview) imagePreview.style.display = 'none';
    }

    function updateProfileImage() {
        const formData = new FormData(document.getElementById('profileImageForm'));
        const saveButton = document.querySelector('[onclick="updateProfileImage()"]');
        const originalText = saveButton.innerHTML;
        
        // Show loading state
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        saveButton.disabled = true;

        axios.post('/employee/profile', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(response => {
            if (response.data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Profile image has been updated successfully.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            }
        })
        .catch(error => {
            console.error('Profile update error:', error);
            let errorMessage = 'Failed to update profile image';
            
            if (error.response?.data?.message) {
                errorMessage = error.response.data.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage
            });
        })
        .finally(() => {
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        });
    }
</script>
@endpush
