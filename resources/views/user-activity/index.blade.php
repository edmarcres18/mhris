@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xxl-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> User Activities</h5>
                </div>

                <div class="card-body">
                    <div class="alert alert-light border-start border-primary border-4 mb-4" role="alert">
                        <div class="d-flex align-items-start flex-column flex-sm-row">
                            <i class="fas fa-info-circle me-sm-3 mb-2 mb-sm-0 fs-4 text-primary"></i>
                            <div>
                                <h6 class="fw-bold mb-1">About This Feature</h6>
                                <p class="mb-0 text-muted">
                                    Monitor and track actions performed by users within your department as a Coordinator or Supervisor.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center py-3 py-md-4">
                        <i class="fas fa-tools fa-3x text-primary mb-2 mb-md-3 opacity-75"></i>
                        <h4 class="fw-bold mb-2 mb-md-3">Feature Coming Soon</h4>
                        <p class="text-muted mb-3 mb-md-4">We're working on bringing you comprehensive activity tracking capabilities.</p>
                        
                        <div class="row g-3 g-md-4">
                            @foreach([
                                ['icon' => 'fa-user-clock', 'title' => 'Real-time Tracking', 'description' => 'Monitor user actions as they happen'],
                                ['icon' => 'fa-filter', 'title' => 'Advanced Filtering', 'description' => 'Filter by user, action type, and date'],
                                ['icon' => 'fa-chart-line', 'title' => 'Activity Analytics', 'description' => 'Visual reports and activity trends']
                            ] as $feature)
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="feature-box p-3 p-md-4 text-center h-100">
                                    <i class="fas {{ $feature['icon'] }} text-primary mb-2 mb-md-3"></i>
                                    <h6 class="fw-bold">{{ $feature['title'] }}</h6>
                                    <p class="text-muted small mb-0">{{ $feature['description'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Card Styles */
    .card {
        background: #ffffff;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        margin-bottom: 24px;
    }

    .card-header {
        padding: 20px 24px;
        background: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 16px 16px 0 0;
    }

    .card-body {
        padding: 24px;
    }

    /* Alert Styles */
    .alert {
        padding: 20px;
        background: #f8f9fa;
        border-left: 4px solid #0d6efd;
        margin-bottom: 24px;
    }

    .alert i {
        font-size: 24px;
        color: #0d6efd;
        margin-right: 16px;
    }

    /* Feature Box Styles */
    .feature-box {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 12px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }

    .feature-box:hover {
        background: #ffffff;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    .feature-box i {
        font-size: 28px;
        color: #0d6efd;
        margin-bottom: 16px;
        opacity: 0.9;
    }

    .feature-box h6 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .feature-box p {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
        line-height: 1.5;
    }

    /* Utility Classes */
    .mb-0 { margin-bottom: 0; }
    .py-4 { padding-top: 24px; padding-bottom: 24px; }
    .text-primary { color: #0d6efd; }
    .text-muted { color: #6c757d; }
    .text-center { text-align: center; }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 16px;
            padding-right: 16px;
        }

        .card {
            border-radius: 12px;
        }

        .card-header {
            padding: 16px 20px;
        }

        .card-body {
            padding: 20px;
        }

        .feature-box {
            padding: 20px;
            border-radius: 10px;
        }

        .feature-box i {
            font-size: 24px;
            margin-bottom: 12px;
        }

        .feature-box h6 {
            font-size: 15px;
            margin-bottom: 8px;
        }

        .feature-box p {
            font-size: 13px;
        }

        .py-4 {
            padding-top: 16px;
            padding-bottom: 16px;
        }
    }
</style>
@endpush