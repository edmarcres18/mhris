<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\Type;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:leave-list|leave-create|leave-edit|leave-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:leave-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:leave-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:leave-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->hasRole('Supervisor')) {
            $leaves = Leave::whereHas('employee', function($query) {
                $query->where('department_id', auth()->user()->department_id);
            })->get();
            $employees = Employee::where('department_id', auth()->user()->department_id)->get();
        } else {
            $leaves = Leave::all();
            $employees = Employee::all();
        }
        
        return view('leaves.index', compact('leaves', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if the authenticated user has the 'Employee' role
        if (auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor')) {
            // Retrieve only the employee with the same email address as the authenticated user
            $employees = Employee::where('email_address', auth()->user()->email)
                                 ->where('employee_status', 'Active')
                                 ->get();
        } else {
            // For other roles, retrieve all active employees
            $employees = Employee::where('employee_status', 'Active')->get();
        }

        $types = Type::all();
        return view('leaves.create', compact('employees', 'types'));
    }

    /**
     * Store a newly created leave request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'leave_type' => 'required|string|in:Leave,Undertime,Halfday',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'type_id' => 'required|exists:types,id',
            'reason_to_leave' => 'required|string|max:1000',
            'approved_by' => 'nullable|integer|exists:users,id',
            'signature' => 'required|string',
        ]);

        // Add new validation for vacation leave
        if ($validatedData['leave_type'] === 'Leave' && $validatedData['type_id'] == 1) {
            $dateFrom = \Carbon\Carbon::parse($validatedData['date_from']);
            $twoWeeksFromNow = now()->addWeeks(2);

            if ($dateFrom->lessThan($twoWeeksFromNow)) {
                return response()->json([
                    'errors' => [
                        'date_from' => ['Vacation leave must be filed at least 2 weeks before the start date.']
                    ]
                ], 422);
            }
        }

        try {
            // Get employee details first
            $employee = Employee::findOrFail($validatedData['employee_id']);

            // Check if employee has a signature
            if (!$employee->signature) {
                return response()->json([
                    'errors' => [
                        'signature' => ['No signature found. Please update your signature in your profile.']
                    ]
                ], 422);
            }

            // Create leave record with employee's signature
            $leave = Leave::create([
                'employee_id' => $validatedData['employee_id'],
                'leave_type' => $validatedData['leave_type'],
                'date_from' => $validatedData['date_from'],
                'date_to' => $validatedData['date_to'],
                'type_id' => $validatedData['type_id'],
                'status' => 'pending',
                'reason_to_leave' => $validatedData['reason_to_leave'],
                'signature' => $employee->signature,
            ]);

            // Calculate payment status after creation
            $leave->payment_status = $leave->getLeavePaymentStatus();
            $leave->save();

            // Redirect with success message
            if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Admin')) {
                return response()->json([
                    'message' => 'Leave request created successfully',
                    'status' => 'success'
                ]);
            }
            elseif (auth()->user()->hasRole('Supervisor')) {
                return response()->json([
                    'message' => 'Leave request sent successfully. Wait for the Admin confirmation.',
                    'status' => 'success'
                ]);
            }
            else {
                return response()->json([
                    'message' => 'Leave request sent successfully. Wait for the Supervisor confirmation.',
                    'status' => 'success'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'errors' => ['error' => ['Failed to create leave request: ' . $e->getMessage()]]
            ], 422);
        }
    }

    public function show($id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $employees = Employee::all();
            if (!Auth::user()->hasRole('Employee')) {
                $this->markAsRead($leave);
            } else {
                $this->markAsView($leave);
            }
            // Calculate leave balances up to this leave ID
            $vacationTaken = $this->calculateLeaveTaken($leave->employee->id, 1, $id);
            $emergencyTaken = $this->calculateLeaveTaken($leave->employee->id, 2, $id);
            $sickTaken = $this->calculateLeaveTaken($leave->employee->id, 3, $id);

            // Calculate current leave days
            $currentLeaveDays = $this->calculateCurrentLeaveDays($leave);

            // Check if taken days are within limits and update payment status
            if ($leave->employee->employment_status === 'REGULAR') {
                $isWithinLimits = match($leave->type_id) {
                    1 => $vacationTaken <= $leave->employee->vacation_leave,
                    2 => $emergencyTaken <= $leave->employee->emergency_leave,
                    3 => $sickTaken <= $leave->employee->sick_leave,
                    default => false
                };

                // Only update payment status if the leave hasn't been approved yet
                if (!$leave->approved_by) {
                    $leave->payment_status = $isWithinLimits ? 'With Pay' : 'Without Pay';
                    $leave->save();
                }
            } else {
                // For non-regular employees, always set to Without Pay if not yet approved
                if (!$leave->approved_by) {
                    $leave->payment_status = 'Without Pay';
                    $leave->save();
                }
            }

            // Calculate balances
            $vacationBalance = max(0, 5 - $vacationTaken);
            $emergencyBalance = max(0, 3 - $emergencyTaken);
            $sickBalance = max(0, 7 - $sickTaken);

            $diff = $this->calculateCurrentLeaveDays($leave);
            $approvedByUser = $leave->approvedByUser;

            return view('leaves.show', compact(
                'leave',
                'approvedByUser',
                'diff',
                'employees',
                'vacationTaken',
                'sickTaken',
                'emergencyTaken',
                'vacationBalance',
                'sickBalance',
                'emergencyBalance'
            ));
        } catch (\Exception $e) {
            \Log::error('Leave Show Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    private function markAsRead(Leave $leave)
    {
        DB::beginTransaction();
        try {
            $leave->is_read = true;
            $leave->read_at = now();
            $leave->save();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to mark leave #' . $leave->id . ' as read: ' . $e->getMessage());
            throw $e;
        }
    }

    private function markAsView(Leave $leave)
    {
        DB::beginTransaction();
        try {
            $leave->is_view = true;
            $leave->view_at = now();
            $leave->save();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to mark leave #' . $leave->id . ' as viewed: ' . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $leave = Leave::findOrFail($id);
        $employees = Employee::where('employee_status', 'Active')->get();
        $types = Type::all();
        return view('leaves.edit', compact('leave', 'employees', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        // Validate the request data
        $validatedData = $request->validate([
            'employee_id' => 'required|integer',
            'leave_type' => 'required|string|in:Leave,Undertime,Halfday',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'type_id' => 'required',
            'reason_to_leave' => 'required|string',
            'approved_by' => 'nullable|integer',
        ]);

        // Determine the payment status based on employee's employment status
        $employee = Employee::findOrFail($validatedData['employee_id']);
        $validatedData['payment_status'] = $employee->employment_status === 'REGULAR' ? 'With Pay' : 'Without Pay';

        // Find the leave request and update it with the validated data
        $leave = Leave::findOrFail($id);
        $leave->update($validatedData);

        // Redirect to the leaves.index view with a success message
        return redirect()->route('leaves.index')->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $leave = Leave::findOrFail($id);
        $leave->delete();

        return redirect()->route('leaves.index')->with('success', 'Leave request deleted successfully.');
    }
    public function updateStatus(Request $request, string $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'status' => 'required|in:pending,rejected,approved',
            'approved_by_signature' => 'required_if:status,approved|nullable|string',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
        ]);

        // Find the leave request
        $leave = Leave::findOrFail($id);
        $employee = $leave->employee;

        try {
            // Process approved_by signature
            $approvedSignatureFileName = null;

            if ($validatedData['status'] === 'approved' && $request->filled('approved_by_signature')) {
                $signatureData = $request->input('approved_by_signature');
                $approvedSignatureFileName = $this->processSignature($signatureData, 'approved');
            }

            // Update leave status and related fields
            $leave->status = $validatedData['status'];
            
            if ($validatedData['status'] === 'approved') {
                $leave->approved_by = Auth::id();
                $leave->approved_by_signature = $approvedSignatureFileName;
                $leave->rejected_by = null;
                $leave->rejected_at = null;
                $leave->rejection_reason = null;
                
                // Calculate hours to deduct
                $dateFrom = \Carbon\Carbon::parse($leave->date_from);
                $dateTo = \Carbon\Carbon::parse($leave->date_to);
                $diffInMinutes = $dateFrom->diffInMinutes($dateTo);
                $hoursToDeduct = $diffInMinutes / 60; // Convert minutes to hours

                // Deduct leave hours based on type
                if ($employee->employment_status === 'REGULAR') {
                    switch ($leave->type_id) {
                        case 1: // Vacation Leave
                            if ($employee->vacation_leave >= $hoursToDeduct) {
                                $employee->vacation_leave -= $hoursToDeduct;
                                $leave->payment_status = 'With Pay';
                            } else {
                                $leave->payment_status = 'Without Pay';
                            }
                            break;

                        case 2: // Emergency Leave
                            if ($employee->emergency_leave >= $hoursToDeduct) {
                                $employee->emergency_leave -= $hoursToDeduct;
                                $leave->payment_status = 'With Pay';
                            } else {
                                $leave->payment_status = 'Without Pay';
                            }
                            break;

                        case 3: // Sick Leave
                            if ($employee->sick_leave >= $hoursToDeduct) {
                                $employee->sick_leave -= $hoursToDeduct;
                                $leave->payment_status = 'With Pay';
                            } else {
                                $leave->payment_status = 'Without Pay';
                            }
                            break;

                        default:
                            $leave->payment_status = 'Without Pay';
                    }

                    // Save the updated employee leave balances
                    $employee->save();
                } else {
                    // For non-regular employees
                    $leave->payment_status = 'Without Pay';
                }
                
            } elseif ($validatedData['status'] === 'rejected') {
                $leave->rejected_by = Auth::id();
                $leave->rejected_at = now();
                $leave->rejection_reason = $request->input('rejection_reason');
                $leave->approved_by = null;
                $leave->approved_by_signature = null;
                $leave->payment_status = null;
            }

            $leave->save();

            // Prepare success message
            $message = $validatedData['status'] === 'approved' 
                ? 'Leave request has been approved successfully. Leave balances have been updated.'
                : 'Leave request has been rejected successfully.';

            // Log the leave balance update
            if ($validatedData['status'] === 'approved') {
                \Log::info('Leave balance update for Employee #' . $employee->id, [
                    'leave_id' => $leave->id,
                    'type' => $leave->type->name,
                    'hours_deducted' => $hoursToDeduct,
                    'new_balance' => [
                        'vacation_leave' => $employee->vacation_leave,
                        'emergency_leave' => $employee->emergency_leave,
                        'sick_leave' => $employee->sick_leave,
                    ]
                ]);
            }

            return redirect()->route('leaves.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            // Clean up signature file if it exists
            if ($approvedSignatureFileName && Storage::disk('public')->exists($approvedSignatureFileName)) {
                Storage::disk('public')->delete($approvedSignatureFileName);
            }

            \Log::error('Leave status update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update leave status: ' . $e->getMessage()]);
        }
    }

    // Add these helper methods
    private function processSignature($signatureData, $prefix)
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $signatureData)) {
            throw new \Exception('Invalid signature format');
        }

        $signatureImage = substr($signatureData, strpos($signatureData, ',') + 1);
        $decodedImage = base64_decode($signatureImage);

        if ($decodedImage === false) {
            throw new \Exception('Failed to decode signature');
        }

        $signatureFileName = "signatures/{$prefix}-" . uniqid() . '.png';
        Storage::disk('public')->makeDirectory('signatures');

        if (!Storage::disk('public')->put($signatureFileName, $decodedImage)) {
            throw new \Exception('Failed to save signature');
        }

        return $signatureFileName;
    }

    private function cleanupSignatureFiles($fileNames)
    {
        foreach ($fileNames as $fileName) {
            if ($fileName && Storage::disk('public')->exists($fileName)) {
                Storage::disk('public')->delete($fileName);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function detail($id)
    {
        // Find the leave by its ID
        $leave = Leave::findOrFail($id);

        // Load any related models if needed
        // $leave->load('relationName');

        // Pass the $leave object to the view
        return view('leaves.detail', compact('leave'));
    }

     /**
         * Display a listing of all employees with action to view leaves.
         */
        public function allEmployees(Request $request)
        {
            $departmentId = $request->input('department_id');
            $departments = Department::all();

            if ($departmentId) {
                $employees = Employee::where('department_id', $departmentId)
                    ->where('employee_status', 'Active')
                    ->get();
            } else {
                $employees = Employee::where('employee_status', 'Active')->get();
            }

            return view('leaves.all_employees', compact('employees', 'departments', 'departmentId'));
        }

        /**
         * Display leaves for a specific employee and provide a downloadable PDF.
         */
        public function employeeLeaves($employee_id)
        {
            $employee = Employee::findOrFail($employee_id);
            $leaves = Leave::where('employee_id', $employee_id)->get();

            // Check if the request is for downloading the PDF
            if (request()->has('download')) {
                $pdf = PDF::loadView('leaves.pdf', compact('employee', 'leaves'));
                return $pdf->download('employee_leaves.pdf');
            }

            return view('leaves.employee_leaves', compact('employee', 'leaves'));
        }

        public function report(Request $request)
    {
        $departmentId = $request->input('department_id');
        $departments = Department::all();

        if ($departmentId) {
            $leaves = Leave::whereHas('employee', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })->get();
        } else {
            $leaves = Leave::all();
        }

        // Check if the request is for downloading the PDF
        if ($request->has('download')) {
            $pdf = PDF::loadView('leaves.report_pdf', compact('leaves', 'departments', 'departmentId'));
            return $pdf->download('all_leaves_report.pdf');
        }

        return view('leaves.report', compact('leaves', 'departments', 'departmentId'));
    }

        /**
         * Display the leave sheet and balances for the authenticated employee.
         */
        public function myLeaveSheet()
        {
            $user = Auth::user();
            $employee = Employee::where('email_address', $user->email)->first();

            if ($employee) {
                $leaves = Leave::where('employee_id', $employee->id)->get();
                $sickLeaveBalance = $employee->sick_leave;
                $emergencyLeaveBalance = $employee->emergency_leave;
                $vacationLeaveBalance = $employee->vacation_leave;

                return view('leaves.my_leave_sheet', compact('employee', 'leaves', 'sickLeaveBalance', 'emergencyLeaveBalance', 'vacationLeaveBalance'));
            } else {
                return redirect()->route('leaves.index')->with('error', 'Employee not found.');
            }
        }

        /**
         * Display the leave details for the authenticated employee.
         */
        public function myLeaveDetail($id)
        {
            try {
                $leave = Leave::findOrFail($id);
                $employees = Employee::all();
                $this->markAsRead($leave);

                // Calculate leave balances up to this leave ID
                $vacationTaken = $this->calculateLeaveTaken($leave->employee->id, 1, $id);
                $sickTaken = $this->calculateLeaveTaken($leave->employee->id, 2, $id);
                $emergencyTaken = $this->calculateLeaveTaken($leave->employee->id, 3, $id);

                // Calculate current leave days
                $currentLeaveDays = $this->calculateCurrentLeaveDays($leave);

                // Check if taken days are within limits and update payment status
                if ($leave->employee->employment_status === 'REGULAR') {
                    $isWithinLimits = match($leave->type_id) {
                        1 => $vacationTaken <= $leave->employee->vacation_leave,
                        2 => $sickTaken <= $leave->employee->sick_leave,
                        3 => $emergencyTaken <= $leave->employee->emergency_leave,
                        default => false
                    };

                    // Only update payment status if the leave hasn't been approved yet
                    if (!$leave->approved_by) {
                        $leave->payment_status = $isWithinLimits ? 'With Pay' : 'Without Pay';
                        $leave->save();
                    }
                } else {
                    // For non-regular employees, always set to Without Pay if not yet approved
                    if (!$leave->approved_by) {
                        $leave->payment_status = 'Without Pay';
                        $leave->save();
                    }
                }

                // Calculate balances
                $vacationBalance = max(0, 5 - $vacationTaken);
                $sickBalance = max(0, 7 - $sickTaken);
                $emergencyBalance = max(0, 3 - $emergencyTaken);

                $diff = $this->calculateCurrentLeaveDays($leave);
                $approvedByUser = $leave->approvedByUser;

                return view('leaves.my_leave_detail', compact(
                    'leave',
                    'approvedByUser',
                    'diff',
                    'employees',
                    'vacationTaken',
                    'sickTaken',
                    'emergencyTaken',
                    'vacationBalance',
                    'sickBalance',
                    'emergencyBalance'
                ));
            } catch (\Exception $e) {
                \Log::error('Leave Show Error: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
                throw $e;
            }
        }

        public function updateValidation(Request $request, string $id)
        {
            // Validate the request data
            $validatedData = $request->validate([
                'validated_by_signature' => 'required|string',
            ]);

            // Find the leave request
            $leave = Leave::findOrFail($id);

            try {
                // Process validated_by signature
                if ($request->filled('validated_by_signature')) {
                    $signatureData = $request->input('validated_by_signature');
                    $validatedSignatureFileName = $this->processSignature($signatureData, 'validated');

                    // Update leave with validation signature
                    $leave->validated_by_signature = $validatedSignatureFileName;
                    $leave->save();
                }

                return redirect()->route('leaves.show', $id)
                    ->with('success', 'Leave validation signature added successfully.');

            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Failed to add validation signature: ' . $e->getMessage()]);
            }
        }

    private function calculateLeaveTaken($employeeId, $typeId, $currentLeaveId)
    {
        $leaves = Leave::where('employee_id', $employeeId)
            ->where('type_id', $typeId)
            ->where('id', '<=', $currentLeaveId)
            ->where('status', 'approved')
            ->get();

        $total = 0;
        foreach ($leaves as $leave) {
            $days = $this->calculateCurrentLeaveDays($leave);
            if (is_array($days)) {
                // Convert hours and minutes to days with 2 decimal places
                $daysValue = ($days['hours'] / 24) + ($days['minutes'] / (24 * 60));
                $total += round($daysValue, 2);
            } else {
                $total += $days;
            }
        }

        // Ensure final total also has 2 decimal places
        return round($total, 2);
    }

    private function calculateCurrentLeaveDays($leave)
    {
        if ($leave->status !== 'approved') {
            return 0;
        }

        $dateFrom = \Carbon\Carbon::parse($leave->date_from);
        $dateTo = \Carbon\Carbon::parse($leave->date_to);

        if ($leave->leave_type == 'Halfday' || $leave->leave_type == 'Undertime') {
            // Calculate hours and minutes difference
            $diffInMinutes = $dateFrom->diffInMinutes($dateTo);
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;

            return [
                'hours' => $hours,
                'minutes' => $minutes
            ];
        } else if ($leave->leave_type == 'Leave') {
            // For Leave type, calculate hours and minutes
            $diffInMinutes = $dateFrom->diffInMinutes($dateTo);
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;

            return [
                'hours' => $hours,
                'minutes' => $minutes
            ];
        } else {
            // For other types, calculate days
            return $dateFrom->diffInDays($dateTo);
        }
    }
}
