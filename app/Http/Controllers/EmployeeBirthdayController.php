<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeBirthdayController extends Controller
{
    public function index()
    {
        $employees = Employee::where('employee_status', 'Active')
            ->get()
            ->map(function ($employee) {
                $birthDate = Carbon::parse($employee->birth_date);
                $employee->birth_month = $birthDate->format('F');
                $employee->birth_day = $birthDate->format('d');
                return $employee;
            })
            ->groupBy('birth_month');

        return view('birthdays', compact('employees'));
    }
}
