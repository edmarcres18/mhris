<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CelebrantsController extends Controller
{
    public function getTodayCelebrants(): JsonResponse
    {
        $today = now()->format('m-d');
        $celebrants = Employee::whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", [$today])
            ->select('first_name', 'last_name', 'department', 'profile_picture')
            ->get()
            ->map(function ($employee) {
                return [
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'department' => $employee->department,
                    'profile_picture' => $employee->profile_picture ? asset($employee->profile_picture) : null
                ];
            });

        $userDismissed = false;
        if (auth()->check()) {
            $userDismissed = Cache::get('celebrants_dismissed_' . auth()->id() . '_' . now()->format('Y-m-d'), false);
        }

        return response()->json([
            'celebrants' => $celebrants,
            'userDismissed' => $userDismissed
        ]);
    }

    public function dismissCelebrants(): JsonResponse
    {
        if (auth()->check()) {
            Cache::put(
                'celebrants_dismissed_' . auth()->id() . '_' . now()->format('Y-m-d'),
                true,
                now()->endOfDay()
            );
        }
        return response()->json(['success' => true]);
    }
} 