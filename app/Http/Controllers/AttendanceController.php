<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use App\Events\AttendanceStored;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use App\Imports\AttendanceImport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use GuzzleHttp\Client;

class AttendanceController extends Controller
{
    use HasRoles;

    // Middleware for permissions
    function __construct()
    {
        $this->middleware(['permission:attendance-list|attendance-create|attendance-edit|attendance-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:attendance-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:attendance-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:attendance-delete'], ['only' => ['destroy']]);
    }

    // Display all attendance records
    public function index()
    {
        if (auth()->user()->hasRole('Supervisor')) {
            $attendances = Attendance::whereHas('employee', function($query) {
                $query->where('department_id', auth()->user()->department_id);
            })->get();
        } else {
            $attendances = Attendance::all();
        }
        
        return view('attendances.index', compact('attendances'));
    }

    // Show form for creating a new attendance record
    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        return view('attendances.create', compact('employees'));
    }

    // Store a new attendance record
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_attended' => 'required|date',
            'time_stamp1' => 'nullable',
            'time_stamp2' => 'nullable',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'remarks' => 'nullable',
            'hours_worked' => 'nullable',
        ]);

        // Check if attendance already exists for the given date_attended and employee_id
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
                                        ->where('date_attended', $request->date_attended)
                                        ->first();

        // Get the authenticated user
        $user = Auth::user();

        $successMessage = '';

        if ($existingAttendance) {
            // Update existing attendance with any provided fields
            if ($request->time_in) {
                $existingAttendance->time_in = $request->time_in;
            }
            
            if ($request->time_out) {
                $existingAttendance->time_out = $request->time_out;
            }
            
            if ($request->hasFile('time_stamp1')) {
                $existingAttendance->time_stamp1 = $request->file('time_stamp1')->store('time_stamps', 'public');
            }
            
            if ($request->hasFile('time_stamp2')) {
                $existingAttendance->time_stamp2 = $request->file('time_stamp2')->store('time_stamps', 'public');
            }
            
            $existingAttendance->remarks = $request->remarks;
            $existingAttendance->hours_worked = $request->hours_worked;
            $existingAttendance->save();

            $successMessage = 'Attendance record updated successfully.';
        } else {
            // Create new attendance record with any provided fields
            $newAttendance = new Attendance;
            $newAttendance->employee_id = $request->employee_id;
            $newAttendance->date_attended = $request->date_attended;
            $newAttendance->time_in = $request->time_in;
            $newAttendance->time_out = $request->time_out;
            
            if ($request->hasFile('time_stamp1')) {
                $newAttendance->time_stamp1 = $request->file('time_stamp1')->store('time_stamps', 'public');
            }
            
            if ($request->hasFile('time_stamp2')) {
                $newAttendance->time_stamp2 = $request->file('time_stamp2')->store('time_stamps', 'public');
            }
            
            $newAttendance->remarks = $request->remarks;
            $newAttendance->hours_worked = $request->hours_worked;
            $newAttendance->save();

            $successMessage = 'Attendance record created successfully.';
        }

        // Check user role and return appropriate view
        if ($user->hasRole('Employee')) {
            $employees = Employee::all(); // Fetch employees to pass to the view
            return view('attendances.create', compact('employees'))->with('successMessage', $successMessage);
        } else {
            return redirect()->route('attendances.index')->with('success', $successMessage);
        }
    }

    // Method to check if attendance exists (for AJAX call)
    public function checkAttendance(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $dateAttended = $request->query('date_attended');

        // Replace this with your logic to check attendance in the database
        $attendance = Attendance::where('employee_id', $employeeId)
                                ->where('date_attended', $dateAttended)
                                ->first();

        return response()->json([
            'hasTimeIn' => $attendance && $attendance->time_in != null,
            'hasTimeOut' => $attendance && $attendance->time_out != null,
        ]);
    }

    // Display a specific attendance record
    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }

    // Show form for editing an attendance record
    public function edit(Attendance $attendance)
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        $remarks = Attendance::getRemarks();
        return view('attendances.edit', compact('attendance', 'employees', 'remarks'));
    }

    // Update a specific attendance record
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_attended' => 'required|date',
            'time_stamp1' => 'nullable|file',
            'time_stamp2' => 'nullable|file',
            'time_in' => 'nullable',
            'time_out' => 'nullable',
            'remarks' => 'nullable',
            'hours_worked' => 'nullable|numeric',
        ]);

        // Update attendance with provided data
        $attendance->employee_id = $request->employee_id;
        $attendance->date_attended = $request->date_attended;
        $attendance->time_in = $request->time_in;
        $attendance->time_out = $request->time_out;
        $attendance->remarks = $request->remarks;
        $attendance->hours_worked = $request->hours_worked;

        if ($request->hasFile('time_stamp1')) {
            $attendance->time_stamp1 = $request->file('time_stamp1')->store('time_stamps', 'public');
        }

        if ($request->hasFile('time_stamp2')) {
            $attendance->time_stamp2 = $request->file('time_stamp2')->store('time_stamps', 'public');
        }

        $attendance->save();

        return redirect()->route('attendances.index')
                         ->with('success', 'Attendance updated successfully.');
    }

    // Delete a specific attendance record
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')
                         ->with('success', 'Attendance deleted successfully.');
    }

    // Generate timesheets or attendance records for all employees
    public function generateTimesheets()
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Get employees based on user role
        $employees = $user->hasRole('Super Admin') 
            ? Employee::all()
            : Employee::where('employee_status', 'Active')->get();
            
        $departments = Department::all();
        $timesheets = [];

        foreach ($employees as $employee) {
            $attendances = Attendance::where('employee_id', $employee->id)->get();
            $timesheets[$employee->id] = $attendances;
        }

        return view('attendances.timesheets', compact('employees', 'timesheets', 'departments'));
    }

    // Show attendance for a specific employee
    public function showEmployeeAttendance($employee_id)
    {
        $employee = Employee::find($employee_id);
        $attendances = Attendance::where('employee_id', $employee_id)->get();

        return view('attendances.employee_attendance', compact('employee', 'attendances'));
    }

    // Show the logged-in employee's timesheet
    public function myTimesheet()
    {
        $user = Auth::user();

        // Find the employee record corresponding to the logged-in user
        $employee = Employee::where('first_name', $user->first_name)->first();

        if (!$employee) {
            return redirect()->route('attendances.index')
                             ->withErrors(['error' => 'No corresponding employee record found for the user.']);
        }

        // Fetch the attendance records for the logged-in employee
        $attendances = Attendance::where('employee_id', $employee->id)->get();

        return view('attendances.employee_attendance', compact('employee', 'attendances'));
    }

    // Check user role and execute appropriate function
    public function checkUserAndShowTimesheet()
    {
        $user = Auth::user();

        // Assuming roles are defined and you have a method to check user roles
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return redirect()->route('attendances.index');
        } else {
            return $this->myTimesheet();
        }
    }

    public function checkAttendanceStatus(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_attended' => 'required|date',
        ]);

        $employeeId = $request->input('employee_id');
        $dateAttended = $request->input('date_attended');

        // Retrieve the attendance record for the employee and date
        $attendance = Attendance::where('employee_id', $employeeId)
                                ->where('date_attended', $dateAttended)
                                ->first();

        // Check if attendance exists and has a time_in value
        if ($attendance) {
            return response()->json([
                'status' => 'exists',
                'time_in' => $attendance->time_in,
                'time_out' => $attendance->time_out, // You may also include time_out if needed
            ], 200);
        }

        return response()->json(['status' => 'not_found'], 200);
    }

    public function getEmployeeInfo(Request $request)
    {
        // Get authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Find employee by email
        $employee = Employee::where('email_address', $user->email)->first();
        
        if (!$employee) {
            return response()->json([
                'name' => $user->first_name . ' ' . $user->last_name,
                'id' => $user->id,
                'position' => null,
                'department' => null
            ]);
        }
        
        // Get position and department information
        $position = $employee->position ? $employee->position->name : null;
        $department = $employee->department ? $employee->department->name : null;

        return response()->json([
            'name' => $employee->first_name . ' ' . $employee->last_name,
            'id' => $employee->id,
            'position' => $position,
            'department' => $department
        ]);
    }

    public function printAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_attended' => 'required|date',
        ]);

        $employeeId = $request->input('employee_id');
        $dateAttended = $request->input('date_attended');

        $attendance = Attendance::where('employee_id', $employeeId)
                                ->where('date_attended', $dateAttended)
                                ->first();

        if (!$attendance) {
            return redirect()->back()->withErrors(['error' => 'Attendance record not found.']);
        }

        // Pass the attendance data to the print view
        return view('attendances.print', [
            'attendance' => $attendance
        ]);
    }

    /**
     * Show the form for importing attendance records.
     *
     * @return \Illuminate\View\View
     */
    public function showImportForm()
    {
        return view('attendances.import');
    }

    /**
     * Handle the import of attendance records.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new AttendanceImport, $request->file('file'));

            return redirect()->route('attendances.index')->with('success', 'Attendance records imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to import records: ' . $e->getMessage()]);
        }
    }

    /**
     * Export attendance records to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new AttendanceExport, 'attendances.xlsx');
    }

    public function attendance()
    {
        $user = Auth::user();
        
        if (!$user->hasRole(['Employee', 'Supervisor'])) {
            abort(403, 'Unauthorized access.');
        }

        $employee = Employee::where('email_address', $user->email)->first();

        return view('attendances.attendance', compact('employee'));
    }

    /**
     * Execute the attendance:store artisan command
     */
    public function executeStoreCommand()
    {
        try {
            \Artisan::call('attendance:store');
            return response()->json(['message' => 'Attendance records stored successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a new attendance record and redirect to create another
     */
    public function storeAndCreateAnother(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date_attended' => 'required|date',
            'time_stamp1' => 'nullable',
            'time_stamp2' => 'nullable',
            'time_in' => 'nullable|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i',
            'remarks' => 'nullable',
            'hours_worked' => 'nullable',
        ]);

        // Check if attendance already exists for the given date_attended and employee_id
        $existingAttendance = Attendance::where('employee_id', $request->employee_id)
                                        ->where('date_attended', $request->date_attended)
                                        ->first();

        // Get the authenticated user
        $user = Auth::user();

        $successMessage = '';

        if ($existingAttendance) {
            // Update existing attendance with any provided fields
            if ($request->time_in) {
                $existingAttendance->time_in = $request->time_in;
            }
            
            if ($request->time_out) {
                $existingAttendance->time_out = $request->time_out;
            }
            
            if ($request->hasFile('time_stamp1')) {
                $existingAttendance->time_stamp1 = $request->file('time_stamp1')->store('time_stamps', 'public');
            }
            
            if ($request->hasFile('time_stamp2')) {
                $existingAttendance->time_stamp2 = $request->file('time_stamp2')->store('time_stamps', 'public');
            }
            
            $existingAttendance->remarks = $request->remarks;
            $existingAttendance->hours_worked = $request->hours_worked;
            $existingAttendance->save();

            $successMessage = 'Attendance record updated successfully.';
        } else {
            // Create new attendance record with any provided fields
            $newAttendance = new Attendance;
            $newAttendance->employee_id = $request->employee_id;
            $newAttendance->date_attended = $request->date_attended;
            $newAttendance->time_in = $request->time_in;
            $newAttendance->time_out = $request->time_out;
            
            if ($request->hasFile('time_stamp1')) {
                $newAttendance->time_stamp1 = $request->file('time_stamp1')->store('time_stamps', 'public');
            }
            
            if ($request->hasFile('time_stamp2')) {
                $newAttendance->time_stamp2 = $request->file('time_stamp2')->store('time_stamps', 'public');
            }
            
            $newAttendance->remarks = $request->remarks;
            $newAttendance->hours_worked = $request->hours_worked;
            $newAttendance->save();

            $successMessage = 'Attendance record created successfully.';
        }

        // Check if the request is AJAX (from mobile app)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ]);
        }

        // For regular form submissions, redirect to the create page
        $employees = Employee::where('employee_status', 'Active')->get();
        return redirect()->route('attendances.create')
                         ->with('success', $successMessage);
    }
}