@extends('layouts.profile')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
<style>
    .profile-stepper {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .bs-stepper .line {
        background-color: var(--border-color);
    }

    .bs-stepper-header {
        border-bottom: 1px solid var(--border-color);
        padding: 1rem;
    }

    .step-trigger {
        padding: 1rem !important;
    }

    .bs-stepper-circle {
        width: 2.5rem;
        height: 2.5rem;
        background: var(--primary-light);
        color: var(--primary-color);
        font-weight: 600;
        border: 2px solid var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .active .bs-stepper-circle {
        background: var(--primary-color);
        color: white;
    }

    .bs-stepper-label {
        font-weight: 500;
        margin-top: 0.5rem;
        color: var(--text-muted);
    }

    .active .bs-stepper-label {
        color: var(--primary-color);
    }

    .form-control, .form-select {
        border-color: var(--border-color);
        padding: 0.75rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem var(--primary-light);
    }

    .form-label {
        font-weight: 500;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .password-toggle {
        cursor: pointer;
        padding: 0.75rem 1rem;
        color: var(--text-muted);
        transition: all var(--transition-speed);
    }

    .password-toggle:hover {
        color: var(--primary-color);
    }

    .signature-preview {
        max-width: 100%;
        border: 2px dashed var(--border-color);
        padding: 1.5rem;
        border-radius: 0.5rem;
        background: white;
    }

    .signature-actions {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    .btn-nav {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }

    .btn-nav i {
        transition: transform var(--transition-speed);
    }

    .btn-nav:hover i.fa-arrow-right {
        transform: translateX(4px);
    }

    .btn-nav:hover i.fa-arrow-left {
        transform: translateX(-4px);
    }

    @media (max-width: 767.98px) {
        .bs-stepper-header {
            flex-direction: column;
            align-items: flex-start;
            padding: 0.5rem;
        }
        
        .step:not(:last-child) {
            margin-bottom: 1rem;
        }

        .bs-stepper .line {
            display: none;
        }

        .step-trigger {
            padding: 0.5rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid fade-in">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="profile-stepper">
                <div id="stepper" class="bs-stepper">
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#personal-info">
                            <button type="button" class="step-trigger" role="tab" aria-controls="personal-info" id="personal-info-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label d-none d-sm-inline">Personal Info</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#account-info">
                            <button type="button" class="step-trigger" role="tab" aria-controls="account-info" id="account-info-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label d-none d-sm-inline">Account Info</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#profile-image">
                            <button type="button" class="step-trigger" role="tab" aria-controls="profile-image" id="profile-image-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label d-none d-sm-inline">Profile & Bio</span>
                            </button>
                        </div>
                    </div>

                    <div class="bs-stepper-content p-4">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Personal Info Step -->
                            <div id="personal-info" class="content" role="tabpanel" aria-labelledby="personal-info-trigger">
                                <h4 class="mb-4">Personal Information</h4>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="company_id">Company ID</label>
                                            <input type="text" id="company_id" name="company_id" 
                                                   value="{{ old('company_id', $user->company_id) }}" 
                                                   class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="first_name">First Name</label>
                                            <input type="text" id="first_name" name="first_name" 
                                                   value="{{ old('first_name', $user->first_name) }}" 
                                                   class="form-control @error('first_name') is-invalid @enderror" 
                                                   placeholder="Enter first name" required>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="middle_name">Middle Name</label>
                                            <input type="text" id="middle_name" name="middle_name" 
                                                   value="{{ old('middle_name', $user->middle_name) }}" 
                                                   class="form-control @error('middle_name') is-invalid @enderror" 
                                                   placeholder="Enter middle name (optional)">
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="last_name">Last Name</label>
                                            <input type="text" id="last_name" name="last_name" 
                                                   value="{{ old('last_name', $user->last_name) }}" 
                                                   class="form-control @error('last_name') is-invalid @enderror" 
                                                   placeholder="Enter last name" required>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="suffix">Suffix</label>
                                            <input type="text" id="suffix" name="suffix" 
                                                   value="{{ old('suffix', $user->suffix) }}" 
                                                   class="form-control @error('suffix') is-invalid @enderror" 
                                                   placeholder="Enter suffix (optional)">
                                            @error('suffix')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary btn-nav" onclick="stepper.next()">
                                        Next <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Account Info Step -->
                            <div id="account-info" class="content" role="tabpanel" aria-labelledby="account-info-trigger">
                                <h4 class="mb-4">Account Information</h4>
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="email">Email Address</label>
                                            <input type="email" id="email" name="email" 
                                                   value="{{ old('email', $user->email) }}" 
                                                   class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="password">New Password</label>
                                            <div class="input-group">
                                                <input type="password" id="password" name="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       placeholder="Enter new password (optional)">
                                                <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('password', this)">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                                       class="form-control" 
                                                       placeholder="Confirm new password">
                                                <span class="input-group-text password-toggle" onclick="togglePasswordVisibility('password_confirmation', this)">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-nav" onclick="stepper.previous()">
                                        <i class="fas fa-arrow-left me-2"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary btn-nav" onclick="stepper.next()">
                                        Next <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Profile Image & Bio Step -->
                            <div id="profile-image" class="content" role="tabpanel" aria-labelledby="profile-image-trigger">
                                <h4 class="mb-4">Profile Image & Biography</h4>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Profile Image</label>
                                            @if($user->profile_image)
                                                <div class="position-relative mb-3">
                                                    <img src="{{ Storage::url($user->profile_image) }}" 
                                                         alt="Current Profile Image" 
                                                         class="img-fluid rounded" 
                                                         style="max-width: 200px;">
                                                </div>
                                            @endif
                                            <input type="file" name="profile_image" 
                                                   class="form-control @error('profile_image') is-invalid @enderror" 
                                                   accept="image/*">
                                            @error('profile_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="bio">Biography</label>
                                            <textarea id="bio" name="bio" 
                                                      class="form-control @error('bio') is-invalid @enderror" 
                                                      rows="4" 
                                                      placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                            @error('bio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if(!auth()->user()->hasRole('Employee'))
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-signature me-2"></i>Digital Signature
                                                </h5>
                                                @if(!$user->signature)
                                                    <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#signatureModal">
                                                        <i class="fas fa-plus me-1"></i> Add Signature
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                @if($user->signature)
                                                    <div class="text-center">
                                                        <div class="signature-preview mb-3">
                                                            <img src="{{ Storage::url($user->signature) }}"
                                                                 alt="User Signature"
                                                                 class="img-fluid"
                                                                 style="max-height: 100px;">
                                                        </div>
                                                        <div class="text-muted small mb-3">
                                                            Last updated: {{ \Carbon\Carbon::parse($user->updated_at)->format('F d, Y h:i A') }}
                                                        </div>
                                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#signatureModal">
                                                            <i class="fas fa-edit me-1"></i> Update Signature
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <div class="text-muted mb-3">
                                                            <i class="fas fa-signature fa-2x"></i>
                                                            <p class="mt-2">No signature has been uploaded yet.</p>
                                                        </div>
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#signatureModal">
                                                            <i class="fas fa-plus me-1"></i> Add Your Signature
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-nav" onclick="stepper.previous()">
                                        <i class="fas fa-arrow-left me-2"></i> Previous
                                    </button>
                                    <button type="submit" class="btn btn-success btn-nav">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Signature Modal -->
<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">
                    {{ $user->signature ? 'Update Your Signature' : 'Add Your Signature' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($user->signature)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your existing signature will be replaced when you save a new one.
                    </div>
                @endif
                <canvas id="signatureCanvas" class="border rounded w-100" height="200"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="clearSignature()">
                    <i class="fas fa-eraser me-1"></i> Clear
                </button>
                <button type="button" class="btn btn-primary" onclick="saveSignature()">
                    <i class="fas fa-save me-1"></i> 
                    {{ $user->signature ? 'Update Signature' : 'Save Signature' }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
<script>
    $(document).ready(function() {
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

        window.stepper = new Stepper(document.querySelector('#stepper'), {
            animation: true
        });

        function capitalizeInput(event) {
            event.target.value = event.target.value.toUpperCase();
        }

        const inputsToCapitalize = [
            'first_name',
            'middle_name',
            'last_name'
        ];

        inputsToCapitalize.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', capitalizeInput);
            }
        });

        // Add form submission handler
        $('form').on('submit', function(e) {
            e.preventDefault();

            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...').prop('disabled', true);

            // Submit form
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        ...toastConfig,
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile updated successfully',
                        background: '#28a745',
                        color: '#fff'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        ...toastConfig,
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Failed to update profile',
                        background: '#dc3545',
                        color: '#fff'
                    });
                },
                complete: function() {
                    submitBtn.html(originalText).prop('disabled', false);
                }
            });
        });
    });

    function togglePasswordVisibility(inputId, icon) {
        const input = document.getElementById(inputId);
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;

        // Toggle the icon class
        icon.querySelector('i').classList.toggle('fa-eye');
        icon.querySelector('i').classList.toggle('fa-eye-slash');
    }

    let canvas = null;
    let ctx = null;
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    document.addEventListener('DOMContentLoaded', function() {
        canvas = document.getElementById('signatureCanvas');
        ctx = canvas.getContext('2d');

        // Set up canvas drawing events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Set up touch events for mobile
        canvas.addEventListener('touchstart', handleTouch);
        canvas.addEventListener('touchmove', handleTouch);
        canvas.addEventListener('touchend', stopDrawing);
    });

    function startDrawing(e) {
        isDrawing = true;
        [lastX, lastY] = [e.offsetX, e.offsetY];
    }

    function draw(e) {
        if (!isDrawing) return;

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.stroke();

        [lastX, lastY] = [e.offsetX, e.offsetY];
    }

    function stopDrawing() {
        isDrawing = false;
    }

    function handleTouch(e) {
        e.preventDefault();
        const touch = e.touches[0];
        const rect = canvas.getBoundingClientRect();
        const offsetX = touch.clientX - rect.left;
        const offsetY = touch.clientY - rect.top;

        const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 'mousemove', {
            clientX: touch.clientX,
            clientY: touch.clientY
        });

        canvas.dispatchEvent(mouseEvent);
    }

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function saveSignature() {
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
                text: 'Please draw your signature before saving.',
                background: '#dc3545',
                color: '#fff'
            });
            return;
        }

        // Confirm if updating existing signature
        const hasExistingSignature = {{ $user->signature ? 'true' : 'false' }};
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
        const signatureData = canvas.toDataURL('image/png');
        const saveButton = document.querySelector('[onclick="saveSignature()"]');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        saveButton.disabled = true;

        axios.post('/profile/signature', {
            signature: signatureData
        })
        .then(response => {
            if (response.data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Signature has been saved successfully.',
                    timer: 2000,
                    showConfirmButton: false,
                    background: '#28a745',
                    color: '#fff'
                }).then(() => {
                    location.reload();
                });
                $('#signatureModal').modal('hide');
            }
        })
        .catch(error => {
            console.error('Signature save error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.response?.data?.message || 'Failed to save signature',
                background: '#dc3545',
                color: '#fff'
            });
        })
        .finally(() => {
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        });
    }
</script>
@endpush
