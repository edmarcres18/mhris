@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-calendar-check me-2"></i> Attendance Details
                        </h3>
                        @if(Auth::user()->hasRole('Super Admin'))
                        <a href="{{ route('attendances.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                        @else
                        <a href="{{ url('/my-timesheet') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                        @endif
                    </div>
                </div>
                <!-- /.card-header -->
                @php
                    use Carbon\Carbon;
                @endphp
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card mb-4 h-100 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Employee Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="text-muted small">Employee ID</label>
                                        <p class="font-weight-bold">{{ $attendance->employee->company_id }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Name</label>
                                        <p class="font-weight-bold">{{ $attendance->employee->last_name }} {{ $attendance->employee->first_name }}, {{ $attendance->employee->middle_name ?? ' ' }} {{ $attendance->employee->suffix ?? ' ' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Date</label>
                                        <p class="font-weight-bold">{{ Carbon::parse($attendance->date_attended)->format('F j, Y') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Remarks</label>
                                        <p class="font-weight-bold">{{ $attendance->remarks ?? 'No remarks' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        @if($attendance->remarks == 'Late')
                                        <label class="text-muted small">Late Time</label>
                                        <p class="font-weight-bold">{{ $attendance->late_time }} hours/minutes</p>
                                        @elseif($attendance->remarks == 'UnderTime')
                                        <label class="text-muted small">Under Time</label>
                                        <p class="font-weight-bold">{{ $attendance->under_time }} hours/minutes</p>
                                        @elseif($attendance->remarks == 'On Leave')
                                        <label class="text-muted small">Unpaid Leave Time</label>
                                        <p class="font-weight-bold">{{ $attendance->unpaid_leave_time }} hours/minutes</p>
                                        @elseif($attendance->remarks == 'Overtime')
                                        <label class="text-muted small">Overtime</label>
                                        <p class="font-weight-bold">{{ $attendance->overtime_hours }} hours/minutes</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card mb-4 h-100 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Attendance Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="text-muted small">Time In</label>
                                            <p class="font-weight-bold">{{ $attendance->time_in ? date('h:i A', strtotime($attendance->time_in)) : '--:-- --' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="text-muted small">Time Out</label>
                                            <p class="font-weight-bold">{{ $attendance->time_out ? date('h:i A', strtotime($attendance->time_out)) : '--:-- --' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="text-muted small">Time In Address</label>
                                            <p class="font-weight-bold">{{ $attendance->time_in_address ?? 'Address not recorded' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="text-muted small">Time Out Address</label>
                                            <p class="font-weight-bold">{{ $attendance->time_out_address ?? 'Address not recorded' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="text-muted small">Working Hours</label>
                                            <p class="font-weight-bold">{{ $attendance->hours_worked ?? '0.00' }} hours</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Time Stamps</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 text-center mb-4">
                                            <div class="card h-100">
                                                <div class="card-header">Time In Stamp</div>
                                                <div class="card-body">
                                                    @if($attendance->time_stamp1)
                                                    <img src="{{ asset('storage/' . $attendance->time_stamp1) }}" alt="Time Stamp In" class="img-thumbnail" style="max-height: 200px; cursor: pointer;">
                                                    @else
                                                    <div class="alert alert-secondary">No time stamp available</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 text-center mb-4">
                                            <div class="card h-100">
                                                <div class="card-header">Time Out Stamp</div>
                                                <div class="card-body">
                                                    @if($attendance->time_stamp2)
                                                    <img src="{{ asset('storage/' . $attendance->time_stamp2) }}" alt="Time Stamp Out" class="img-thumbnail" style="max-height: 200px; cursor: pointer;">
                                                    @else
                                                    <div class="alert alert-secondary">No time stamp available</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list me-1"></i>Back to List
                            </a>
                            @canany(['Super Admin', 'HR Comben'])
                            <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            @endcanany
                            <a href="#" class="btn btn-success" onclick="window.print()">
                                <i class="fas fa-print me-1"></i>Print
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Time Stamp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center d-flex align-items-center justify-content-center" style="background-color: rgba(0,0,0,0.9);">
        <img src="" alt="Time Stamp" id="modalImage" class="img-fluid" style="max-height: 95vh; object-fit: contain;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="toggleFullscreen">
          <i class="fas fa-expand"></i> Toggle Fullscreen
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var imageModal = document.getElementById('imageModal');
        var modalImage = document.getElementById('modalImage');
        
        // Add click handlers for thumbnail images to go fullscreen directly
        document.querySelectorAll('.img-thumbnail').forEach(function(img) {
            img.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent modal from opening
                if (!document.fullscreenElement) {
                    if (img.requestFullscreen) {
                        img.requestFullscreen();
                    } else if (img.webkitRequestFullscreen) {
                        img.webkitRequestFullscreen();
                    } else if (img.msRequestFullscreen) {
                        img.msRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    }
                }
            });
        });

        imageModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var imageSrc = button.getAttribute('data-image');
            var imageTitle = button.getAttribute('data-title');
            
            var modalTitle = imageModal.querySelector('.modal-title');
            modalImage.src = imageSrc;
            modalTitle.textContent = imageTitle;
        });

        // Handle fullscreen toggle
        document.getElementById('toggleFullscreen').addEventListener('click', function() {
            if (!document.fullscreenElement) {
                if (modalImage.requestFullscreen) {
                    modalImage.requestFullscreen();
                } else if (modalImage.webkitRequestFullscreen) {
                    modalImage.webkitRequestFullscreen();
                } else if (modalImage.msRequestFullscreen) {
                    modalImage.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        });
    });
</script>
@endsection

@section('css')
<style>
    .card {
        transition: all 0.3s ease;
    }
    
    .img-thumbnail {
        transition: transform 0.3s ease;
    }
    
    .img-thumbnail:hover {
        transform: scale(1.05);
    }
    
    label.text-muted {
        display: block;
        margin-bottom: 0.2rem;
    }
    
    p.font-weight-bold {
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    #modalImage {
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    #modalImage:hover {
        transform: scale(1.02);
    }
    
    @media print {
        .btn, .card-footer {
            display: none !important;
        }
    }
    
    @media (max-width: 767.98px) {
        .card-title {
            font-size: 1.2rem;
        }
    }
</style>
@endsection