<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name') }} - Employee ID Card</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --card-width: 54mm;    /* Standard ID card width in portrait */
            --card-height: 85.6mm; /* Standard ID card height in portrait */
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e67e22;
            --background-color: #ecf0f1;
            --text-dark: #333333;
            --text-light: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Times New Roman', serif;
        }

        body {
            background: var(--background-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            gap: 20px;
            flex-wrap: wrap;
            @media screen and (max-width: 768px) {
                padding: 10px;
            }
        }

        .id-card-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .id-card {
            width: var(--card-width);
            height: var(--card-height);
            position: relative;
            @media screen and (max-width: 768px) {
                transform: scale(0.9);
                margin: -10px;
            }
            @media screen and (max-width: 480px) {
                transform: scale(0.8);
                margin: -20px;
            }
        }

        .card-side {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-front {
            background: linear-gradient(135deg, 
                {{ match($employee->department->name) {
                    'Marketing Department' => '#6495ED 0%, #6495ED 50%, #4169E1 50%, #4169E1 65%, #ffffff 65%, #ffffff 67%, #4169E1 67%, #4169E1 100%',
                    'VHI' => '#228B22 0%, #228B22 50%, #32CD32 50%, #32CD32 65%, #ffffff 65%, #ffffff 67%, #32CD32 67%, #32CD32 100%',
                    'BGPDI' => '#4169E1 0%, #4169E1 50%, #FFD700 50%, #FFD700 65%, #ffffff 65%, #ffffff 67%, #FFD700 67%, #FFD700 100%',
                    default => '#9370DB 0%, #9370DB 50%, #4A6FA5 50%, #4A6FA5 65%, #ffffff 65%, #ffffff 67%, #4A6FA5 67%, #4A6FA5 100%'
                } }}
            );
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .card-front::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 40%;
            height: 40%;
            background: linear-gradient(135deg, transparent 50%, rgba(255, 255, 255, 0.1) 50%);
            transform: rotate(-15deg);
        }

        .card-front::after {
            content: '';
            position: absolute;
            bottom: -5%;
            left: -5%;
            width: 30%;
            height: 30%;
            background: linear-gradient(45deg, transparent 50%, rgba(74, 111, 165, 0.3) 50%);
            transform: rotate(15deg);
        }

        .card-back {
            background: white;
            padding: 10px;
        }

        .card-header {
            padding: 8px;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 2px solid var(--accent-color);
        }

        .company-logo {
            height: 60px;
            width: auto;
            margin-bottom: 4px;
            filter: brightness(1.2) contrast(1.1);
            max-width: 95%;
        }

        .card-title {
            font-size: 7px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .profile-section {
            padding: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .profile-image {
            width: 75px;
            height: 95px;
            object-fit: cover;
            border: 2px solid var(--accent-color);
            border-radius: 4px;
        }

        .profile-info {
            width: 100%;
            text-align: center;
            background: rgba(0, 0, 0, 0.6);
            padding: 8px 6px;
            border-radius: 4px;
        }

        .employee-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: #ffffff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            line-height: 1.3;
            word-wrap: break-word;
            max-width: 100%;
            padding: 0 4px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 6px;
        }

        .employee-details {
            font-size: 7px;
            line-height: 1.3;
            color: #ffffff;
            margin-top: 4px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            padding: 0 3px;
            flex-wrap: wrap;
        }

        .detail-label {
            color: #ffffff;
            font-weight: bold;
            margin-right: 4px;
        }

        .detail-item span:last-child {
            text-align: right;
            flex: 1;
            min-width: 50%;
            word-break: break-word;
        }

        .card-footer {
            margin-top: auto;
            padding: 6px;
            text-align: center;
            font-size: 7px;
            background: rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .back-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding: 8px;
        }

        .back-header {
            text-align: center;
            color: var(--primary-color);
            padding: 6px;
            border-bottom: 1px solid var(--accent-color);
            font-size: 8px;
        }

        .back-section {
            font-size: 7px;
            padding: 4px;
        }

        .back-section-title {
            color: {{ match($employee->department->name) {
                'MHRHCI' => '#4169E1',
                'VHI' => '#006400',
                'BGPDI' => '#0000FF',
                default => '#663399'
            } }};
            font-weight: bold;
            margin-bottom: 3px;
            text-align: left;
            text-transform: uppercase;
            font-size: 6px;
        }

        .back-header h3 {
            color: {{ match($employee->department->name) {
                'MHRHCI' => '#4169E1',
                'VHI' => '#006400',
                'BGPDI' => '#0000FF',
                default => '#663399'
            } }};
            font-weight: bold;
        }

        .back-section .detail-label {
            color: {{ match($employee->department->name) {
                'MHRHCI' => '#4169E1',
                'VHI' => '#006400',
                'BGPDI' => '#0000FF',
                default => '#663399'
            } }};
            font-weight: bold;
        }

        .emergency-contact {
            padding: 8px;
            font-size: 7px;
        }

        .emergency-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 4px;
            text-align: center;
        }

        .signature-section {
            margin-top: auto;
            text-align: center;
            padding: 8px;
            border-top: 1px solid var(--accent-color);
            font-size: 6px;
        }

        .signature-section .detail-label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
            color: var(--primary-color);
            letter-spacing: 0.5px;
        }

        .signature-image {
            max-width: 80px;
            height: auto;
            margin: 4px auto;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 16px;
            opacity: 0.1;
            pointer-events: none;
            white-space: nowrap;
        }

        @media print {
            body { margin: 0; padding: 0; }
            .id-card-container {
                width: var(--card-width);
                height: var(--card-height);
            }
        }

        .download-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .download-btn {
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .download-btn:hover {
            background: var(--secondary-color);
        }

        @media print {
            .download-buttons {
                display: none;
            }
        }

        .flip-card {
            position: relative;
            width: var(--card-width);
            height: var(--card-height);
            perspective: 1000px;
            cursor: pointer;
        }

        .flip-card-inner {
            position: absolute;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
        }

        .flip-card-back {
            transform: rotateY(180deg);
        }

        .flip-card.flipped .flip-card-inner {
            transform: rotateY(180deg);
        }

        @media (max-width: 768px) {
            .flip-card {
                touch-action: none;
            }
            
            .flip-instruction {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 14px;
                z-index: 1000;
                pointer-events: none;
            }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #authModal {
            backdrop-filter: blur(5px);
        }

        #authModal input:focus {
            box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
        }

        #authModal button[type="submit"]:hover {
            background: var(--secondary-color);
        }

        #authModal button[type="button"]:hover {
            background: #f5f5f5;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
</head>
<body>
    <div class="id-card-container">
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <div class="card-side card-front">
                        <div class="card-header">
                            @if($employee->department->name == 'Marketing Department')
                                <img src="{{ asset('vendor/adminlte/dist/img/mhrhci.png') }}" alt="MHRHCI Logo" class="company-logo">
                            @elseif($employee->department->name == 'VHI')
                                <img src="{{ asset('vendor/adminlte/dist/img/vhi.png') }}" alt="VHI Logo" class="company-logo">
                            @elseif($employee->department->name == 'BGPDI')
                                <img src="{{ asset('vendor/adminlte/dist/img/bgpdi.png') }}" alt="BGPDI Logo" class="company-logo">
                            @else
                                <img src="{{ asset('vendor/adminlte/dist/img/whiteLOGO4.png') }}" alt="Company Logo" class="company-logo">
                            @endif
                            <div class="card-title">Employee Identification Card</div>
                        </div>

                        <div class="profile-section">
                            <img src="{{ $employee->profile ? Storage::url($employee->profile) : asset('images/default-profile.png') }}"
                                 alt="Employee Photo" class="profile-image">
                            
                            <div class="profile-info">
                                <div class="employee-name">
                                    {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }} {{ $employee->suffix }}
                                </div>
                                <div class="employee-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Employee ID:</span>
                                        <span>{{ $employee->company_id }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Position:</span>
                                        <span>{{ $employee->position->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Department:</span>
                                        <span>{{ $employee->department->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Date Hired:</span>
                                        <span>{{ \Carbon\Carbon::parse($employee->date_hired)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            
                        </div>
                    </div>
                </div>
                <div class="flip-card-back">
                    <div class="card-side card-back">
                        <div class="back-content">
                            <div class="back-header">
                                <h3>EMPLOYEE INFORMATION</h3>
                            </div>

                            <div class="back-section">
                                <div class="back-section-title">Personal Details</div>
                                <div class="detail-item">
                                    <span class="detail-label">Birth Date:</span>
                                    <span>{{ \Carbon\Carbon::parse($employee->birth_date)->format('M d, Y') ?? ' ' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">SSS No:</span>
                                    <span>{{ $employee->sss_no ?? ' ' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">TIN No:</span>
                                    <span>{{ $employee->tin_no ?? ' ' }}</span>
                                </div>
                            </div>

                            <div class="back-section">
                                <div class="back-section-title">Emergency Contact</div>
                                <div class="detail-item">
                                    <span class="detail-label">Name:</span>
                                    <span>{{ $employee->emergency_name }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Contact No:</span>
                                    <span>{{ $employee->emergency_no }}</span>
                                </div>
                            </div>

                            <div class="back-section">
                                <div class="back-section-title">Company Information</div>
                                <div class="detail-item">
                                    <span class="detail-label">Name:</span>
                                    <span>{{ config('app.company_name') }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Address:</span>
                                    <span>{{ config('app.company_address') }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">City:</span>
                                    <span>{{ config('app.company_city') }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">ZIP:</span>
                                    <span>{{ config('app.company_zip') }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Phone:</span>
                                    <span>{{ config('app.company_phone') }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Email:</span>
                                    <span>{{ config('app.company_email') }}</span>
                                </div>
                            </div>

                            <div class="signature-section">
                                <div class="detail-label">Employee Signature</div>
                                @if($employee->signature)
                                    <img src="{{ Storage::url($employee->signature) }}" 
                                         alt="Employee Signature" 
                                         class="signature-image">
                                @else
                                    <div style="color: #666; font-size: 8px;">No signature available</div>
                                @endif
                                <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                                    <div style="font-size: 6px;">
                                        This ID card remains the property of {{ config('app.name') }}.<br>
                                        If found, please return to the nearest office.
                                    </div>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data={{ route('employees.public', $employee->slug) }}" 
                                         alt="QR Code" 
                                         style="width: 20px; height: 20px; margin-left: 4px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="download-buttons">
        <button class="download-btn" onclick="showAuthForm()">Download Secure ID Card</button>
    </div>

    <div class="flip-instruction">Tap card to flip</div>

    <!-- Enhanced Authentication Modal -->
    <div id="authModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1001; opacity: 0; transition: opacity 0.3s ease;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; width: 360px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <!-- Close button -->
            <button onclick="closeAuthModal()" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 20px; cursor: pointer; color: #666; padding: 5px; line-height: 1;">×</button>
            
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 25px;">
                <h3 style="color: var(--primary-color); font-size: 1.5em; margin: 0 0 10px 0;">Secure Download</h3>
                <p style="color: #666; font-size: 0.9em; margin: 0;">Please authenticate to download the ID card</p>
            </div>

            <!-- Form -->
            <form id="authForm" onsubmit="validateUser(event)">
                <!-- Email Field -->
                <div style="margin-bottom: 20px;">
                    <label for="email" style="display: block; margin-bottom: 8px; color: #444; font-size: 0.9em; font-weight: 500;">Email Address</label>
                    <div style="position: relative;">
                        <input type="email" 
                               id="email" 
                               required 
                               style="width: 100%; 
                                      padding: 12px 15px 12px 40px; 
                                      border: 2px solid #e1e1e1; 
                                      border-radius: 8px; 
                                      font-size: 0.95em; 
                                      transition: all 0.3s ease;
                                      outline: none;"
                               onFocus="this.style.borderColor='var(--primary-color)'"
                               onBlur="this.style.borderColor='#e1e1e1'">
                        <!-- Email Icon -->
                        <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: #666;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <!-- Password Field -->
                <div style="margin-bottom: 25px;">
                    <label for="password" style="display: block; margin-bottom: 8px; color: #444; font-size: 0.9em; font-weight: 500;">Password</label>
                    <div style="position: relative;">
                        <input type="password" 
                               id="password" 
                               required 
                               style="width: 100%; 
                                      padding: 12px 15px 12px 40px; 
                                      border: 2px solid #e1e1e1; 
                                      border-radius: 8px; 
                                      font-size: 0.95em; 
                                      transition: all 0.3s ease;
                                      outline: none;"
                               onFocus="this.style.borderColor='var(--primary-color)'"
                               onBlur="this.style.borderColor='#e1e1e1'">
                        <!-- Lock Icon -->
                        <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: #666;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px;">
                    <button type="button" 
                            onclick="closeAuthModal()" 
                            style="flex: 1;
                                   padding: 12px;
                                   border: 2px solid #e1e1e1;
                                   background: white;
                                   color: #666;
                                   border-radius: 8px;
                                   cursor: pointer;
                                   font-weight: 500;
                                   transition: all 0.3s ease;">Cancel</button>
                    <button type="submit" 
                            style="flex: 2;
                                   padding: 12px;
                                   border: none;
                                   background: var(--primary-color);
                                   color: white;
                                   border-radius: 8px;
                                   cursor: pointer;
                                   font-weight: 500;
                                   transition: all 0.3s ease;">Authenticate & Download</button>
                </div>

                <!-- Loading Indicator (hidden by default) -->
                <div id="loadingIndicator" 
                     style="display: none;
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(255,255,255,0.9);
                            border-radius: 12px;
                            justify-content: center;
                            align-items: center;">
                    <div style="text-align: center;">
                        <div style="border: 3px solid #f3f3f3;
                                   border-top: 3px solid var(--primary-color);
                                   border-radius: 50%;
                                   width: 30px;
                                   height: 30px;
                                   margin: 0 auto 10px;
                                   animation: spin 1s linear infinite;"></div>
                        <p style="color: var(--primary-color); margin: 0;">Authenticating...</p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        const rateLimiter = {
            lastDownload: 0,
            minInterval: 5000, // 5 seconds
            check() {
                const now = Date.now();
                if (now - this.lastDownload < this.minInterval) {
                    return false;
                }
                this.lastDownload = now;
                return true;
            }
        };

        async function captureCard(element) {
            return await html2canvas(element, {
                scale: 4,
                useCORS: true,
                logging: false,
                backgroundColor: null,
                quality: 1.0
            });
        }

        async function downloadSecureIdCard() {
            if (!rateLimiter.check()) {
                alert('Please wait a few seconds before downloading again.');
                return;
            }

            try {
                // First, get the download token
                const tokenResponse = await fetch(`/employees/{{ $employee->slug }}/secure-download`);
                const tokenData = await tokenResponse.json();

                if (!tokenData.success) {
                    throw new Error(tokenData.message);
                }

                // Capture card sides
                const frontElement = document.querySelector('.flip-card-front');
                const backElement = document.querySelector('.flip-card-back');
                
                // Setup for capturing back side
                const backElementTransform = backElement.style.transform;
                const backElementParent = backElement.parentElement;
                const parentTransform = backElementParent.style.transform;
                
                const tempContainer = document.createElement('div');
                tempContainer.style.position = 'absolute';
                tempContainer.style.left = '-9999px';
                tempContainer.style.top = '0';
                document.body.appendChild(tempContainer);
                
                const backClone = backElement.cloneNode(true);
                tempContainer.appendChild(backClone);
                backClone.style.transform = 'none';
                backClone.style.position = 'static';
                backClone.style.backfaceVisibility = 'visible';

                const frontCanvas = await captureCard(frontElement);
                const backCanvas = await captureCard(backClone);

                document.body.removeChild(tempContainer);
                backElement.style.transform = backElementTransform;
                backElementParent.style.transform = parentTransform;

                // Process secure download
                const processResponse = await fetch('/employees/process-secure-download', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        download_token: tokenData.download_token
                    })
                });

                const processData = await processResponse.json();

                if (!processData.success) {
                    throw new Error(processData.message);
                }

                // Create zip with WinRAR compatible encryption
                const zip = new JSZip();
                
                // Add a readme file with instructions
                zip.file('README.txt', 
                    'This ZIP file contains your ID card images.\n' +
                    'Front and back images are included in PNG format.\n' +
                    'Created on: ' + new Date().toLocaleString() + '\n' +
                    'For security purposes, this file is password protected.'
                );
                
                // Add images to zip with better compression
                zip.file('id-card-front.png', frontCanvas.toDataURL('image/png', 1.0).split(',')[1], {
                    base64: true,
                    compression: "DEFLATE",
                    compressionOptions: {
                        level: 9
                    }
                });
                
                zip.file('id-card-back.png', backCanvas.toDataURL('image/png', 1.0).split(',')[1], {
                    base64: true,
                    compression: "DEFLATE",
                    compressionOptions: {
                        level: 9
                    }
                });
                
                // Generate WinRAR compatible encrypted zip
                const content = await zip.generateAsync({
                    type: "blob",
                    compression: "DEFLATE",
                    compressionOptions: {
                        level: 9
                    },
                    platform: "DOS",
                    encryptStrength: 3,
                    password: processData.password,
                    comment: "Created by {{ config('app.name') }} - Secured with WinRAR encryption"
                });
                
                // Create and trigger download
                const link = document.createElement('a');
                link.href = URL.createObjectURL(content);
                link.download = processData.filename;
                link.type = 'application/zip';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(link.href);

                // Show password dialog with better formatting
                const passwordMessage = 
                    "Your ID card has been downloaded successfully!\n\n" +
                    "File: " + processData.filename + "\n" +
                    "Password: " + processData.password + "\n\n" +
                    "Please keep this password safe. You will need it to open the ZIP file.\n" +
                    "This file can be opened with WinRAR or other ZIP programs.";
                    
                alert(passwordMessage);

                // Analytics tracking if available
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'download_secure_id_card', {
                        'employee_id': '{{ $employee->company_id }}',
                        'timestamp': processData.timestamp
                    });
                }
            } catch (error) {
                console.error('Error generating secure ID card:', error);
                alert('An error occurred while generating the secure ID card. Please try again.');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const flipCard = document.querySelector('.flip-card');
            const instruction = document.querySelector('.flip-instruction');
            let touchStartX = 0;
            let touchEndX = 0;

            // Function to toggle flip
            function toggleFlip() {
                flipCard.classList.toggle('flipped');
            }

            // Handle click/tap events
            flipCard.addEventListener('click', function(e) {
                toggleFlip();
            });

            // Handle touch events
            flipCard.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, false);

            flipCard.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, false);

            // Handle swipe
            function handleSwipe() {
                const swipeThreshold = 50; // minimum distance for swipe
                const difference = touchStartX - touchEndX;

                if (Math.abs(difference) > swipeThreshold) {
                    toggleFlip();
                }
            }

            // Hide instruction after 3 seconds
            if (instruction) {
                setTimeout(() => {
                    instruction.style.opacity = '0';
                    instruction.style.transition = 'opacity 0.5s';
                    setTimeout(() => {
                        instruction.style.display = 'none';
                    }, 500);
                }, 3000);
            }

            // Prevent default touch behaviors
            flipCard.addEventListener('touchmove', function(e) {
                e.preventDefault();
            }, { passive: false });
        });

        function showAuthForm() {
            const modal = document.getElementById('authModal');
            modal.style.display = 'block';
            setTimeout(() => {
                modal.style.opacity = '1';
            }, 10);
        }

        function closeAuthModal() {
            const modal = document.getElementById('authModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none';
                // Reset form
                document.getElementById('authForm').reset();
            }, 300);
        }

        async function validateUser(event) {
            event.preventDefault();
            
            const loadingIndicator = document.getElementById('loadingIndicator');
            loadingIndicator.style.display = 'flex';
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('/validate-user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (data.success) {
                    closeAuthModal();
                    downloadSecureIdCard();
                } else {
                    alert(data.message || 'Invalid credentials. Please try again.');
                }
            } catch (error) {
                console.error('Authentication error:', error);
                alert('An error occurred during authentication. Please try again.');
            } finally {
                loadingIndicator.style.display = 'none';
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('authModal');
            if (event.target === modal) {
                closeAuthModal();
            }
        });
    </script>
</body>
</html>
