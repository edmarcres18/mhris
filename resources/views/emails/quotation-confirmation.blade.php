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
            <h1 class="text-2xl font-bold text-white mb-2">Quotation Request Confirmation</h1>
            <p class="text-blue-100 opacity-90">Reference: QR-{{ date('Ymd') }}-{{ rand(1000, 9999) }}</p>
        </div>

        <!-- Main Content -->
        <div class="p-8">
            <!-- Greeting -->
            <p class="text-lg text-gray-800 mb-6">Dear {{ $customerName }},</p>
            
            <!-- Confirmation Message -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 rounded-r">
                <p class="text-blue-800">
                    Thank you for choosing Medical & Hospital Resources Health Care, Inc. We have successfully received your quotation request and it has been registered in our system.
                </p>
            </div>

            <!-- Request Details -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 mb-8">
                <h2 class="text-xl font-semibold text-blue-800 border-b border-gray-200 pb-3 mb-4">
                    Request Details
                </h2>
                
                <!-- Product Information -->
                <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
                    <span class="block text-sm font-semibold text-blue-800 uppercase tracking-wide mb-2">
                        Product Information
                    </span>
                    <p class="text-gray-700 p-3 bg-gray-50 rounded border border-gray-200">
                        {{ $productName }}
                    </p>
                </div>

                <!-- Customer Details -->
                <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
                    <span class="block text-sm font-semibold text-blue-800 uppercase tracking-wide mb-2">
                        Customer Details
                    </span>
                    <div class="text-gray-700 p-3 bg-gray-50 rounded border border-gray-200 space-y-2">
                        <p><span class="font-medium">Name:</span> {{ $customerName }}</p>
                        <p><span class="font-medium">Email:</span> {{ $customerEmail }}</p>
                        <p><span class="font-medium">Contact:</span> {{ $customerPhone }}</p>
                        <p><span class="font-medium">Institution:</span> {{ $hospitalName }}</p>
                    </div>
                </div>

                <!-- Submission Time -->
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <span class="block text-sm font-semibold text-blue-800 uppercase tracking-wide mb-2">
                        Submission Time
                    </span>
                    <p class="text-gray-700 p-3 bg-gray-50 rounded border border-gray-200">
                        {{ now()->format('F j, Y, g:i a') }} PHT
                    </p>
                </div>
            </div>

            <!-- Process Information -->
            <p class="text-gray-700 mb-6">
                Our expert team will carefully evaluate your request and prepare a comprehensive quotation tailored to your specific requirements. You can expect to receive our detailed proposal within the next 24-48 business hours.
            </p>

            <!-- Next Steps -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8 rounded-r">
                <h3 class="font-semibold text-green-800 mb-2">What's Next?</h3>
                <ul class="text-green-800 ml-6 list-disc space-y-2">
                    <li>Our team will review your requirements</li>
                    <li>Prepare a detailed quotation with pricing and terms</li>
                    <li>Send you a comprehensive proposal</li>
                    <li>Follow up to address any questions you may have</li>
                </ul>
            </div>

            <!-- Contact Information -->
            <p class="text-gray-700 mb-8">
                If you need immediate assistance or have any questions in the meantime, please don't hesitate to contact our dedicated customer service team.
            </p>

            <!-- Signature -->
            <div class="border-t border-gray-200 pt-6 mb-8">
                <p class="text-gray-700">Best regards,</p>
                <p class="font-semibold text-gray-800 mt-2">
                    Customer Service Department<br>
                    Medical & Hospital Resources Health Care, Inc.
                </p>
            </div>

            <!-- Automated Message Note -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-amber-800 text-sm italic mb-8">
                This is an automated confirmation message. Please do not reply to this email. For inquiries, please contact us directly using the information below.
            </div>

            <!-- Company Information -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-4">
                    Medical & Hospital Resources Health Care, Inc.
                </h3>
                <div class="space-y-3 text-gray-700">
                    <p>
                        <span class="font-medium">Address:</span><br>
                        MHR Building: Jose L. Briones St., NRA<br>
                        Cebu City, Philippines, 6000
                    </p>
                    <p><span class="font-medium">Contact:</span> +63 32 234 5678</p>
                    <p><span class="font-medium">Email:</span> 
                        <a href="mailto:csr.mhrhealthcare@gmail.com" class="text-blue-600 hover:text-blue-800">
                            csr.mhrhealthcare@gmail.com
                        </a>
                    </p>
                    <p><span class="font-medium">Website:</span> 
                        <a href="https://mhrpci.site/mhrhealthcareinc" class="text-blue-600 hover:text-blue-800">
                            mhrpci.site/mhrhealthcareinc
                        </a>
                    </p>
                    <p class="text-sm text-gray-600 mt-4 pt-4 border-t border-gray-200">
                        Business Hours: Monday - Friday, 8:00 AM - 5:00 PM PHT
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 