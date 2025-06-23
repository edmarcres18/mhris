<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\LoginHistory;
use Laravel\Sanctum\HasApiTokens;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Log successful login
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'login_successful' => true
            ]);

            // Check if the user's status is disabled
            if ($user->status === 'disabled') {
                Auth::logout(); // Log out the user if status is disabled
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is disabled.',
                    'field' => 'email'
                ]);
            }

            // Set redirect path based on user role
            $redirectTo = '/home'; // default path
            if ($user->hasRole('Product Manager')) {
                $redirectTo = '/analytics';
                
                // Force redirect to analytics if coming from login page
                if (url()->previous() === url('/login')) {
                    return redirect()->to('/analytics');
                }
            }

            // Generate a token (if using token-based authentication)
            $token = $user->createToken('YourAppName')->plainTextToken;

            // Authentication passed
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $user,
                'redirect' => $redirectTo
            ]);
        }

        // Check if the email exists
        $user = User::where('email', $request->email)->first();

        // Log failed login attempt
        if ($user) {
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'login_successful' => false
            ]);
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email does not exist.',
                'field' => 'email'
            ]);
        }

        // If the email exists, then the password must be wrong
        return response()->json([
            'success' => false,
            'message' => 'Incorrect password.',
            'field' => 'password'
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        }

        return redirect()->route('login', ['logout' => 'success']);
    }
}
