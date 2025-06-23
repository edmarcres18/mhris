<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    use SendsPasswordResetEmails;

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user, // Returning user details along with the token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }

    public function sendOtp(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');

        // Generate a random 6-digit OTP
        $otp = random_int(100000, 999999); // Generates a random integer between 100000 and 999999

        // Store the OTP in the database
        DB::table('otp_records')->updateOrInsert(
            ['email' => $email],
            [
                'otp' => $otp,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(5) // Set expiration time
            ]
        );

        // Send the OTP via email
        Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Your Password Reset OTP');
        });

        return response()->json(['message' => 'OTP sent to your email.'], 200);
    }

    public function verifyOtp(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $email = $request->input('email');
        $otp = $request->input('otp');

        // Check if the OTP is valid and not expired
        $record = DB::table('otp_records')->where('email', $email)->first();

        if ($record && $record->otp == $otp) {
            // Check if the OTP has expired
            if (Carbon::now()->lessThanOrEqualTo($record->expires_at)) {
                // OTP is valid, you can perform the next step (e.g., password reset)
                return response()->json(['message' => 'OTP verified successfully.'], 200);
            } else {
                // OTP has expired
                return response()->json(['message' => 'OTP has expired. Please request a new OTP.'], 400);
            }
        }

        // OTP is invalid
        return response()->json(['message' => 'Invalid OTP. Please try again.'], 400);
    }

    public function validateUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            return response()->json([
                'success' => true,
                'message' => 'Authentication successful'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }
}
