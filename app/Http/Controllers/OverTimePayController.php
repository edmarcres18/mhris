<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\OvertimePay;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class OverTimePayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user) {
                abort(403, 'Unauthorized action.');
            }

            // Check if user has any of the required roles
            $hasRequiredRole = $user->roles->pluck('name')->intersect(['HR ComBen', 'Supervisor', 'Finance', 'Employee', 'VP Finance', 'Admin', 'Super Admin'])->count() > 0;

            // Check if user is a supervisor with matching email to an employee
            $isSupervisorWithMatchingEmail = false;
            if ($user->roles->pluck('name')->contains('Supervisor')) {
                $employee = Employee::where('email_address', $user->email)->first();
                $isSupervisorWithMatchingEmail = $employee !== null;
            }

            if (!$hasRequiredRole && !$isSupervisorWithMatchingEmail) {
                abort(403, 'Unauthorized action.');
            }
            
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user has the required role
        $user = Auth::user();
        if (!$user || !$user->roles->pluck('name')->intersect(['Super Admin', 'Admin', 'Finance', 'VP Finance', 'HR ComBen', 'Supervisor'])->count() > 0) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch all OvertimePay records
        $overtime = OvertimePay::all();

        // Calculate overtime pay for each record
        foreach ($overtime as $overtimePay) {
            $overtimePay->calculateOvertimePay();
        }

        // Pass the data to the view
        return view('overtime.index', compact('overtime'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user has the required role
        $user = Auth::user();
        if (!$user || !$user->roles->pluck('name')->intersect(['Super Admin', 'Admin', 'Finance', 'VP Finance', 'HR ComBen', 'Supervisor'])->count() > 0) {
            abort(403, 'Unauthorized action.');
        }

        $employees = Employee::where('employee_status', 'Active')->get();
        return view('overtime.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user has the required role
        $user = Auth::user();
        if (!$user || !$user->roles->pluck('name')->intersect(['Super Admin', 'Admin', 'Finance', 'VP Finance', 'HR ComBen', 'Supervisor'])->count() > 0) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time_in' => 'required|date_format:Y-m-d\TH:i',
            'time_out' => 'required|date_format:Y-m-d\TH:i|after:time_in',
            'overtime_rate' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Format the datetimes properly
            $date = date('Y-m-d', strtotime($request->date));
            $timeIn = date('Y-m-d H:i:s', strtotime($request->time_in));
            $timeOut = date('Y-m-d H:i:s', strtotime($request->time_out));
            
            // Verify time_in date matches the date field
            $timeInDate = date('Y-m-d', strtotime($timeIn));
            if ($timeInDate !== $date) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['time_in' => 'Time in date must match the overtime date']);
            }
            
            // Calculate the time difference in hours (before saving)
            $timeDiff = strtotime($timeOut) - strtotime($timeIn);
            $hours = $timeDiff / 3600; // Convert seconds to hours
            $hours = round($hours, 2); // Round to 2 decimal places
            
            // Create the overtime record
            $overtime = new OvertimePay();
            $overtime->employee_id = $request->employee_id;
            $overtime->date = $date;
            $overtime->time_in = $timeIn;
            $overtime->time_out = $timeOut;
            $overtime->overtime_rate = $request->overtime_rate;
            $overtime->approval_status = 'pending';
            $overtime->overtime_hours = $hours > 0 ? $hours : 0; // Set overtime_hours before saving
            $overtime->save();
            
            // Calculate overtime pay
            try {
                $overtime->calculateOvertimePay();
            } catch (\Exception $e) {
                // If calculation fails, log the error but continue
                Log::error('Overtime calculation error: ' . $e->getMessage());
            }
            
            return redirect()->route('overtime.index')
                ->with('success', 'Overtime created successfully.');
        } catch (\Exception $e) {
            Log::error('Overtime creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(OvertimePay $overtime)
    {
        // Check if user has the required role
        $user = Auth::user();
        if (!$user || !$user->roles->pluck('name')->intersect(['Super Admin', 'Admin', 'Finance', 'VP Finance', 'HR ComBen', 'Supervisor'])->count() > 0) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check user's role and mark as read accordingly
        if ($user) {
            try {
                // Handle read status based on user role
                $this->markAsReadBasedOnRole($user, $overtime);
            } catch (\Exception $e) {
                Log::error('Error marking overtime as read: ' . $e->getMessage());
            }
        }
        
        return view('overtime.show', compact('overtime'));
    }
    
    /**
     * Mark overtime as read based on user role
     * 
     * @param User $user
     * @param OvertimePay $overtime
     * @return void
     */
    protected function markAsReadBasedOnRole(User $user, OvertimePay $overtime): void
    {
        // VP Finance role check
        if ($user->roles->pluck('name')->contains('VP Finance')) {
            $overtime->is_read_by_vpfinance = true;
            $overtime->is_read_at_vpfinance = now();
            $overtime->save();
            
            Log::info('OvertimePay #' . $overtime->id . ' marked as read by VP Finance user #' . $user->id);
        } 
        // Finance Head role check
        elseif ($user->roles->pluck('name')->contains('Finance')) {
            $overtime->is_read_by_finance = true;
            $overtime->is_read_at_finance = now();
            $overtime->save();
            
            Log::info('OvertimePay #' . $overtime->id . ' marked as read by Finance Head user #' . $user->id);
        } 
        // Supervisor role check
        elseif ($user->roles->pluck('name')->contains('Supervisor')) {
            $overtime->is_read_by_supervisor = true;
            $overtime->is_read_at_supervisor = now();
            $overtime->save();
            
            Log::info('OvertimePay #' . $overtime->id . ' marked as read by Supervisor user #' . $user->id);
        } 
        // Employee role check - only mark as read if it belongs to the employee
        elseif ($user->roles->pluck('name')->contains('Employee') && 
                $user->employee && 
                $overtime->employee_id == $user->employee->id) {
            $overtime->is_read_by_employee = true;
            $overtime->is_read_at_employee = now();
            $overtime->save();
            
            Log::info('OvertimePay #' . $overtime->id . ' marked as read by Employee user #' . $user->id);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(OvertimePay $overtime)
    // {
    //     $employees =  Employee::all();
    //     return view('overtime.edit', compact('overtime', 'employees'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, OvertimePay $overtime)
    // {
    //     $overtime->update($request->all());
    //     return redirect()->route('overtime.index');
    // }

    /**
     * Approve the overtime pay by supervisor
     */
    public function approvedBySupervisor(OvertimePay $overtime)
    {
        $user = Auth::user();
        if($overtime->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This overtime record has already been processed.');
        }

        // Check if employee has rank "Rank File"
        if($overtime->employee->rank !== 'Rank File') {
            return redirect()->back()->with('error', 'Supervisor approval is only applicable for Rank File employees.');
        }

        // Check if supervisor email matches any employee email_address
        $isSupervisorWithEmployeeEmail = false;
        if($user->roles->pluck('name')->contains('Supervisor')) {
            $supervisorEmployee = Employee::where('email_address', $user->email)->first();
            $isSupervisorWithEmployeeEmail = $supervisorEmployee !== null;
        }

        // Allow supervisors with matching email to approve
        if($user->roles->pluck('name')->contains('Supervisor') && $isSupervisorWithEmployeeEmail) {
            $overtime->approval_status = 'approvedBySupervisor';
            $overtime->approveBySupervisor(Auth::id());
            $overtime->save();
            
            return redirect()->route('overtime.index')->with('success', 'Overtime approved by supervisor successfully.');
        }

        // Default case
        $overtime->approval_status = 'approvedBySupervisor';
        $overtime->approveBySupervisor(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime approved by supervisor successfully.');
    }

    /**
     * Reject the overtime pay by supervisor
     */
    public function rejectedBySupervisor(Request $request, OvertimePay $overtime)
    {
        $user = Auth::user();
        if($overtime->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This overtime record has already been processed.');
        }

        // Check if employee has rank "Rank File"
        if($overtime->employee->rank !== 'Rank File') {
            return redirect()->back()->with('error', 'Supervisor rejection is only applicable for Rank File employees.');
        }

        // Check if supervisor email matches any employee email_address
        $isSupervisorWithEmployeeEmail = false;
        if($user->roles->pluck('name')->contains('Supervisor')) {
            $supervisorEmployee = Employee::where('email_address', $user->email)->first();
            $isSupervisorWithEmployeeEmail = $supervisorEmployee !== null;
        }

        // Allow supervisors with matching email to reject
        if($user->roles->pluck('name')->contains('Supervisor') && $isSupervisorWithEmployeeEmail) {
            $overtime->approval_status = 'rejectedBySupervisor';
            $overtime->rejection_reason = $request->rejection_reason;
            $overtime->approveBySupervisor(Auth::id());
            $overtime->save();
            
            return redirect()->route('overtime.index')->with('success', 'Overtime rejected by supervisor.');
        }

        // Default case
        $overtime->approval_status = 'rejectedBySupervisor';
        $overtime->rejection_reason = $request->rejection_reason;
        $overtime->approveBySupervisor(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime rejected by supervisor.');
    }

    /**
     * Approve the night premium pay by finance head
     */
    public function approvedByFinance(OvertimePay $overtime)
    {
        // Check if employee has rank "Managerial"
        if($overtime->employee->rank === 'Managerial') {
            // For Managerial rank, this is the first approval step
            if($overtime->approval_status !== 'pending') {
                return redirect()->back()->with('error', 'This overtime record has already been processed.');
            }
        } else {
            // For other ranks, supervisor must approve first
            if($overtime->approval_status !== 'approvedBySupervisor') {
                return redirect()->back()->with('error', 'This overtime must be approved by supervisor first.');
            }
        }

        $overtime->approval_status = 'approvedByFinance';
        $overtime->approveByFinanceHead(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime approved by finance head successfully.');
    }

    /**
     * Reject the night premium pay by finance head
     */
    public function rejectedByFinance(Request $request, OvertimePay $overtime)
    {
        // Check if employee has rank "Managerial"
        if($overtime->employee->rank === 'Managerial') {
            // For Managerial rank, this is the first approval step
            if($overtime->approval_status !== 'pending') {
                return redirect()->back()->with('error', 'This overtime record has already been processed.');
            }
        } else {
            // For other ranks, supervisor must approve first
            if($overtime->approval_status !== 'approvedBySupervisor') {
                return redirect()->back()->with('error', 'This overtime must be approved by supervisor first.');
            }
        }

        $overtime->approval_status = 'rejectedByFinance';
        $overtime->rejection_reason = $request->rejection_reason;
        $overtime->approveByFinanceHead(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime rejected by finance head.');
    }

    /**
     * Approve the night premium pay by VP finance
     */
    public function approvedByVPFinance(OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'approvedByFinance') {
            return redirect()->back()->with('error', 'This overtime must be approved by finance head first.');
        }

        $overtime->approval_status = 'approvedByVPFinance';
        $overtime->approveByVpFinance(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime approved by VP finance successfully.');
    }

    /**
     * Reject the night premium pay by VP finance
     */
    public function rejectedByVPFinance(Request $request, OvertimePay $overtime)
    {
        if($overtime->approval_status !== 'approvedByFinance') {
            return redirect()->back()->with('error', 'This overtime must be approved by finance head first.');
        }

        $overtime->approval_status = 'rejectedByVPFinance';
        $overtime->rejection_reason = $request->rejection_reason;
        $overtime->approveByVpFinance(Auth::id());
        $overtime->save();
        
        return redirect()->route('overtime.index')->with('success', 'Overtime rejected by VP finance.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OvertimePay $overtime)
    {
        // Check if user has the required role
        $user = Auth::user();
        if (!$user || !$user->roles->pluck('name')->intersect(['Super Admin', 'Admin', 'Finance', 'VP Finance', 'HR ComBen'])->count() > 0) {
            abort(403, 'Unauthorized action.');
        }

        $overtime->delete();
        return redirect()->route('overtime.index')->with('success', 'Overtime record has been deleted successfully.');
    }

    /**
     * Allow employee to apply for overtime
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function applyForOvertime(Request $request)
    {
        // Check if user is authenticated
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // Allow access for users with Employee role or Supervisor role with matching email
        $hasEmployeeRole = $user->roles->pluck('name')->contains('Employee');
        $isSupervisorWithEmployeeEmail = false;
        
        if ($user->roles->pluck('name')->contains('Supervisor')) {
            $supervisorEmployee = Employee::where('email_address', $user->email)->first();
            $isSupervisorWithEmployeeEmail = $supervisorEmployee !== null;
        }

        if (!$hasEmployeeRole && !$isSupervisorWithEmployeeEmail) {
            abort(403, 'Unauthorized action.');
        }

        // Find employee based on user email
        $employee = Employee::where('email_address', $user->email)->first();
        if (!$employee) {
            return redirect()->back()->with('error', 'No employee record found matching your email.');
        }

        // Show the apply form if this is a GET request
        if ($request->isMethod('get')) {
            return view('overtime.apply', compact('employee'));
        }

        // Process the application if this is a POST request
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time_in' => 'required|date_format:Y-m-d\TH:i',
            'time_out' => 'required|date_format:Y-m-d\TH:i|after:time_in',
            'reason' => 'required|string|min:10|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Start DB transaction
            DB::beginTransaction();
            
            // Format the datetimes properly
            $date = date('Y-m-d', strtotime($request->date));
            $timeIn = date('Y-m-d H:i:s', strtotime($request->time_in));
            $timeOut = date('Y-m-d H:i:s', strtotime($request->time_out));
            
            // Verify time_in date matches the date field
            $timeInDate = date('Y-m-d', strtotime($timeIn));
            if ($timeInDate !== $date) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['time_in' => 'Time in date must match the overtime date']);
            }
            
            // Calculate the time difference in hours
            $timeDiff = strtotime($timeOut) - strtotime($timeIn);
            $hours = $timeDiff / 3600; // Convert seconds to hours
            $hours = round($hours, 2); // Round to 2 decimal places
            
            if ($hours <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['time_out' => 'Overtime period must be greater than zero hours']);
            }
            
            // Check for duplicate overtime application
            $existingOvertime = OvertimePay::where('employee_id', $employee->id)
                ->where('date', $date)
                ->where(function($query) use ($timeIn, $timeOut) {
                    $query->where(function($q) use ($timeIn, $timeOut) {
                        $q->where('time_in', '<=', $timeIn)
                          ->where('time_out', '>=', $timeIn);
                    })->orWhere(function($q) use ($timeIn, $timeOut) {
                        $q->where('time_in', '<=', $timeOut)
                          ->where('time_out', '>=', $timeOut);
                    })->orWhere(function($q) use ($timeIn, $timeOut) {
                        $q->where('time_in', '>=', $timeIn)
                          ->where('time_out', '<=', $timeOut);
                    });
                })->first();
                
            if ($existingOvertime) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'An overlapping overtime request already exists for this date and time range']);
            }
            
            // Set default overtime rate based on company policy
            // This can be adjusted based on business needs
            $overtimeRate = 1.25; // Example: 1.25x regular pay
            
            // Create the overtime record
            $overtime = new OvertimePay();
            $overtime->employee_id = $employee->id;
            $overtime->date = $date;
            $overtime->time_in = $timeIn;
            $overtime->time_out = $timeOut;
            $overtime->overtime_rate = $overtimeRate;
            $overtime->approval_status = 'pending';
            $overtime->overtime_hours = $hours > 0 ? $hours : 0;
            $overtime->reason = $request->reason;
            $overtime->save();
            
            // Calculate overtime pay
            try {
                $overtime->calculateOvertimePay();
            } catch (\Exception $e) {
                // If calculation fails, log the error but continue
                Log::error('Overtime calculation error: ' . $e->getMessage(), [
                    'employee_id' => $employee->id,
                    'overtime_id' => $overtime->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            // Commit transaction
            DB::commit();
            
            // Log successful application
            Log::info('Overtime application submitted', [
                'employee_id' => $employee->id,
                'overtime_id' => $overtime->id,
                'date' => $date,
                'hours' => $hours
            ]);
            
            return redirect()->route('overtime.history')
                ->with('success', 'Overtime application submitted successfully. Waiting for approval.');
        } catch (\Exception $e) {
            // Roll back transaction on error
            DB::rollBack();
            
            Log::error('Overtime application error: ' . $e->getMessage(), [
                'employee_id' => $employee->id ?? 'unknown',
                'user_id' => $user->id,
                'input' => $request->except(['_token']),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Display overtime history for the authenticated employee
     * 
     * @return \Illuminate\Http\Response
     */
    public function employeeOvertimeHistory()
    {
        // Check if user is authenticated
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // Allow access for users with Employee role or Supervisor role with matching email
        $hasEmployeeRole = $user->roles->pluck('name')->contains('Employee');
        $isSupervisorWithEmployeeEmail = false;
        
        if ($user->roles->pluck('name')->contains('Supervisor')) {
            $supervisorEmployee = Employee::where('email_address', $user->email)->first();
            $isSupervisorWithEmployeeEmail = $supervisorEmployee !== null;
        }

        if (!$hasEmployeeRole && !$isSupervisorWithEmployeeEmail) {
            abort(403, 'Unauthorized action.');
        }

        // Find employee based on user email
        $employee = Employee::where('email_address', $user->email)->first();
        if (!$employee) {
            return redirect()->back()->with('error', 'No employee record found matching your email.');
        }

        // Get overtime records for this employee
        $overtimeRecords = OvertimePay::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculate overtime pay for each record
        foreach ($overtimeRecords as $overtimePay) {
            $overtimePay->calculateOvertimePay();
        }

        return view('overtime.employee-history', compact('overtimeRecords', 'employee'));
    }
}
