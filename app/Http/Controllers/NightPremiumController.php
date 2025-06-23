<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\NightPremium;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NightPremiumController extends Controller
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

        // Fetch all NightPremium records
        $nightPremiums = NightPremium::all();

        // Calculate night premium pay for each record
        foreach ($nightPremiums as $nightPremium) {
            $nightPremium->calculateNightPremiumPay();
        }

        // Pass the data to the view
        return view('night-premium.index', compact('nightPremiums'));
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
        return view('night-premium.create', compact('employees'));
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
            'night_rate' => 'required|numeric|min:1',
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
                    ->withErrors(['time_in' => 'Time in date must match the night premium date']);
            }
            
            // Calculate the time difference in hours (before saving)
            $timeDiff = strtotime($timeOut) - strtotime($timeIn);
            $hours = $timeDiff / 3600; // Convert seconds to hours
            $hours = round($hours, 2); // Round to 2 decimal places
            
            // Create the night premium record
            $nightPremium = new NightPremium();
            $nightPremium->employee_id = $request->employee_id;
            $nightPremium->date = $date;
            $nightPremium->time_in = $timeIn;
            $nightPremium->time_out = $timeOut;
            $nightPremium->night_rate = $request->night_rate;
            $nightPremium->approval_status = 'pending';
            $nightPremium->night_hours = $hours > 0 ? $hours : 0; // Set night_hours before saving
            $nightPremium->save();
            
            // Calculate night premium pay
            try {
                $nightPremium->calculateNightPremiumPay();
            } catch (\Exception $e) {
                // If calculation fails, log the error but continue
                Log::error('Night premium calculation error: ' . $e->getMessage());
            }
            
            return redirect()->route('night-premium.index')
                ->with('success', 'Night premium created successfully.');
        } catch (\Exception $e) {
            Log::error('Night premium creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(NightPremium $nightPremium)
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
                $this->markAsReadBasedOnRole($user, $nightPremium);
            } catch (\Exception $e) {
                Log::error('Error marking night premium as read: ' . $e->getMessage());
            }
        }
        
        return view('night-premium.show', compact('nightPremium'));
    }
    
    /**
     * Mark overtime as read based on user role
     * 
     * @param User $user
     * @param OvertimePay $overtime
     * @return void
     */
    protected function markAsReadBasedOnRole(User $user, NightPremium $nightPremium): void
    {
        // VP Finance role check
        if ($user->roles->pluck('name')->contains('VP Finance')) {
            $nightPremium->is_read_by_vpfinance = true;
            $nightPremium->is_read_at_vpfinance = now();
            $nightPremium->save();
            
            Log::info('NightPremium #' . $nightPremium->id . ' marked as read by VP Finance user #' . $user->id);
        } 
        // Finance Head role check
        elseif ($user->roles->pluck('name')->contains('Finance')) {
            $nightPremium->is_read_by_finance = true;
            $nightPremium->is_read_at_finance = now();
            $nightPremium->save();
            
            Log::info('NightPremium #' . $nightPremium->id . ' marked as read by Finance Head user #' . $user->id);
        } 
        // Supervisor role check
        elseif ($user->roles->pluck('name')->contains('Supervisor')) {
            $nightPremium->is_read_by_supervisor = true;
            $nightPremium->is_read_at_supervisor = now();
            $nightPremium->save();
            
            Log::info('NightPremium #' . $nightPremium->id . ' marked as read by Supervisor user #' . $user->id);
        } 
        // Employee role check - only mark as read if it belongs to the employee
        elseif ($user->roles->pluck('name')->contains('Employee') && 
                $user->employee && 
                $nightPremium->employee_id == $user->employee->id) {
            $nightPremium->is_read_by_employee = true;
            $nightPremium->is_read_at_employee = now();
            $nightPremium->save();
            
            Log::info('NightPremium #' . $nightPremium->id . ' marked as read by Employee user #' . $user->id);
        }
    }

 

       /**
     * Approve the night premium pay by supervisor
     */
    public function approvedBySupervisor(NightPremium $nightPremium)
    {
        $user = Auth::user();
        if($nightPremium->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This night premium record has already been processed.');
        }

        // Check if employee has rank "Rank File"
        if($nightPremium->employee->rank !== 'Rank File') {
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
            $nightPremium->approval_status = 'approvedBySupervisor';
            $nightPremium->approveBySupervisor(Auth::id());
            $nightPremium->save();
            
            return redirect()->route('night-premium.index')->with('success', 'Night premium approved by supervisor successfully.');
        }

        // Default case
        $nightPremium->approval_status = 'approvedBySupervisor';
        $nightPremium->approveBySupervisor(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium approved by supervisor successfully.');
    }

    /**
     * Reject the night premium pay by supervisor
     */
    public function rejectedBySupervisor(Request $request, NightPremium $nightPremium)
    {
        $user = Auth::user();
        if($nightPremium->approval_status !== 'pending') {
            return redirect()->back()->with('error', 'This night premium record has already been processed.');
        }

        // Check if employee has rank "Rank File"
        if($nightPremium->employee->rank !== 'Rank File') {
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
            $nightPremium->approval_status = 'rejectedBySupervisor';
            $nightPremium->rejection_reason = $request->rejection_reason;
            $nightPremium->approveBySupervisor(Auth::id());
            $nightPremium->save();
            
            return redirect()->route('night-premium.index')->with('success', 'Night premium rejected by supervisor.');
        }

        // Default case
        $nightPremium->approval_status = 'rejectedBySupervisor';
        $nightPremium->rejection_reason = $request->rejection_reason;
        $nightPremium->approveBySupervisor(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium rejected by supervisor.');
    }

    /**
     * Approve the night premium pay by finance head
     */
    public function approvedByFinance(NightPremium $nightPremium)
    {
        // Check if employee has rank "Managerial"
        if($nightPremium->employee->rank === 'Managerial') {
            // For Managerial rank, this is the first approval step
            if($nightPremium->approval_status !== 'pending') {
                return redirect()->back()->with('error', 'This night premium record has already been processed.');
            }
        } else {
            // For other ranks, supervisor must approve first
            if($nightPremium->approval_status !== 'approvedBySupervisor') {
                return redirect()->back()->with('error', 'This night premium must be approved by supervisor first.');
            }
        }

        $nightPremium->approval_status = 'approvedByFinance';
        $nightPremium->approveByFinanceHead(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium approved by finance head successfully.');
    }

    /**
     * Reject the night premium pay by finance head
     */
    public function rejectedByFinance(Request $request, NightPremium $nightPremium)
    {
        // Check if employee has rank "Managerial"
        if($nightPremium->employee->rank === 'Managerial') {
            // For Managerial rank, this is the first approval step
            if($nightPremium->approval_status !== 'pending') {
                return redirect()->back()->with('error', 'This night premium record has already been processed.');
            }
        } else {
            // For other ranks, supervisor must approve first
            if($nightPremium->approval_status !== 'approvedBySupervisor') {
                return redirect()->back()->with('error', 'This night premium must be approved by supervisor first.');
            }
        }

        $nightPremium->approval_status = 'rejectedByFinance';
        $nightPremium->rejection_reason = $request->rejection_reason;
        $nightPremium->approveByFinanceHead(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium rejected by finance head.');
    }

    /**
     * Approve the night premium pay by VP finance
     */
    public function approvedByVPFinance(NightPremium $nightPremium)
    {
        if($nightPremium->approval_status !== 'approvedByFinance') {
            return redirect()->back()->with('error', 'This night premium must be approved by finance head first.');
        }

        $nightPremium->approval_status = 'approvedByVPFinance';
        $nightPremium->approveByVpFinance(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium approved by VP finance successfully.');
    }

    /**
     * Reject the night premium pay by VP finance
     */
    public function rejectedByVPFinance(Request $request, NightPremium $nightPremium)
    {
        if($nightPremium->approval_status !== 'approvedByFinance') {
            return redirect()->back()->with('error', 'This night premium must be approved by finance head first.');
        }

        $nightPremium->approval_status = 'rejectedByVPFinance';
        $nightPremium->rejection_reason = $request->rejection_reason;
        $nightPremium->approveByVpFinance(Auth::id());
        $nightPremium->save();
        
        return redirect()->route('night-premium.index')->with('success', 'Night premium rejected by VP finance.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NightPremium $nightPremium)
    {
        // Check if user has the required role
        $user = Auth::user();
        if (!$user || !$user->roles->pluck('name')->intersect(['Super Admin', 'Admin', 'Finance', 'VP Finance', 'HR ComBen'])->count() > 0) {
            abort(403, 'Unauthorized action.');
        }

        $nightPremium->delete();
        return redirect()->route('night-premium.index')->with('success', 'Night premium record has been deleted successfully.');
    }

    /**
     * Allow employee to apply for night premium
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function applyForNightPremium(Request $request)
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
            return view('night-premium.apply', compact('employee'));
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
                    ->withErrors(['time_in' => 'Time in date must match the night premium date']);
            }
            
            // Calculate the time difference in hours
            $timeDiff = strtotime($timeOut) - strtotime($timeIn);
            $hours = $timeDiff / 3600; // Convert seconds to hours
            $hours = round($hours, 2); // Round to 2 decimal places
            
            if ($hours <= 0) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['time_out' => 'Night premium period must be greater than zero hours']);
            }
            
            // Check for duplicate night premium application
            $existingNightPremium = NightPremium::where('employee_id', $employee->id)
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
                
            if ($existingNightPremium) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'An overlapping night premium request already exists for this date and time range']);
            }
            
            // Check if night hours are between 10:00 PM and 6:00 AM
            $timeInObj = new \DateTime($timeIn);
            $timeOutObj = new \DateTime($timeOut);
            
            $startNight = clone $timeInObj;
            $startNight->setTime(22, 0); // 10:00 PM
            $endNight = clone $timeInObj;
            $endNight->modify('+1 day');
            $endNight->setTime(6, 0); // 6:00 AM next day
            
            // Check if the shift has any overlap with night premium hours
            $hasNightHours = false;
            
            // Case 1: timeIn is before 10PM and timeOut is after 10PM
            if ($timeInObj <= $startNight && $timeOutObj >= $startNight) {
                $hasNightHours = true;
            }
            // Case 2: timeIn is between 10PM and 6AM
            elseif ($timeInObj >= $startNight && $timeInObj <= $endNight) {
                $hasNightHours = true;
            }
            // Case 3: timeOut is between 10PM and 6AM
            elseif ($timeOutObj >= $startNight && $timeOutObj <= $endNight) {
                $hasNightHours = true;
            }
            
            if (!$hasNightHours) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Night premium applies only for hours worked between 10:00 PM and 6:00 AM.']);
            }
            
            // Set default night premium rate based on company policy
            // This can be adjusted based on business needs
            $nightRate = 1.10; // Example: 1.10x regular pay
            
            // Create the night premium record
            $nightPremium = new NightPremium();
            $nightPremium->employee_id = $employee->id;
            $nightPremium->date = $date;
            $nightPremium->time_in = $timeIn;
            $nightPremium->time_out = $timeOut;
            $nightPremium->night_rate = $nightRate;
            $nightPremium->approval_status = 'pending';
            $nightPremium->night_hours = $hours > 0 ? $hours : 0;
            $nightPremium->reason = $request->reason;
            $nightPremium->save();
            
            // Calculate night premium pay
            try {
                $nightPremium->calculateNightPremiumPay();
            } catch (\Exception $e) {
                // If calculation fails, log the error but continue
                Log::error('Night premium calculation error: ' . $e->getMessage(), [
                    'employee_id' => $employee->id,
                    'night_premium_id' => $nightPremium->id,
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            // Commit transaction
            DB::commit();
            
            // Log successful application
            Log::info('Night premium application submitted', [
                'employee_id' => $employee->id,
                'night_premium_id' => $nightPremium->id,
                'date' => $date,
                'hours' => $hours
            ]);
            
            return redirect()->route('night-premium.history')
                ->with('success', 'Night premium application submitted successfully. Waiting for approval.');
        } catch (\Exception $e) {
            // Roll back transaction on error
            DB::rollBack();
            
            Log::error('Night premium application error: ' . $e->getMessage(), [
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
     * Display night premium history for the authenticated employee
     * 
     * @return \Illuminate\Http\Response
     */
    public function employeeNightPremiumHistory()
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

        // Get night premium records for this employee
        $nightPremiumRecords = NightPremium::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculate night premium pay for each record
        foreach ($nightPremiumRecords as $nightPremium) {
            $nightPremium->calculateNightPremiumPay();
        }

        return view('night-premium.employee-history', compact('nightPremiumRecords', 'employee'));
    }
}
