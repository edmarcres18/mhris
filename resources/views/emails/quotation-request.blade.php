<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
    <div class="max-w-2xl mx-auto my-8 bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-800 to-blue-900 px-8 py-6 text-center">
            <h1 class="text-2xl font-bold text-white mb-2">New Product Quotation Request</h1>
            <p class="text-blue-100 opacity-90">Reference: QR-{{ date('Ymd') }}-{{ rand(1000, 9999) }}</p>
        </div>

        <!-- Main Content -->
        <div class="p-8">
            <!-- Priority Alert -->
            <div class="flex items-center gap-3 bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <div>
                    <span class="font-semibold text-red-800">Priority Action Required:</span>
                    <span class="text-red-700 ml-1">New quotation request requires immediate attention</span>
                </div>
            </div>

            <!-- Introduction -->
            <p class="text-lg font-medium text-gray-800 mb-4">Dear Sales Team,</p>
            <p class="text-gray-700 mb-6">A new quotation request has been received through our online platform and requires your prompt attention.</p>

            <!-- Customer Information Section -->
            <div class="mb-8">
                <h2 class="flex items-center text-lg font-semibold text-blue-800 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Customer Information
                </h2>
                
                <div class="grid gap-4 bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <span class="block text-sm font-semibold text-blue-800 uppercase tracking-wide mb-2">Contact Person</span>
                        <p class="text-gray-700 p-3 bg-gray-50 rounded border border-gray-200">{{ $customerName }}</p>
                    </div>

                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <span class="block text-sm font-semibold text-blue-800 uppercase tracking-wide mb-2">Email Address</span>
                        <p class="text-gray-700 p-3 bg-gray-50 rounded border border-gray-200">{{ $customerEmail }}</p>
                    </div>

                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <span class="block text-sm font-semibold text-blue-800 uppercase tracking-wide mb-2">Contact Number</span>
                        <p class="text-gray-700 p-3 bg-gray-50 rounded border border-gray-200">{{ $customerPhone }}</p>
                    </div>

                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <span class="block text-sm font-semibold text-blue-800 uppercase tracking-wide mb-2">Institution</span>
                        <p class="text-gray-700 p-3 bg-gray-50 rounded border border-gray-200">{{ $hospitalName }}</p>
                    </div>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="mb-8">
                <h2 class="flex items-center text-lg font-semibold text-blue-800 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Product Details
                </h2>
                
                <div class="grid gap-4 bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <span class="block text-sm font-semibold text-blue-800 uppercase tracking-wide mb-2">Requested Product</span>
                        <p class="text-gray-700 p-3 bg-gray-50 rounded border border-gray-200">{{ $productName }}</p>
                    </div>

                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <span class="block text-sm font-semibold text-blue-800 uppercase tracking-wide mb-2">Request Timestamp</span>
                        <p class="text-gray-700 p-3 bg-gray-50 rounded border border-gray-200">{{ now()->format('F j, Y, g:i a') }} PHT</p>
                    </div>
                </div>
            </div>

            <!-- Required Actions Section -->
            <div class="bg-green-50 rounded-lg border border-green-200 p-6 mb-8">
                <h3 class="flex items-center text-lg font-semibold text-green-800 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Required Actions
                </h3>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg p-4">
                        <h4 class="text-green-800 font-semibold mb-3">Initial Review (Within 2 Hours)</h4>
                        <ul class="list-disc ml-5 text-gray-700 space-y-2">
                            <li>Verify product availability and current pricing</li>
                            <li>Check customer's previous transaction history (if any)</li>
                            <li>Review any special pricing considerations</li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-lg p-4">
                        <h4 class="text-green-800 font-semibold mb-3">Quotation Preparation (Within 24 Hours)</h4>
                        <ul class="list-disc ml-5 text-gray-700 space-y-2">
                            <li>Complete product specifications documentation</li>
                            <li>Calculate pricing including any applicable discounts</li>
                            <li>Include delivery timeline and terms</li>
                            <li>Specify payment terms and conditions</li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-lg p-4">
                        <h4 class="text-green-800 font-semibold mb-3">Customer Communication</h4>
                        <ul class="list-disc ml-5 text-gray-700 space-y-2">
                            <li>Send acknowledgment email (automated)</li>
                            <li>Schedule follow-up call if needed</li>
                            <li>Prepare for potential technical queries</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Timestamp -->
            <div class="text-right text-gray-600 text-sm border-t border-gray-200 pt-4 mb-6">
                Request Generated: {{ now()->format('l, F j, Y \a\t g:i:s a') }} PHT
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                <p class="text-gray-700 italic mb-6">
                    This is an internal notification for the sales team. Please process this request according to our standard operating procedures and maintain appropriate documentation in our CRM system.
                </p>

                <div class="border-t border-gray-200 pt-4 text-gray-600">
                    <p class="mb-1">Generated by Automated Quotation System</p>
                    <p class="mb-1">Medical & Hospital Resources Health Care, Inc.</p>
                    <p class="text-sm text-gray-500">Internal Reference: MHR-{{ date('Y') }}-{{ rand(10000, 99999) }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 