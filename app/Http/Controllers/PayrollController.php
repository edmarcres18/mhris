<?php

namespace App\Http\Controllers;

use App\Services\PayrollService;
use Illuminate\Http\Request;
use App\Models\Payroll;
use Carbon\Carbon;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Schema;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
        $this->middleware('auth');
        $this->middleware('permission:payroll-list|payroll-create|payroll-edit|payroll-delete', ['only' => ['index','show']]);
        $this->middleware('permission:payroll-create', ['only' => ['create','store']]);
        $this->middleware('permission:payroll-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:payroll-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a form to create payroll
     */
    public function create()
    {
        $user = auth()->user();
        if ($user->hasRole('Finance')) {
            $employees = Employee::where('employee_status', 'Active')->where('rank', 'Managerial')->get();
        }
        elseif ($user->hasRole('Super Admin')) {
            $employees = Employee::where('employee_status', 'Active')->get();
        }
        else {
            $employees = Employee::where('employee_status', 'Active')->where('rank', 'Rank File')->get();
        }
        return view('payroll.create', compact('employees'));
    }

    // Store the payroll records for all employees
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'employee_id' => 'nullable|exists:employees,id',
            'payroll_type' => 'required|in:regular,weekly'
        ]);

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $specific_employee_id = $request->input('employee_id');
        $payroll_type = $request->input('payroll_type');

        $user = auth()->user();

        // Query builder for employees
        $employeesQuery = Employee::where('employee_status', 'Active');

        if ($specific_employee_id) {
            $employeesQuery->where('id', $specific_employee_id);
        }
        else if ($user->hasRole('Finance')) {
            $employeesQuery->where('rank', 'Managerial');
        }
        elseif ($user->hasRole('Super Admin')) {
            $employeesQuery->where('employee_status', 'Active');
        }
        else {
            if (!$user->hasRole('Super Admin', 'Finance')) {
                $employeesQuery->where('rank', 'Rank File');
            }

            // Filter employees based on payroll type
            if ($payroll_type === 'weekly') {
                $employeesQuery->whereHas('department', function ($query) {
                    $query->where('name', 'BGPDI');
                });
            } else {
                $employeesQuery->whereHas('department', function ($query) {
                    $query->where('name', '!=', 'BGPDI');
                });
            }
        }

        $employees = $employeesQuery->get();

        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($employees as $employee) {
            try {
                // Validate payroll type matches employee department
                if ($specific_employee_id) {
                    $isDepartmentMatch = ($payroll_type === 'weekly' && $employee->department->name === 'BGPDI') ||
                                       ($payroll_type === 'regular' && $employee->department->name !== 'BGPDI');
                    
                    if (!$isDepartmentMatch) {
                        throw new \Exception("Invalid payroll type for employee's department");
                    }
                }

                // Check for existing payroll
                if ($this->existingPayroll($employee->id, $start_date, $end_date)) {
                    throw new \Exception("Payroll already exists for this period");
                }

                // Calculate payroll
                $payroll = $this->payrollService->calculatePayroll($employee->id, $start_date, $end_date);

                // // Send email notification if successful
                // if ($payroll && $employee->email_address) {
                //     \Mail::to($employee->email_address)->send(new \App\Mail\PayrollAvailable([
                //         'employee_name' => $employee->full_name,
                //         'start_date' => Carbon::parse($start_date)->format('F d, Y'),
                //         'end_date' => Carbon::parse($end_date)->format('F d, Y'),
                //         'payroll_type' => ucfirst($payroll_type)
                //     ]));
                // }

                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
                $errors[] = "Failed to process payroll for {$employee->full_name}: {$e->getMessage()}";
                \Log::error("Payroll generation failed for employee ID {$employee->id}: " . $e->getMessage());
            }
        }

        // Prepare response message
        $message = $this->prepareResponseMessage($successCount, $failCount, $specific_employee_id);
        
        return redirect()->route('payroll.index')
            ->with('success', $message)
            ->with('errors', $errors);
    }

    private function prepareResponseMessage($successCount, $failCount, $isSpecific)
    {
        $message = "Payroll calculated and stored successfully for {$successCount} " .
                   ($isSpecific ? "employee" : "active employees") . ".";
        
        if ($failCount > 0) {
            $message .= " Failed for {$failCount} " . 
                       ($isSpecific ? "employee" : "employees") . 
                       ". Check logs for details.";
        }
        
        return $message;
    }

    // Display payroll details
    public function show($id)
    {
        $payroll = Payroll::findOrFail($id);
        return view('payroll.show', compact('payroll'));
    }

    /**
     * List all payroll records
     */
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Check if download is requested
        if ($request->has('download')) {
            return $this->downloadPayrolls($request);
        }

        // Existing index logic
        if ($user->hasRole('Super Admin')) {
            $payrolls = Payroll::with('employee')->get();
        } 
        elseif ($user->hasRole('Finance')) {
            $payrolls = Payroll::with('employee')->get();
        }
        else {
            $payrolls = Payroll::whereHas('employee', function ($query) {
                $query->where('rank', 'Rank File');
            })->with('employee')->get();
        }

        return view('payroll.index', compact('payrolls'));
    }

    protected function downloadPayrolls(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $start_date = Carbon::parse($request->input('start_date'));
        $end_date = Carbon::parse($request->input('end_date'));

        // Query payrolls within the date range
        $payrolls = Payroll::where(function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date])
                  ->orWhereBetween('end_date', [$start_date, $end_date])
                  ->orWhere(function ($q) use ($start_date, $end_date) {
                      $q->where('start_date', '<=', $start_date)
                        ->where('end_date', '>=', $end_date);
                  });
        })->with('employee')->get();

        if ($payrolls->isEmpty()) {
            return redirect()->back()->with('error', 'No payrolls found for the specified date range.');
        }

        // Create a folder name combining start_date and end_date
        $folderName = $start_date->format('MdY') . '-' . $end_date->format('MdY') . '-Payrolls';

        // Create a temporary directory to store PDFs
        $tempDir = storage_path('app/temp_' . $folderName);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Generate PDFs for each payroll
        foreach ($payrolls as $payroll) {
            $pdf = $this->generatePayslipPDF($payroll);
            $filename = $this->getPayslipFilename($payroll);
            $pdf->save($tempDir . '/' . $filename);
        }

        // Create a zip file
        $zipFileName = $folderName . '.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = $folderName . '/' . substr($filePath, strlen($tempDir) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }

        // Clean up the temporary directory
        $this->cleanupTempDirectory($tempDir);

        // Download the zip file
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    protected function generatePayslipPDF($payroll)
    {
        // Ensure dates are Carbon instances
        $payroll->start_date = Carbon::parse($payroll->start_date);
        $payroll->end_date = Carbon::parse($payroll->end_date);

        // Get the logo path and convert to base64
        $logoPath = public_path('vendor/adminlte/dist/img/LOGO4.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $pdf = PDF::loadView('payroll.payslip_pdf', compact('payroll', 'logoBase64'));
        $pdf->setPaper('letter');
        $pdf->setOptions([
            'margin-top'    => 0,
            'margin-right'  => 0,
            'margin-bottom' => 0,
            'margin-left'   => 0,
        ]);

        return $pdf;
    }

    protected function getPayslipFilename($payroll)
    {
        return $payroll->employee->company_id . '_' .
               $payroll->employee->last_name . '_' .
               $payroll->employee->first_name . '_' .
               $payroll->start_date->format('F d, Y') . '_' .
               $payroll->end_date->format('F d, Y') . '-Payroll.pdf';
    }

    protected function cleanupTempDirectory($dir)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        rmdir($dir);
    }

    public function destroy($id)
    {
        // Find the payroll record by ID
        $payroll = Payroll::findOrFail($id);

        // Delete the payroll record
        $payroll->delete();

        // Redirect to the payroll index with a success message
        return redirect()->route('payroll.index')->with('success', 'Payroll record deleted successfully.');
    }

    public function employeesWithPayroll()
    {
        $employees = Employee::whereHas('payrolls')->get();
        return view('payroll.employees_with_payroll', compact('employees'));
    }

    public function payslips($employee_id)
    {
        $employee = Employee::with('payrolls')->findOrFail($employee_id);

        // Ensure dates are Carbon instances
        foreach ($employee->payrolls as $payroll) {
            $payroll->start_date = Carbon::parse($payroll->start_date);
            $payroll->end_date = Carbon::parse($payroll->end_date);
        }

        return view('payroll.payslips', compact('employee'));
    }

    public function myPayrolls()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Find the employee by email address
        $employee = Employee::with('payrolls')->where('email_address', $user->email)->first();

        if ($employee) {
            // Ensure dates are Carbon instances
            foreach ($employee->payrolls as $payroll) {
                $payroll->start_date = Carbon::parse($payroll->start_date);
                $payroll->end_date = Carbon::parse($payroll->end_date);
            }

            return view('payroll.my_payrolls', compact('employee'));
        } else {
            return redirect()->route('payroll.index')->with('error', 'Employee not found.');
        }
    }

    public function generatePayslip($payroll_id)
    {
        $payroll = Payroll::with('employee')->findOrFail($payroll_id);

        // Ensure dates are Carbon instances
        $payroll->start_date = Carbon::parse($payroll->start_date);
        $payroll->end_date = Carbon::parse($payroll->end_date);

        // Get the logo path
        $logoPath = public_path('vendor/adminlte/dist/img/LOGO4.png');

        // Read image file and convert to base64
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        } else {
            \Log::error("Logo file not found at path: $logoPath");
        }

        $pdf = PDF::loadView('payroll.payslip_pdf', compact('payroll', 'logoBase64'));

        // Set paper size to letter
        $pdf->setPaper('letter');

        // Set custom margins to occupy half of the paper
        $pdf->setOptions([
            'margin-top'    => 0,
            'margin-right'  => 0,
            'margin-bottom' => 0,
            'margin-left'   => 0,
        ]);

        // Create the new filename
        $filename = $payroll->employee->company_id . '_' .
                    $payroll->employee->last_name . '_' .
                    $payroll->employee->first_name . '_' .
                    $payroll->start_date->format('F d, Y') . '_' .
                    $payroll->end_date->format('F d, Y') . '-Payroll.pdf';

        return $pdf->download($filename);
    }

    /**
     * Check if a payroll record already exists for the given employee and date range
     *
     * @param int $employee_id
     * @param string $start_date
     * @param string $end_date
     * @return bool
     */
    public function existingPayroll($employee_id, $start_date, $end_date)
    {
        return Payroll::where('employee_id', $employee_id)
            ->where('start_date', $start_date)
            ->where('end_date', $end_date)
            ->exists();
    }

    /**
     * Get payroll records for the specified date range for adjustments
     */
    public function getAdjustments(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'period_type' => 'required|in:biweekly,bimonthly',
        ]);

        $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
        $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
        $period_type = $request->input('period_type');

        // Get payrolls within the date range
        $query = Payroll::with(['employee.department', 'employee.position'])
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orWhereBetween('end_date', [$start_date, $end_date]);

        // Apply period type filtering
        if ($period_type === 'biweekly') {
            // For biweekly, typically weekly-paid employees (e.g., BGPDI department)
            $query->whereHas('employee.department', function ($q) {
                $q->where('name', 'BGPDI');
            });
        } else if ($period_type === 'bimonthly') {
            // For bimonthly, typically monthly-paid employees (non-BGPDI departments)
            $query->whereHas('employee.department', function ($q) {
                $q->where('name', '!=', 'BGPDI');
            });
        }

        // Get all departments from the Department model
        $departmentsList = \App\Models\Department::all();
        
        // Define departments for filtering and readability, using data from Department model
        $departments = [];
        foreach ($departmentsList as $dept) {
            $departments[$dept->name] = strtoupper($dept->name);
        }

        // Group payrolls by department
        $payrolls = $query->get();
        $payrollsByDepartment = $payrolls->groupBy(function ($payroll) {
            return $payroll->employee->department->name ?? 'Others';
        });

        if ($request->ajax()) {
            return view('payroll.adjustments-content', compact('payrollsByDepartment', 'departments'));
        }

        return response()->json(['error' => 'Invalid request method'], 400);
    }

    /**
     * Get payroll records for print preview
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function getPrintPreview(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'period_type' => 'required|in:biweekly,bimonthly',
        ]);

        $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
        $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
        $period_type = $request->input('period_type');

        // Get payrolls within the date range
        $query = Payroll::with(['employee.department', 'employee.position'])
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orWhereBetween('end_date', [$start_date, $end_date]);

        // Apply period type filtering
        if ($period_type === 'biweekly') {
            // For biweekly, typically weekly-paid employees (e.g., BGPDI department)
            $query->whereHas('employee.department', function ($q) {
                $q->where('name', 'BGPDI');
            });
        } else if ($period_type === 'bimonthly') {
            // For bimonthly, typically monthly-paid employees (non-BGPDI departments)
            $query->whereHas('employee.department', function ($q) {
                $q->where('name', '!=', 'BGPDI');
            });
        }

        // Get all departments from the Department model
        $departmentsList = \App\Models\Department::all();
        
        // Define departments for filtering and readability, using data from Department model
        $departments = [];
        foreach ($departmentsList as $dept) {
            $departments[$dept->name] = strtoupper($dept->name);
        }

        // Group payrolls by department
        $payrolls = $query->get();
        
        // Format dates for display
        $periodStart = $start_date->format('M j, Y');
        $periodEnd = $end_date->format('M j, Y');
        $payrollYear = $end_date->format('Y');
        
        // Group payrolls by department
        $payrollsByDepartment = $payrolls->groupBy(function ($payroll) {
            return $payroll->employee->department->name ?? 'Others';
        });

        if ($request->ajax()) {
            return view('payroll.print-preview-content', compact(
                'payrollsByDepartment', 
                'departments', 
                'periodStart', 
                'periodEnd',
                'payrollYear'
            ));
        }

        return response()->json(['error' => 'Invalid request method'], 400);
    }

    /**
     * Save payroll adjustments
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function saveAdjustments(Request $request)
    {
        $request->validate([
            'adjustments' => 'required|array',
            'adjustments.*.payroll_id' => 'required|exists:payrolls,id',
            'adjustments.*.adjustments' => 'nullable|numeric',
            'adjustments.*.allowances' => 'nullable|numeric',
            'adjustments.*.other_adjustments' => 'nullable|numeric',
            'adjustments.*.cash_bond' => 'nullable|numeric',
            'adjustments.*.other_deduction' => 'nullable|numeric',
            'adjustments.*.other_deduction_description' => 'nullable|string|max:500',
        ]);

        $adjustments = $request->input('adjustments');
        $updatedPayrolls = [];

        try {
            \DB::beginTransaction();

            foreach ($adjustments as $adjustment) {
                // Find the payroll record
                $payroll = Payroll::findOrFail($adjustment['payroll_id']);
                
                // Store original values for calculations and logging
                $originalNetSalary = $payroll->net_salary;
                $originalAdjustments = $payroll->adjustments ?? 0;
                $originalAllowances = $payroll->allowances ?? 0;
                $originalOtherAdjustments = $payroll->other_adjustments ?? 0;
                $originalCashBond = $payroll->cash_bond ?? 0;
                $originalOtherDeduction = $payroll->other_deduction ?? 0;
                $originalOtherDeductionDescription = $payroll->other_deduction_description ?? '';
                
                // Get new values with proper handling of null, empty, and zero values
                // Use is_numeric to check if the value is a number (including zero)
                // Fall back to null instead of 0 to distinguish between explicitly set zeros and missing values
                $newAdjustments = isset($adjustment['adjustments']) && is_numeric($adjustment['adjustments']) 
                    ? (float) $adjustment['adjustments'] 
                    : null;
                
                $newAllowances = isset($adjustment['allowances']) && is_numeric($adjustment['allowances']) 
                    ? (float) $adjustment['allowances'] 
                    : null;
                
                $newOtherAdjustments = isset($adjustment['other_adjustments']) && is_numeric($adjustment['other_adjustments']) 
                    ? (float) $adjustment['other_adjustments'] 
                    : null;
                
                $newCashBond = isset($adjustment['cash_bond']) && is_numeric($adjustment['cash_bond']) 
                    ? (float) $adjustment['cash_bond'] 
                    : null;
                
                $newOtherDeduction = isset($adjustment['other_deduction']) && is_numeric($adjustment['other_deduction']) 
                    ? (float) $adjustment['other_deduction'] 
                    : null;
                
                // Get the other deduction description (a string field, so no need for numeric checking)
                $newOtherDeductionDescription = isset($adjustment['other_deduction_description']) 
                    ? $adjustment['other_deduction_description'] 
                    : null;
                
                // Calculate the differences (new - original), handling null values
                // Only calculate difference if a new value was explicitly provided (even if zero)
                $adjustmentsDiff = $newAdjustments !== null ? $newAdjustments - $originalAdjustments : 0;
                $allowancesDiff = $newAllowances !== null ? $newAllowances - $originalAllowances : 0;
                $otherAdjustmentsDiff = $newOtherAdjustments !== null ? $newOtherAdjustments - $originalOtherAdjustments : 0;
                $cashBondDiff = $newCashBond !== null ? $newCashBond - $originalCashBond : 0;
                $otherDeductionDiff = $newOtherDeduction !== null ? $newOtherDeduction - $originalOtherDeduction : 0;
                
                // Calculate net impact on salary
                $positiveAdjustments = $adjustmentsDiff + $allowancesDiff + $otherAdjustmentsDiff;
                $negativeAdjustments = $cashBondDiff + $otherDeductionDiff;
                
                // Update the net salary by applying only the differences
                $newNetSalary = $originalNetSalary + $positiveAdjustments - $negativeAdjustments;
                $payroll->net_salary = $newNetSalary;
                
                // Update the adjustment fields with new values, preserving original values when no new value was provided
                if ($newAdjustments !== null) $payroll->adjustments = $newAdjustments;
                if ($newAllowances !== null) $payroll->allowances = $newAllowances;
                if ($newOtherAdjustments !== null) $payroll->other_adjustments = $newOtherAdjustments;
                if ($newCashBond !== null) $payroll->cash_bond = $newCashBond;
                if ($newOtherDeduction !== null) $payroll->other_deduction = $newOtherDeduction;
                if ($newOtherDeductionDescription !== null) $payroll->other_deduction_description = $newOtherDeductionDescription;
                
                $payroll->save();
                
                // Store the updated net salary for the response
                $updatedPayrolls[$payroll->id] = number_format($payroll->net_salary, 2);
                
                // Log the adjustments made
                \Log::info("Payroll adjustment applied to payroll ID {$payroll->id} for {$payroll->employee->first_name} {$payroll->employee->last_name}. " .
                    "Net salary changed from {$originalNetSalary} to {$newNetSalary}. " .
                    "Adjustments: {$originalAdjustments} → " . ($newAdjustments !== null ? $newAdjustments : 'unchanged') . ", " .
                    "Allowances: {$originalAllowances} → " . ($newAllowances !== null ? $newAllowances : 'unchanged') . ", " . 
                    "Other Adj: {$originalOtherAdjustments} → " . ($newOtherAdjustments !== null ? $newOtherAdjustments : 'unchanged') . ", " .
                    "Cash Bond: {$originalCashBond} → " . ($newCashBond !== null ? $newCashBond : 'unchanged') . ", " .
                    "Other Deduct: {$originalOtherDeduction} → " . ($newOtherDeduction !== null ? $newOtherDeduction : 'unchanged') . ", " .
                    "Other Deduct Desc: '" . ($originalOtherDeductionDescription ?: 'none') . "' → '" . ($newOtherDeductionDescription !== null ? $newOtherDeductionDescription : 'unchanged') . "'");
            }

            \DB::commit();

            // For AJAX requests, return JSON response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payroll adjustments saved successfully',
                    'updated_payrolls' => $updatedPayrolls,
                    'redirect' => route('payroll.index')
                ]);
            }

            // For non-AJAX requests, redirect with success message
            return redirect()->route('payroll.index')
                ->with('success', 'Payroll adjustments saved successfully');
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error("Error saving payroll adjustments: " . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save adjustments: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to save adjustments: ' . $e->getMessage());
        }
    }

    /**
     * Send payroll notifications to employees
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'period_type' => 'required|in:biweekly,bimonthly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
        $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
        $period_type = $request->input('period_type');

        // Get payrolls within the date range
        $query = Payroll::with(['employee'])
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orWhereBetween('end_date', [$start_date, $end_date]);

        // Apply period type filtering
        if ($period_type === 'biweekly') {
            // For biweekly, typically weekly-paid employees (e.g., BGPDI department)
            $query->whereHas('employee.department', function ($q) {
                $q->where('name', 'BGPDI');
            });
        } else if ($period_type === 'bimonthly') {
            // For bimonthly, typically monthly-paid employees (non-BGPDI departments)
            $query->whereHas('employee.department', function ($q) {
                $q->where('name', '!=', 'BGPDI');
            });
        }

        $payrolls = $query->get();
        $successCount = 0;
        $errorCount = 0;

        foreach ($payrolls as $payroll) {
            if ($payroll->employee && $payroll->employee->email_address) {
                try {
                    \Mail::to($payroll->employee->email_address)->send(new \App\Mail\PayrollAvailable([
                        'employee_name' => $payroll->employee->full_name,
                        'start_date' => Carbon::parse($payroll->start_date)->format('F d, Y'),
                        'end_date' => Carbon::parse($payroll->end_date)->format('F d, Y'),
                        'payroll_type' => ucfirst($period_type)
                    ]));
                    $successCount++;
                } catch (\Exception $e) {
                    \Log::error("Failed to send payroll notification to {$payroll->employee->email_address}: " . $e->getMessage());
                    $errorCount++;
                }
            } else {
                // Skip employees without email
                $errorCount++;
                if ($payroll->employee) {
                    \Log::warning("Employee {$payroll->employee->id} has no email address for payroll notification.");
                } else {
                    \Log::warning("Payroll {$payroll->id} has no associated employee.");
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Notifications sent to {$successCount} employees. Failed: {$errorCount}.",
            'data' => [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'total_payrolls' => $payrolls->count()
            ]
        ]);
    }
}