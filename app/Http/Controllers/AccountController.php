<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LinkedAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Link a new account to the current user's account.
     */
    public function link(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        // Find the user to link
        $userToLink = User::where('email', $request->email)->first();

        // Verify the password
        if (!Hash::check($request->password, $userToLink->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if already linked
        if (Auth::user()->linkedAccounts()->where('linked_user_id', $userToLink->id)->exists()) {
            return back()->with('error', 'This account is already linked to your account.');
        }

        // Create the link
        Auth::user()->linkedAccounts()->create([
            'linked_user_id' => $userToLink->id,
            'email' => $userToLink->email,
        ]);

        return back()->with('success', 'Account linked successfully.');
    }

    /**
     * Switch to a linked account.
     */
    public function switch($linkedAccountId)
    {
        $linkedAccount = Auth::user()->linkedAccounts()->findOrFail($linkedAccountId);
        $userToSwitchTo = User::findOrFail($linkedAccount->linked_user_id);

        // Store current user info for toast
        $fromName = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $toName = $userToSwitchTo->first_name . ' ' . $userToSwitchTo->last_name;

        // Log out current user
        Auth::logout();
        
        // Regenerate session
        session()->invalidate();
        session()->regenerateToken();

        // Login as linked user
        Auth::login($userToSwitchTo);

        return redirect()->route('home')->with('toast', [
            'title' => 'Account Switched',
            'from' => $fromName,
            'to' => $toName
        ]);
    }

    /**
     * Unlink an account.
     */
    public function unlink($linkedAccountId)
    {
        $linkedAccount = Auth::user()->linkedAccounts()->findOrFail($linkedAccountId);
        $linkedAccount->delete();

        return back()->with('success', 'Account unlinked successfully.');
    }
} 