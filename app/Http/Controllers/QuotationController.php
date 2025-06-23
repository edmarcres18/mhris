<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\QuotationRequestMail;
use App\Mail\QuotationConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\QuotationRequest;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = QuotationRequest::orderBy('created_at', 'desc')->get();
        return view('quotations.index', compact('quotations'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'status' => 'required|string|in:pending,processed,completed,rejected',
                'notes' => 'nullable|string|max:500'
            ], [
                'status.required' => 'Please select a status',
                'status.in' => 'Invalid status selected',
                'notes.max' => 'Notes cannot exceed 500 characters'
            ]);

            $quotation = QuotationRequest::findOrFail($id);
            $oldStatus = $quotation->status;
            
            $quotation->update([
                'status' => $validatedData['status'],
                'notes' => $validatedData['notes'] ?? null,
                'updated_at' => now()
            ]);

            // Send email notification to customer
            try {
                Mail::to($quotation->email)
                    ->send(new \App\Mail\QuotationStatusUpdateMail($quotation, $oldStatus, $validatedData['status']));
            } catch (\Exception $emailError) {
                Log::error('Failed to send quotation status update email: ' . $emailError->getMessage());
                // Continue execution even if email fails
            }

            $message = 'Quotation status updated successfully from ' . ucfirst($oldStatus) . ' to ' . ucfirst($validatedData['status']);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'status' => $validatedData['status']
                ]);
            }

            return redirect()->route('quotations.index')->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Quotation Update Error: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update quotation status'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to update quotation status')
                ->withInput();
        }
    }

    public function sendRequest(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_id' => 'required|numeric|exists:medical_products,id',
                'name' => 'required|string|min:3|max:255|regex:/^[A-Za-z\s]+$/',
                'email' => 'required|email:rfc,dns|max:255',
                'phone' => ['required', 'string', 'min:10', 'max:20', 'regex:/^[0-9\+\-\s]+$/'],
                'hospital_name' => 'required|string|min:3|max:255',
                'message' => 'nullable|string|max:500'
            ], [
                'product_id.exists' => 'The selected product does not exist.',
                'name.regex' => 'The name may only contain letters and spaces.',
                'phone.regex' => 'Please enter a valid phone number.',
                'email.email' => 'Please enter a valid email address.',
                'email.rfc' => 'Please enter a valid email address.',
                'email.dns' => 'Please enter a valid email domain.'
            ]);

            // Save to database
            $quotationRequest = QuotationRequest::create([
                'product_id' => $validatedData['product_id'],
                'product_name' => $validatedData['product_name'],
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'hospital_name' => $validatedData['hospital_name'],
                'message' => $validatedData['message'] ?? null,
                'status' => 'pending'
            ]);

            // Send email to company
            try {
                Mail::to(config('mail.quotation_recipient', 'csr.mhrhealthcare@gmail.com'))
                    ->send(new QuotationRequestMail($quotationRequest));

                // Send confirmation email to customer
                Mail::to($validatedData['email'])
                    ->send(new QuotationConfirmationMail($quotationRequest));
            } catch (\Exception $emailError) {
                Log::error('Quotation Email Error: ' . $emailError->getMessage());
                // Continue execution even if email fails
                // We might want to notify admin about email failure
                if (app()->bound('sentry')) {
                    \Sentry\captureException($emailError);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Quotation request sent successfully',
                'data' => [
                    'quotation_id' => $quotationRequest->id,
                    'status' => $quotationRequest->status
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Quotation Validation Error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Quotation Request Error: ' . $e->getMessage());
            
            // Log detailed error info for debugging
            Log::error('Error details:', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Send to error tracking service if available
            if (app()->bound('sentry')) {
                \Sentry\captureException($e);
            }

            return response()->json([
                'success' => false,
                'message' => 'There was an error processing your request. Please try again later.'
            ], 500);
        }
    }
} 
