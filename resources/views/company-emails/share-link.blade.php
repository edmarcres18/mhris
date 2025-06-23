@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-primary">{{ __('Shareable Link Generated') }}</h5>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-2 fs-5"></i> 
                        <span>Shareable link has been generated successfully!</span>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded">
                        <h5 class="border-bottom pb-2 text-secondary">{{ __('Link Details') }}</h5>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-2">
                                <p><strong><i class="fas fa-user me-1"></i> Created by:</strong> {{ auth()->user()->name }}</p>
                                <p><strong><i class="fas fa-calendar me-1"></i> Created at:</strong> {{ $shareableLink->created_at->format('M d, Y h:i A') }}</p>
                                <p><strong><i class="fas fa-clock me-1"></i> Expires at:</strong> {{ $shareableLink->expires_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p><strong><i class="fas fa-info-circle me-1"></i> Description:</strong> {{ $shareableLink->description ?: 'No description provided' }}</p>
                                <p><strong><i class="fas fa-envelope me-1"></i> Number of emails:</strong> {{ $shareableLink->companyEmails->count() }}</p>
                                <p><strong><i class="fas fa-hourglass-half me-1"></i> Time remaining:</strong> <span class="badge bg-warning text-dark">{{ $shareableLink->remainingTimeInMinutes() }} minutes</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded">
                        <h5 class="border-bottom pb-2 text-secondary">{{ __('Shareable Link') }}</h5>
                        <div class="input-group mt-3">
                            <input type="text" class="form-control form-control-lg border" id="shareableLink" value="{{ route('public.shared-emails', $shareableLink->token) }}" readonly>
                            <button class="btn btn-primary" type="button" id="copyButton">
                                <i class="fas fa-copy me-1"></i> Copy
                            </button>
                        </div>
                        <div class="mt-2 text-muted">
                            <small><i class="fas fa-exclamation-circle me-1"></i> This link will expire in {{ $shareableLink->remainingTimeInMinutes() }} minutes.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 text-secondary">{{ __('Company Emails Included') }}</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="5%">#</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shareableLink->companyEmails as $index => $email)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td><i class="fas fa-envelope me-1 text-muted"></i> {{ $email->email }}</td>
                                            <td><i class="fas fa-key me-1 text-muted"></i> {{ $email->password }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-between mt-4">
                        <a href="{{ route('company-emails.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Company Emails') }}
                        </a>
                        <a href="{{ route('company-emails.shareable-links') }}" class="btn btn-primary">
                            <i class="fas fa-link me-1"></i> {{ __('View All Shareable Links') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#copyButton').click(function() {
            var linkInput = document.getElementById('shareableLink');
            linkInput.select();
            document.execCommand('copy');
            
            $(this).removeClass('btn-primary').addClass('btn-success');
            $(this).html('<i class="fas fa-check me-1"></i> Copied!');
            
            setTimeout(function() {
                $('#copyButton').removeClass('btn-success').addClass('btn-primary');
                $('#copyButton').html('<i class="fas fa-copy me-1"></i> Copy');
            }, 2000);
        });
    });
</script>
@endsection
@endsection 