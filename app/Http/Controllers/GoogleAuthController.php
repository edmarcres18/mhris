<?php

namespace App\Http\Controllers;

use App\Models\GoogleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        // Store the previous route in the session
        $request->session()->put('url.intended', url()->previous());
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = GoogleUser::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                    'token' => $googleUser->token,
                ]
            );

            Auth::guard('google')->login($user);

            // Redirect to the intended URL or fallback to home
            $redirectTo = $request->session()->pull('url.intended', '/');
            return redirect($redirectTo)->with('success', 'Successfully logged in!');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Google login failed. Please try again.');
        }
    }

    public function getUserInfo()
    {
        if (Auth::guard('google')->check()) {
            $user = Auth::guard('google')->user();
            return response()->json([
                'name' => $user->name,
                'avatar' => $user->avatar,
            ]);
        }
        return response()->json(null);
    }

    public function logout(Request $request)
    {
        Auth::guard('google')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the previous page or fallback to home
        return redirect(url()->previous())->with('success', 'Successfully logged out!');
    }
}
