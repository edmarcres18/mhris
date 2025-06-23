@extends('layouts.app')

@section('styles')
<style>
    :root {
        --primary: #6a3de8;
        --primary-dark: #5a2bd8;
        --primary-light: #e0d7fc;
        --text-dark: #333333;
        --text-light: #ffffff;
        --text-muted: #6c757d;
        --background: #f9f7ff;
        --section-bg: #ffffff;
    }
    
    .app-download-section {
        padding: 100px 0;
        background: var(--background);
        color: var(--text-dark);
    }
    
    .content-card {
        background: var(--section-bg);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(106, 61, 232, 0.08);
        padding: 40px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }
    
    .content-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(106, 61, 232, 0.12);
    }
    
    .app-mockup {
        max-width: 280px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(106, 61, 232, 0.1);
        margin-bottom: 30px;
    }
    
    .download-heading {
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--text-dark);
        font-size: 2.2rem;
    }
    
    .download-subheading {
        color: var(--text-muted);
        font-size: 1.2rem;
        margin-bottom: 30px;
        line-height: 1.6;
    }
    
    .feature-item {
        margin-bottom: 30px;
        display: flex;
        align-items: flex-start;
    }
    
    .feature-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background-color: var(--primary-light);
        color: var(--primary);
        font-size: 1.4rem;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .feature-content h5 {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }
    
    .feature-content p {
        margin-bottom: 0;
        color: var(--text-muted);
        line-height: 1.5;
    }
    
    .download-btn {
        display: inline-flex;
        align-items: center;
        background-color: var(--primary);
        color: var(--text-light);
        border-radius: 12px;
        padding: 14px 25px;
        margin-right: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        text-decoration: none;
        width: 220px;
        border: none;
    }
    
    .download-btn:hover {
        background-color: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(106, 61, 232, 0.15);
        color: var(--text-light);
        text-decoration: none;
    }
    
    .download-btn-icon {
        font-size: 1.8rem;
        margin-right: 15px;
    }
    
    .download-btn-text span {
        display: block;
    }
    
    .download-btn-text .small-text {
        font-size: 0.7rem;
        font-weight: 400;
        opacity: 0.9;
    }
    
    .download-btn-text .big-text {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .app-features {
        margin-top: 50px;
    }
    
    .info-text {
        display: flex;
        align-items: center;
        background-color: rgba(106, 61, 232, 0.08);
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        margin-bottom: 15px;
    }
    
    .info-text i {
        color: var(--primary);
        margin-right: 10px;
        font-size: 1rem;
    }
    
    .section-divider {
        width: 80px;
        height: 4px;
        background: var(--primary);
        margin: 20px 0 30px;
        border-radius: 2px;
    }
    
    @media (max-width: 767px) {
        .app-mockup-container {
            margin-bottom: 40px;
        }
        
        .app-download-section {
            padding: 60px 0;
        }
        
        .download-heading {
            font-size: 1.8rem;
        }
        
        .download-subheading {
            font-size: 1rem;
        }
        
        .download-btn {
            width: 100%;
            margin-right: 0;
            justify-content: center;
        }
        
        .content-card {
            padding: 25px;
        }
    }
    
    @media (max-width: 575px) {
        .feature-item {
            flex-direction: column;
            text-align: center;
        }
        
        .feature-icon {
            margin-bottom: 15px;
            margin-right: 0;
            margin-left: auto;
            margin-right: auto;
        }
        
        .section-divider {
            margin-left: auto;
            margin-right: auto;
        }
    }
</style>
@endsection

@section('content')
<div class="app-download-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="content-card">
                    <div class="row align-items-center">
                        <div class="col-md-5 app-mockup-container text-center">
                            <img src="{{ asset('vendor/adminlte/dist/img/ICON_APP.png') }}" alt="App Mockup" class="app-mockup img-fluid">
                            <div class="download-buttons d-md-none">
                                <a href="https://www.mediafire.com/file/app-ios-download-link" class="download-btn" target="_blank" rel="noopener noreferrer">
                                    <div class="download-btn-icon">
                                        <i class="fab fa-apple"></i>
                                    </div>
                                    <div class="download-btn-text">
                                        <span class="small-text">Download for</span>
                                        <span class="big-text">iOS</span>
                                    </div>
                                </a>
                                
                                <a href="https://www.mediafire.com/file/1nyg6w24rg0pzwp/mhrpci-hris-app-android.apk/file" class="download-btn" target="_blank" rel="noopener noreferrer">
                                    <div class="download-btn-icon">
                                        <i class="fab fa-android"></i>
                                    </div>
                                    <div class="download-btn-text">
                                        <span class="small-text">Download for</span>
                                        <span class="big-text">Android</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-7">
                            <h1 class="download-heading">HRIS Mobile App</h1>
                            <div class="section-divider"></div>
                            <p class="download-subheading">Access your HR resources anytime, anywhere with our secure and user-friendly mobile application.</p>
                            
                            <div class="download-buttons d-none d-md-block">
                                <a href="https://www.mediafire.com/file/app-ios-download-link" class="download-btn" target="_blank" rel="noopener noreferrer">
                                    <div class="download-btn-icon">
                                        <i class="fab fa-apple"></i>
                                    </div>
                                    <div class="download-btn-text">
                                        <span class="small-text">Download for</span>
                                        <span class="big-text">iOS</span>
                                    </div>
                                </a>
                                
                                <a href="https://www.mediafire.com/file/1nyg6w24rg0pzwp/mhrpci-hris-app-android.apk/file" class="download-btn" target="_blank" rel="noopener noreferrer">
                                    <div class="download-btn-icon">
                                        <i class="fab fa-android"></i>
                                    </div>
                                    <div class="download-btn-text">
                                        <span class="small-text">Download for</span>
                                        <span class="big-text">Android</span>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="info-text">
                                <i class="fas fa-info-circle"></i>
                                <p class="mb-0">Our app installation files are hosted on MediaFire for direct and secure downloading.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="content-card">
                    <h3 class="text-center mb-4">Key Features</h3>
                    <div class="section-divider mx-auto"></div>
                    
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Attendance Tracking</h5>
                                    <p>Clock in and out directly from your mobile device with geolocation support.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Request Management</h5>
                                    <p>Submit and track leave requests on the go with real-time status updates.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Push Notifications</h5>
                                    <p>Stay updated with important announcements and approval notifications.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="feature-content">
                                    <h5>Performance Dashboard</h5>
                                    <p>Track your work performance metrics and personal productivity insights.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Check viewport width and adjust layout as needed
        function checkResponsiveness() {
            var windowWidth = $(window).width();
            
            // Additional responsive adjustments if needed
            if (windowWidth < 576) {
                // Extra small device specific adjustments
            } else if (windowWidth < 768) {
                // Small device specific adjustments
            }
        }
        
        // Run on page load
        checkResponsiveness();
        
        // Run on window resize
        $(window).resize(function() {
            checkResponsiveness();
        });
    });
</script>
@endsection 