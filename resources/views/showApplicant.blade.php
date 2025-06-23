@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('careers.all') }}">All Applicants</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Applicant Details</li>
                </ol>
            </nav>

            <div class="card shadow">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-title h3 mb-0">{{ $career->first_name }} {{ $career->last_name }}</h2>
                        <p class="mb-0"><small>{{ $career->hiring->position ?? 'Position Not Specified' }}</small></p>
                    </div>
                    <span class="badge badge-light">Applied {{ $career->created_at->diffForHumans() }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h3 class="h5 border-bottom pb-2 mb-3">Contact Information</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-envelope text-primary mr-2"></i> {{ $career->email }}</li>
                                <li class="mb-2"><i class="fas fa-phone text-primary mr-2"></i> {{ $career->phone }}</li>
                                <li class="mb-2">
                                    <i class="fab fa-linkedin text-primary mr-2"></i>
                                    @if($career->linkedin)
                                        <a href="{{ $career->linkedin }}" target="_blank" rel="noopener noreferrer">View LinkedIn Profile</a>
                                    @else
                                        Not provided
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h3 class="h5 border-bottom pb-2 mb-3">Career Details</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-briefcase text-primary mr-2"></i> {{ $career->experience }} years of experience</li>
                                <li class="mb-2"><i class="fas fa-calendar-alt text-primary mr-2"></i> Applied on {{ $career->created_at->format('M d, Y') }}</li>
                                @if($career->interview_date)
                                    <li class="mb-2">
                                        <i class="fas fa-calendar-check text-success mr-2"></i>
                                        Interview scheduled for {{ $career->interview_date->format('M d, Y g:i A') }}
                                    </li>
                                    @if($career->interview_location)
                                        <li class="mb-2">
                                            <i class="fas fa-map-marker-alt text-success mr-2"></i>
                                            {{ $career->interview_location }}
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h3 class="h5 border-bottom pb-2 mb-3">Cover Letter</h3>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($career->cover_letter ?? 'No cover letter provided.')) !!}
                        </div>
                    </div>

                    @if($career->resume_path)
                        <div class="mt-4">
                            <h3 class="h5 border-bottom pb-2 mb-3">Resume</h3>
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-file-pdf text-danger mr-2"></i>{{ basename($career->resume_path) }}</span>
                                <a href="{{ Storage::url($career->resume_path) }}" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener noreferrer">
                                    <i class="fas fa-download mr-2"></i>Download Resume
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="mt-4">
                            <h3 class="h5 border-bottom pb-2 mb-3">Resume</h3>
                            <p class="text-muted"><i class="fas fa-exclamation-circle mr-2"></i>No resume uploaded</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('careers.all') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to All Applicants
                </a>
                <div>
                    @if(is_null($career->interview_date))
                        <button class="btn btn-success mr-2" data-toggle="modal" data-target="#scheduleInterviewModal">
                            <i class="fas fa-calendar-check mr-2"></i>Schedule Interview
                        </button>
                    @else
                        <span class="text-success"><i class="fas fa-check-circle mr-2"></i>Interview Scheduled</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Interview Modal -->
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1" role="dialog" aria-labelledby="scheduleInterviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleInterviewModalLabel">Schedule Interview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="scheduleInterviewForm" action="{{ route('careers.schedule-interview', $career->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="interview_date">Interview Date and Time</label>
                        <input type="datetime-local" class="form-control" id="interview_date" name="interview_date" required>
                    </div>
                    <div class="form-group">
                        <label for="interview_location">Interview Location (Optional)</label>
                        <input type="text" class="form-control" id="interview_location" name="interview_location" placeholder="e.g., Conference Room A or Video Call Link">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
    .card-title {
        font-size: 1.5rem;
    }
    .list-unstyled li {
        margin-bottom: 0.5rem;
    }
    .bg-light {
        background-color: #f8f9fa;
    }
    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }
</style>
@stop

@section('js')
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#scheduleInterviewForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#scheduleInterviewModal').modal('hide');
                    alert('Interview scheduled successfully!');
                    // Reload the page after successful scheduling
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error scheduling interview. Please try again.');
                }
            });
        });
    });
</script>
@stop
