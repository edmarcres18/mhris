<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

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
                // OTP is valid
                return response()->json(['message' => 'OTP verified successfully.'], 200);
            } else {
                // OTP has expired, generate and resend a new OTP
                return $this->resendOtp($email);
            }
        } else {
            // OTP is invalid, generate and resend a new OTP
            return $this->resendOtp($email);
        }
    }

    public function resendOtp(Request $request)
{
    try {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->input('email');

        // Generate a new OTP
        $otp = random_int(100000, 999999);

        // Update or insert the OTP in the database
        DB::table('otp_records')->updateOrInsert(
            ['email' => $email],
            [
                'otp' => $otp,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(5)
            ]
        );

        // Send the OTP via email
        Mail::raw("Your new OTP is: $otp", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Your Password Reset OTP');
        });

        return response()->json(['message' => 'New OTP sent to your email.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to resend OTP.', 'error' => $e->getMessage()], 500);
    }
}

}
