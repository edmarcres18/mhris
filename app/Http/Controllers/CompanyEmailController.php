<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyEmail;
use App\Models\ShareableEmailLink;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class CompanyEmailController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'role:Super Admin|Admin|HR  Compliance']);
    }

    public function index()
    {
        $companyEmails = CompanyEmail::all();
        return view('company-emails.index', compact('companyEmails'));
    }

    public function create()
    {
        return view('company-emails.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:company_emails',
            'password' => 'nullable|min:8',
        ]);

        CompanyEmail::create($request->all());
        return redirect()->route('company-emails.index')->with('success', 'Company email created successfully');
    }

    /**
     * Store a new company email and redirect back to create form.
     */
    public function storeAndCreateAnother(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:company_emails',
            'password' => 'nullable|min:8',
        ]);

        CompanyEmail::create($request->all());
        return redirect()->route('company-emails.create')->with('success', 'Company email created successfully. You can add another one.');
    }

    public function edit(CompanyEmail $companyEmail)
    {
        return view('company-emails.edit', compact('companyEmail'));
    }

    public function update(Request $request, CompanyEmail $companyEmail)
    {
        $request->validate([
            'email' => 'required|email|unique:company_emails,email,' . $companyEmail->id,
            'password' => 'nullable|min:8',
        ]);

        $companyEmail->update($request->all());
        return redirect()->route('company-emails.index')->with('success', 'Company email updated successfully');
    }

    public function destroy(CompanyEmail $companyEmail)
    {
        // Ensure only Super Admin can delete company emails
        $user = Auth::user();
        $isSuperAdmin = $user->roles->where('name', 'Super Admin')->count() > 0;

        if (!$isSuperAdmin) {
            return redirect()->route('company-emails.index')
                ->with('error', 'You do not have permission to delete company emails.');
        }
        
        $companyEmail->delete();
        return redirect()->route('company-emails.index')->with('success', 'Company email deleted successfully');
    }

    /**
     * Show form to create a shareable link for company emails.
     */
    public function showShareForm()
    {
        $companyEmails = CompanyEmail::all();
        return view('company-emails.share', compact('companyEmails'));
    }

    /**
     * Generate shareable link for selected company emails.
     */
    public function generateShareableLink(Request $request)
    {
        $request->validate([
            'company_emails' => 'required|array',
            'company_emails.*' => 'exists:company_emails,id',
            'description' => 'nullable|string|max:255',
            'expiration_time' => 'required|in:10,20,30,40,50,60',
        ]);

        // Create shareable link with selected expiry time
        $expirationMinutes = (int) $request->expiration_time;
        $shareableLink = ShareableEmailLink::create([
            'token' => Str::random(64),
            'created_by' => auth()->id(),
            'description' => $request->description,
            'expires_at' => Carbon::now()->addMinutes($expirationMinutes),
        ]);

        // Attach selected company emails
        $shareableLink->companyEmails()->attach($request->company_emails);

        // If the request is AJAX, return JSON response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'token' => $shareableLink->token,
                'message' => 'Shareable link generated successfully.'
            ]);
        }

        // For regular form submission
        return redirect()->route('company-emails.share-link', $shareableLink->token)
            ->with('success', 'Shareable link generated successfully.');
    }

    /**
     * Show the shareable link details.
     */
    public function showShareableLink(string $token)
    {
        $shareableLink = ShareableEmailLink::where('token', $token)->firstOrFail();
        
        return view('company-emails.share-link', compact('shareableLink'));
    }

    /**
     * Access a shared email list via the public link.
     */
    public function accessSharedEmails(string $token)
    {
        $shareableLink = ShareableEmailLink::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->firstOrFail();

        $companyEmails = $shareableLink->companyEmails;
        $remainingTime = $shareableLink->remainingTimeInMinutes();

        return view('company-emails.public-share', compact('companyEmails', 'shareableLink', 'remainingTime'));
    }

    /**
     * List all active shareable links for the current user.
     */
    public function listShareableLinks()
    {
        $shareableLinks = ShareableEmailLink::where('created_by', auth()->id())
            ->active()
            ->with('companyEmails')
            ->orderBy('expires_at', 'desc')
            ->get();

        return view('company-emails.shareable-links', compact('shareableLinks'));
    }

    /**
     * Delete a shareable link.
     */
    public function deleteShareableLink(ShareableEmailLink $shareableLink)
    {
        // Only allow the creator or a Super Admin to delete
        $user = Auth::user();
        $isSuperAdmin = $user->roles->where('name', 'Super Admin')->count() > 0;

        if (auth()->id() !== $shareableLink->created_by && !$isSuperAdmin) {
            return redirect()->back()->with('error', 'You do not have permission to delete this link.');
        }

        $shareableLink->delete();
        return redirect()->route('company-emails.shareable-links')
            ->with('success', 'Shareable link deleted successfully.');
    }
}
