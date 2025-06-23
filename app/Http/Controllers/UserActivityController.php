<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || !auth()->user()->hasRole(['Supervisor', 'Super Admin'])) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('user-activity.index');
    }
}
