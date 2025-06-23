<?php

namespace App\Http\Controllers;

use App\Models\Pagibig;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PagibigController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $contributions = Pagibig::all();
        $activeEmployeesCount = Employee::where('employee_status', 'Active')
            ->whereNotNull('pagibig_no')
            ->whereRaw('DATEDIFF(CURRENT_DATE, date_hired) >= 60')
            ->count();
        return view('pagibig.index', compact('contributions', 'employees', 'activeEmployeesCount'));
    }

    public function create()
    {
        $employees = Employee::whereNotNull('pagibig_no')
        ->where('employee_status', 'Active')
        ->whereRaw('DATEDIFF(CURRENT_DATE, date_hired) >= 60')
        ->get();
        return view('pagibig.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'contribution_date' => 'required|date_format:Y-m',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $contributionDate = $request->contribution_date . '-01';
        Pagibig::createContribution($employee, $contributionDate);

        return redirect()->route('pagibig.index')->with('success', 'Pagibig contribution created successfully.');
    }

    public function show(Pagibig $pagibig)
    {
        return view('pagibig.show', compact('pagibig'));
    }

    public function destroy(Pagibig $pagibig)
    {
        $user = auth()->user();
        // Check if the authenticated user has the 'Super Admin' role
        if ($user->hasRole('Super Admin')) {
            $pagibig->delete();
            return redirect()->route('pagibig.index')->with('success', 'pagibig contribution deleted successfully.');
        }

        return redirect()->route('pagibig.index')->with('error', 'Unauthorized action.');
    }

    public function storeAllActive(Request $request)
    {
        $request->validate([
            'contribution_date' => 'required|date_format:Y-m',
        ]);

        $contributionDate = $request->contribution_date . '-01'; // Add day to make it a valid date

        // Check if contributions already exist for this month
        if ($this->contributionsExistForMonth($contributionDate)) {
            return redirect()->route('pagibig.index')->with('error', 'Contributions for this month already exist.');
        }

        $activeEmployees = Employee::where('employee_status', 'Active')
            ->whereNotNull('pagibig_no')
            ->whereRaw('DATEDIFF(CURRENT_DATE, date_hired) >= 60')
            ->get();

        DB::beginTransaction();
        try {
            foreach ($activeEmployees as $employee) {
                Pagibig::createContribution($employee, $contributionDate);
            }
            DB::commit();
            return redirect()->route('pagibig.index')->with('success', 'Pag-IBIG contributions created for all active employees.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pagibig.index')->with('error', 'Error creating Pag-IBIG contributions: ' . $e->getMessage());
        }
    }

    private function contributionsExistForMonth($date)
    {
        return Pagibig::whereYear('contribution_date', '=', date('Y', strtotime($date)))
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

        // Find all Pag-IBIG contributions for the specified month and year
        $contributions = Pagibig::with('employee')
            ->whereYear('contribution_date', '=', $year)
            ->whereMonth('contribution_date', '=', $month)
            ->get();

        if ($contributions->isEmpty()) {
            return redirect()->route('pagibig.index')->with('error', "No Pag-IBIG contributions found for {$monthName} {$year}.");
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
                Mail::send('emails.pagibig_contribution', [
                    'employee' => $contribution->employee,
                    'contribution' => $contribution,
                    'month' => $monthName,
                    'year' => $year
                ], function ($message) use ($contribution) {
                    $message->to($contribution->employee->email_address)
                        ->subject('Pag-IBIG Contribution Notification for ' . date('F Y', strtotime($contribution->contribution_date)));
                });
                
                $notificationCount++;
            } catch (\Exception $e) {
                $errorMessages[] = "Failed to send notification to {$contribution->employee->email_address}: {$e->getMessage()}";
            }
        }

        if ($notificationCount > 0) {
            $message = "{$notificationCount} Pag-IBIG contribution notifications sent successfully for {$monthName} {$year}.";
            
            if (!empty($errorMessages)) {
                $message .= " However, some notifications could not be sent.";
            }
            
            return redirect()->route('pagibig.index')->with('success', $message);
        }

        return redirect()->route('pagibig.index')->with('error', "Failed to send Pag-IBIG contribution notifications: " . implode("; ", $errorMessages));
    }
}
