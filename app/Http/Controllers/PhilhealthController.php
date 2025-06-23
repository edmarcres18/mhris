<?php

namespace App\Http\Controllers;

use App\Models\Philhealth;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PhilhealthController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $contributions = Philhealth::all();
        $activeEmployeesCount = Employee::where('employee_status', 'Active')
            ->whereNotNull('philhealth_no')
            ->whereRaw('DATEDIFF(CURRENT_DATE, date_hired) >= 60')
            ->count();
        return view('philhealth.index', compact('contributions', 'employees', 'activeEmployeesCount'));
    }

    public function create()
    {
        $employees = Employee::whereNotNull('philhealth_no')
        ->where('employee_status', 'Active')
        ->whereRaw('DATEDIFF(CURRENT_DATE, date_hired) >= 60')
        ->get();
        return view('philhealth.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'contribution_date' => 'required|date_format:Y-m',
        ]);

        $employee = Employee::findOrFail($validatedData['employee_id']);

        $philhealth = new Philhealth();
        $philhealth->employee()->associate($employee);
        $philhealth->contribution_date = $validatedData['contribution_date'] . '-01';

        $philhealth->calculateContribution()->storeWithContributions();

        return redirect()->route('philhealth.index')->with('success', 'Philhealth contribution created successfully.');
    }

    public function show(Philhealth $philhealth)
    {
        return view('philhealth.show', compact('philhealth'));
    }

    public function destroy(Philhealth $philhealth)
    {
        $user = auth()->user();
        // Check if the authenticated user has the 'Super Admin' role
        if ($user->hasRole('Super Admin')) {
            $philhealth->delete();
            return redirect()->route('philhealth.index')->with('success', 'Philhealth contribution deleted successfully.');
        }

        return redirect()->route('philhealth.index')->with('error', 'Unauthorized action.');
    }

    public function storeAllActive(Request $request)
    {
        $request->validate([
            'contribution_date' => 'required|date_format:Y-m',
        ]);

        $contributionDate = $request->contribution_date . '-01'; // Add day to make it a valid date

        // Check if contributions already exist for this month
        if ($this->contributionsExistForMonth($contributionDate)) {
            return redirect()->route('philhealth.index')->with('error', 'Contributions for this month already exist.');
        }

        $activeEmployees = Employee::where('employee_status', 'Active')
            ->whereNotNull('philhealth_no')
            ->whereRaw('DATEDIFF(CURRENT_DATE, date_hired) >= 60')
            ->get();

        DB::beginTransaction();
        try {
            foreach ($activeEmployees as $employee) {
                $philhealth = new Philhealth();
                $philhealth->employee()->associate($employee);
                $philhealth->contribution_date = $contributionDate;
                $philhealth->calculateContribution()->storeWithContributions();
            }
            DB::commit();
            return redirect()->route('philhealth.index')->with('success', 'Philhealth contributions created for all active employees.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('philhealth.index')->with('error', 'Error creating Philhealth contributions: ' . $e->getMessage());
        }
    }

    private function contributionsExistForMonth($date)
    {
        return Philhealth::whereYear('contribution_date', '=', date('Y', strtotime($date)))
            ->whereMonth('contribution_date', '=', date('m', strtotime($date)))
            ->exists();
    }

    public function notifyEmployees(Request $request)
    {
        $request->validate([
            'notification_date' => 'required|date_format:Y-m',
        ]);

        $notificationDate = $request->notification_date . '-01'; // Add day to make it a valid date
        $year = date('Y', strtotime($notificationDate));
        $month = date('m', strtotime($notificationDate));
        $monthName = date('F', strtotime($notificationDate));

        // Find all PhilHealth contributions for the specified month and year
        $contributions = Philhealth::with('employee')
            ->whereYear('contribution_date', '=', $year)
            ->whereMonth('contribution_date', '=', $month)
            ->get();

        if ($contributions->isEmpty()) {
            return redirect()->route('philhealth.index')->with('error', "No PhilHealth contributions found for {$monthName} {$year}.");
        }

        $notificationCount = 0;
        $errorMessages = [];

        foreach ($contributions as $contribution) {
            if (!$contribution->employee) {
                $errorMessages[] = "Employee record not found for contribution ID: {$contribution->id}";
                continue;
            }

            if (empty($contribution->employee->email_address)) {
                $errorMessages[] = "No email address found for employee: {$contribution->employee->last_name}, {$contribution->employee->first_name}";
                continue;
            }

            try {
                Mail::send('emails.philhealth_contribution', [
                    'employee' => $contribution->employee,
                    'contribution' => $contribution,
                    'month' => $monthName,
                    'year' => $year
                ], function ($message) use ($contribution) {
                    $message->to($contribution->employee->email_address)
                        ->subject('PhilHealth Contribution Notification for ' . date('F Y', strtotime($contribution->contribution_date)));
                });
                
                $notificationCount++;
            } catch (\Exception $e) {
                $errorMessages[] = "Failed to send notification to {$contribution->employee->email_address}: {$e->getMessage()}";
            }
        }

        if ($notificationCount > 0) {
            $message = "{$notificationCount} PhilHealth contribution notifications sent successfully for {$monthName} {$year}.";
            
            if (!empty($errorMessages)) {
                $message .= " However, some notifications could not be sent.";
            }
            
            return redirect()->route('philhealth.index')->with('success', $message);
        }

        return redirect()->route('philhealth.index')->with('error', "Failed to send PhilHealth contribution notifications: " . implode("; ", $errorMessages));
    }
}
