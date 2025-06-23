<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credentials;
use App\Models\Employee;
use App\Models\CompanyEmail;
use App\Models\ShareableCredentialLink;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\ShareableCredentialView;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CredentialController extends Controller
{
            /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:credential-list|credential-create|credential-edit|credential-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:credential-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:credential-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:credential-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $credentials = Credentials::all();
        return view('credentials.index', compact('credentials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        $companyEmails = CompanyEmail::where('status', 'active')->get();
        return view('credentials.create', compact('employees', 'companyEmails'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'company_number' => 'nullable|numeric',
            'company_email' => 'nullable|email|exists:company_emails,email',
            'email_password' => 'nullable|string',
        ]);

        // If company_email is provided but password is not, get password from CompanyEmail model
        if (!empty($validatedData['company_email']) && empty($validatedData['email_password'])) {
            $companyEmail = CompanyEmail::where('email', $validatedData['company_email'])->first();
            if ($companyEmail) {
                $validatedData['email_password'] = $companyEmail->password;
            }
        }

        $credential = Credentials::create($validatedData);
        
        // Check if the action is "save_and_create" to determine the redirect
        if ($request->input('action') === 'save_and_create') {
            return redirect()->route('credentials.create')
                ->with('success', 'Credentials created successfully. You can now add another.');
        }
        
        return redirect()->route('credentials.index')
            ->with('success', 'Credentials created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Credentials $credential)
    {
        return view('credentials.show', compact('credential'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Credentials $credential)
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        $companyEmails = CompanyEmail::where('status', 'active')->get();
        return view('credentials.edit', compact('credential', 'employees', 'companyEmails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Credentials $credential)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'company_number' => 'nullable|numeric',
            'company_email' => 'nullable|email|exists:company_emails,email',
            'email_password' => 'nullable|string',
        ]);

        // If company_email is provided but password is not, get password from CompanyEmail model
        if (!empty($validatedData['company_email']) && empty($validatedData['email_password'])) {
            $companyEmail = CompanyEmail::where('email', $validatedData['company_email'])->first();
            if ($companyEmail) {
                $validatedData['email_password'] = $companyEmail->password;
            }
        }

        $credential->update($validatedData);
        return redirect()->route('credentials.index')->with('success', 'Credentials updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Credentials $credential)
    {
        $credential->delete();
        return redirect()->route('credentials.index')->with('success', 'Credentials deleted successfully.');
    }
    
    /**
     * Get company email password (AJAX endpoint)
     */
    public function getEmailPassword(Request $request)
    {
        $email = $request->input('email');
        $companyEmail = CompanyEmail::where('email', $email)->first();
        
        if ($companyEmail) {
            return response()->json(['success' => true, 'password' => $companyEmail->password]);
        }
        
        return response()->json(['success' => false]);
    }

    /**
     * Show form to share credentials
     */
    public function showShareForm()
    {
        $credentials = Credentials::with('employee')->get();
        return view('credentials.share', compact('credentials'));
    }

    /**
     * Generate shareable link for credentials
     */
    public function generateShareableLink(Request $request)
    {
        $request->validate([
            'credential_ids' => 'required|array',
            'credential_ids.*' => 'exists:credentials,id',
            'description' => 'nullable|string|max:255',
            'expiration' => 'required|integer|min:1|max:720', // Maximum 30 days (720 hours)
        ]);

        // Create shareable link
        $shareableLink = ShareableCredentialLink::create([
            'token' => Str::random(64),
            'created_by' => Auth::id(),
            'description' => $request->description,
            'expires_at' => Carbon::now()->addHours($request->expiration),
        ]);

        // Attach selected credentials to the shareable link
        $shareableLink->credentials()->attach($request->credential_ids);

        return redirect()->route('credentials.shareable-links')
            ->with('success', 'Shareable link created successfully!');
    }

    /**
     * List all shareable links created by the authenticated user
     */
    public function listShareableLinks()
    {
        $shareableLinks = ShareableCredentialLink::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('credentials.shareable-links', compact('shareableLinks'));
    }

    /**
     * View a specific shareable link details
     */
    public function showShareableLink($id)
    {
        $shareableLink = ShareableCredentialLink::findOrFail($id);
        
        // Check if user is authorized to view this link details
        if ($shareableLink->created_by !== Auth::id()) {
            return redirect()->route('credentials.shareable-links')
                ->with('error', 'You are not authorized to view this shareable link details');
        }
        
        return view('credentials.show-shareable-link', compact('shareableLink'));
    }

    /**
     * Delete a shareable link
     */
    public function deleteShareableLink(ShareableCredentialLink $shareableLink)
    {
        // Check if user is authorized to delete this link
        if ($shareableLink->created_by !== Auth::id()) {
            return redirect()->route('credentials.shareable-links')
                ->with('error', 'You are not authorized to delete this shareable link');
        }
        
        $shareableLink->delete();
        
        return redirect()->route('credentials.shareable-links')
            ->with('success', 'Shareable link deleted successfully');
    }

    /**
     * Public access to shared credentials
     */
    public function accessSharedCredentials($token)
    {
        // Find the shareable link by token
        $shareableLink = ShareableCredentialLink::where('token', $token)->first();
        
        // Check if the link exists and is still valid
        if (!$shareableLink || !$shareableLink->isActive()) {
            return view('credentials.shared-credentials-error', [
                'error' => $shareableLink ? 'This link has expired.' : 'Invalid or expired link.'
            ]);
        }
        
        // Get authentication status from session, default to not authenticated
        $isAuthenticated = Session::get('email_auth', false);
        
        // Record view
        $this->recordLinkView($shareableLink, $isAuthenticated);
        
        // Load credentials associated with this link
        $credentials = $shareableLink->credentials;
        
        // Store the original shared URL in session for post-authentication redirect
        Session::put('original_shared_credential_url', request()->fullUrl());
        
        return view('credentials.shared-credentials', [
            'credentials' => $credentials,
            'shareableLink' => $shareableLink,
            'isAuthenticated' => $isAuthenticated,
            'token' => $token
        ]);
    }
    
    /**
     * Record a view of the shared credential link
     */
    private function recordLinkView($shareableLink, $isAuthenticated = false)
    {
        ShareableCredentialView::create([
            'shareable_credential_link_id' => $shareableLink->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'email' => Session::get('authenticated_email'),
            'auth_provider' => $isAuthenticated ? 'email_otp' : null,
            'timestamp' => now(),
            'is_authenticated' => $isAuthenticated
        ]);
    }
    
    /**
     * Show email authentication form
     */
    public function showEmailAuthForm($token)
    {
        // Store the token for later use
        Session::put('credential_token', $token);
        
        // Store the full URL of the shared credentials page to redirect back to after auth
        if (!Session::has('original_shared_credential_url')) {
            Session::put('original_shared_credential_url', request()->fullUrl());
        }
        
        return view('credentials.email-auth', ['token' => $token]);
    }
    
    /**
     * Process email request and send OTP
     */
    public function processEmailAuth(Request $request, $token)
    {
        // Validate email
        $validated = $request->validate([
            'email' => 'required|email',
        ]);
        
        // Generate OTP
        $otp = mt_rand(100000, 999999);
        
        // Store OTP and email in session
        Session::put('otp', $otp);
        Session::put('email_for_auth', $validated['email']);
        Session::put('otp_created_at', now()->timestamp);
        
        // Send OTP to email
        try {
            Mail::raw("Your OTP for credential access is: $otp. This code will expire in 10 minutes.", function ($message) use ($validated) {
                $message->to($validated['email'])
                        ->subject('Your Access Code for Shared Credentials');
            });
            
            // Redirect to OTP verification page
            return redirect()->route('credentials.verify-otp', $token)
                ->with('success', 'We have sent a verification code to your email address.');
                
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification code. Please try again.');
        }
    }
    
    /**
     * Show OTP verification form
     */
    public function showOtpVerification($token)
    {
        // Check if email and OTP exist in session
        if (!Session::has('email_for_auth') || !Session::has('otp')) {
            return redirect()->route('credentials.email-auth', $token)
                ->with('error', 'Please enter your email first.');
        }
        
        // Check if OTP has expired (10 minutes)
        $otpCreatedAt = Session::get('otp_created_at', 0);
        if (now()->timestamp - $otpCreatedAt > 600) {
            Session::forget(['otp', 'otp_created_at']);
            return redirect()->route('credentials.email-auth', $token)
                ->with('error', 'Your verification code has expired. Please request a new one.');
        }
        
        $email = Session::get('email_for_auth');
        $maskedEmail = $this->maskEmail($email);
        
        return view('credentials.verify-otp', [
            'token' => $token,
            'maskedEmail' => $maskedEmail
        ]);
    }
    
    /**
     * Verify OTP entered by user
     */
    public function verifyOtp(Request $request, $token)
    {
        // Validate OTP
        $validated = $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);
        
        // Check if OTP matches
        $storedOtp = Session::get('otp');
        if (!$storedOtp || $validated['otp'] != $storedOtp) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }
        
        // Check if OTP has expired (10 minutes)
        $otpCreatedAt = Session::get('otp_created_at', 0);
        if (now()->timestamp - $otpCreatedAt > 600) {
            Session::forget(['otp', 'otp_created_at']);
            return redirect()->route('credentials.email-auth', $token)
                ->with('error', 'Your verification code has expired. Please request a new one.');
        }
        
        // Get the original URL to redirect back to
        $originalUrl = Session::get('original_shared_credential_url');
        
        // Fallback to route if somehow the original URL wasn't stored
        if (!$originalUrl) {
            $originalUrl = route('credentials.access-shared', $token);
        }
        
        // Retrieve the shareable link
        $shareableLink = ShareableCredentialLink::where('token', $token)->first();
        
        if (!$shareableLink || !$shareableLink->isActive()) {
            return redirect()->route('welcome')
                ->with('error', 'Invalid or expired credential sharing link');
        }
        
        $email = Session::get('email_for_auth');
        
        // Store authentication data in session
        Session::put('email_auth', true);
        Session::put('authenticated_email', $email);
        Session::put('auth_timestamp', now()->toIso8601String());
        
        // Clear OTP data
        Session::forget(['otp', 'otp_created_at', 'email_for_auth']);
        
        // Log the successful authentication
        $this->recordAuthenticationSuccess($shareableLink, $email);
        
        // Redirect to the exact same URL the user initially accessed
        return redirect($originalUrl)
            ->with('success', 'Authentication successful. You can now view the credentials.');
    }
    
    /**
     * Mask email for display in the UI
     */
    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        if (count($parts) != 2) return $email;
        
        $namePart = $parts[0];
        $domainPart = $parts[1];
        
        if (strlen($namePart) <= 2) {
            $maskedName = str_repeat('*', strlen($namePart));
        } else {
            $maskedName = substr($namePart, 0, 1) . str_repeat('*', strlen($namePart) - 2) . substr($namePart, -1);
        }
        
        return $maskedName . '@' . $domainPart;
    }
    
    /**
     * Resend OTP to the user's email
     */
    public function resendOtp($token)
    {
        // Check if email exists in session
        if (!Session::has('email_for_auth')) {
            return redirect()->route('credentials.email-auth', $token)
                ->with('error', 'Please enter your email first.');
        }
        
        $email = Session::get('email_for_auth');
        
        // Generate new OTP
        $otp = mt_rand(100000, 999999);
        
        // Update OTP in session
        Session::put('otp', $otp);
        Session::put('otp_created_at', now()->timestamp);
        
        // Send OTP to email
        try {
            Mail::raw("Your new OTP for credential access is: $otp. This code will expire in 10 minutes.", function ($message) use ($email) {
                $message->to($email)
                        ->subject('Your New Access Code for Shared Credentials');
            });
            
            return redirect()->route('credentials.verify-otp', $token)
                ->with('success', 'We have sent a new verification code to your email address.');
                
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification code. Please try again.');
        }
    }
    
    /**
     * Record a successful authentication
     */
    private function recordAuthenticationSuccess($shareableLink, $email)
    {
        // Check if there's an existing view from this session
        $existingView = ShareableCredentialView::where('shareable_credential_link_id', $shareableLink->id)
            ->where('ip_address', request()->ip())
            ->where('email', null)
            ->latest()
            ->first();
            
        if ($existingView) {
            // Update the existing view with the authentication info
            $existingView->update([
                'email' => $email,
                'auth_provider' => 'email_otp',
                'is_authenticated' => true
            ]);
        } else {
            // Create a new view record
            ShareableCredentialView::create([
                'shareable_credential_link_id' => $shareableLink->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'email' => $email,
                'auth_provider' => 'email_otp',
                'timestamp' => now(),
                'is_authenticated' => true
            ]);
        }
    }

    /**
     * Show tracking information for a shareable link
     */
    public function showLinkTracking($id)
    {
        try {
            // Try to load with views relationship
            $shareableLink = ShareableCredentialLink::with(['credentials'])->findOrFail($id);
            
            // Check if user is authorized to view this link tracking
            if ($shareableLink->created_by !== Auth::id()) {
                return redirect()->route('credentials.shareable-links')
                    ->with('error', 'You are not authorized to view tracking information for this link');
            }
            
            // Manually load views to handle cases where the relationship might not work
            try {
                $views = ShareableCredentialView::where('shareable_credential_link_id', $shareableLink->id)->get();
            } catch (\Exception $e) {
                // If this fails, try the other possible column name
                try {
                    $views = ShareableCredentialView::where('shareable_link_id', $shareableLink->id)->get();
                } catch (\Exception $innerEx) {
                    // If both fail, set views to empty collection
                    $views = collect([]);
                }
            }
            
            // Manually attach the views to the model
            $shareableLink->setRelation('views', $views);
            
            return view('credentials.share-tracking', compact('shareableLink'));
            
        } catch (\Exception $exception) {
            // Handle any exceptions
            return redirect()->route('credentials.shareable-links')
                ->with('error', 'There was an error loading the tracking information: ' . $exception->getMessage());
        }
    }
}
