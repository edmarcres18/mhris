<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Department;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Supervisor');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Check if user is a Supervisor
        if (!$user->hasRole('Supervisor')) {
            abort(403, 'Unauthorized. Only supervisors can view activity logs.');
        }

        // Get all activity logs from users in the same department
        $logs = ActivityLog::with(['user' => function ($query) {
                // Eager load only necessary user fields
                $query->select('id', 'first_name', 'last_name', 'department_id')
                    ->with('department:id,name'); // Eager load department with specific fields
            }])
            ->whereHas('user', function ($query) use ($user) {
                $query->where(function($q) use ($user) {
                    if ($user->department_id) {
                        $q->where('department_id', $user->department_id)
                          ->orWhereNull('department_id');
                    }
                });
                $query->whereHas('roles', function($q) {
                    $q->where('name', 'Employee');
                });
            })
            ->select([
                'id',
                'user_id',
                'action',
                'description',
                'ip_address',
                'created_at'
            ])
            ->latest()
            ->get();

        // Get the department name
        $department = $user->department->name ?? 'Unknown Department';

        return view('activity_logs.index', compact('logs', 'department'));
    }
}
