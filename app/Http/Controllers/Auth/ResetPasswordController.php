<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/home';

    /**
     * Handle a password reset request for the given email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed', // Ensure the password is confirmed
        ]);

        // If validation fails, throw a validation exception
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Find the user by email
        $user = User::where('email', $request->input('email'))->first();

        if ($user) {
            // Update the user's password
            $user->password = Hash::make($request->input('password'));
            $user->save();

            // Optional: Send a notification or event (like an email)
            // You can create a notification class for this
            // Notification::send($user, new PasswordResetSuccessNotification());

            return response()->json(['message' => 'Password reset successfully.'], 200);
        }

        return response()->json(['message' => 'User not found.'], 404);
    }
}
