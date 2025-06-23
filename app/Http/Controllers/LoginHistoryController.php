<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $loginHistory = LoginHistory::with('user')
            ->whereHas('user', function($query) use ($user) {
                $query->where('email', $user->email);
            })
            ->orderBy('login_at', 'desc')
            ->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($loginHistory);
        }

        return view('auth.login-history', compact('loginHistory'));
    }
} 
