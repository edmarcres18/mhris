<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;
use App\Models\Gender;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Notifications\EmployeeAccountActivated;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;
use App\Exports\EmployeesExport;
use Illuminate\Support\Facades\Mail;
use App\Mail\BirthdayGreeting;
use App\Mail\EmployeeResignationNotification;
use App\Mail\UserAccountDisabledNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:employee-list|employee-create|employee-edit|employee-delete'], ['only' => ['index', 'show', 'resigned', 'terminated']]);
        $this->middleware(['permission:employee-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:employee-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:employee-delete'], ['only' => ['destroy']]);
    }
/**
 * Display a listing of the resource.
 */
public function index()
{
    // Get the authenticated user
    $user = auth()->user();
    $departments = Department::all();
    
    // Get only active employees
    $employees = Employee::where('employee_status', 'Active')->get();

    // Determine employment status for each employee
    foreach ($employees as $employee) {
        $employee->employment_status = $employee->employmentStatus();
    }

    return view('employees.index', compact('employees', 'departments'));
}

/**
 * Display a listing of resigned employees.
 */
public function resigned()
{
    // Get the authenticated user
    $user = auth()->user();
    $departments = Department::all();
    
    // Get only resigned employees
    $employees = Employee::where('employee_status', 'Resigned')->get();

    // Determine employment status for each employee
    foreach ($employees as $employee) {
        $employee->employment_status = $employee->employmentStatus();
    }

    return view('employees.resigned', compact('employees', 'departments'));
}

/**
 * Display a listing of terminated employees.
 */
public function terminated()
{
    // Get the authenticated user
    $user = auth()->user();
    $departments = Department::all();
    
    // Get only terminated employees
    $employees = Employee::where('employee_status', 'Terminated')->get();

    // Determine employment status for each employee
    foreach ($employees as $employee) {
        $employee->employment_status = $employee->employmentStatus();
    }

    return view('employees.terminated', compact('employees', 'departments'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genders = Gender::all();
        $provinces = Province::all();
        $city = City::all();
        $barangay = Barangay::all();
        $positions = Position::all();
        $departments = Department::all();
        return view('employees.create',compact('genders', 'provinces', 'city', 'barangay', 'positions', 'departments'));
    }

    /**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function store(Request $request): RedirectResponse
{
    try {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|unique:employees,company_id',
            'profile' => 'nullable|image', // limit file size to 2MB
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'email_address' => 'required|email|unique:employees,email_address',
            'contact_no' => 'required|string|max:20',
            'birth_date' => 'required|date_format:Y-m-d',
            'birth_place_province' => 'nullable|string|max:255',
            'birth_place_city'=> 'nullable|string|max:255',
            'birth_place_barangay' => 'nullable|string|max:255',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'barangay_id' => 'required|exists:barangays,id',
            'gender_id' => 'required|exists:genders,id',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'salary' => 'required|numeric|min:0',
            'zip_code' => 'required|string|max:10',
            'date_hired' => 'required|date_format:Y-m-d',
            'sss_no' => 'nullable|string|max:20',
            'pagibig_no' => 'nullable|string|max:20',
            'tin_no' => 'nullable|string|max:20',
            'philhealth_no' => 'nullable|string|max:20',
            'elementary' => 'nullable|string|max:255',
            'secondary' => 'nullable|string|max:255',
            'tertiary' => 'nullable|string|max:255',
            'emergency_name' => 'required|string|max:255',
            'emergency_no' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->route('employees.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Create the employee
            $employee = Employee::create($request->except('profile'));

            // Handle profile image
            if ($request->hasFile('profile')) {
                $image = $request->file('profile');
                
                // Validate image
                if ($image->isValid()) {
                    $filename = 'profile_' . time() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('profiles', $filename, 'public');
                    $employee->profile = $path;
                    $employee->save();
                }
            }

            // Save employment status
            $employee->saveEmploymentStatus();

            // Commit transaction
            DB::commit();

            // Check which button was clicked
            if ($request->input('action') === 'save_and_create') {
                return redirect()->route('employees.create')
                    ->with('success', 'Employee created successfully. You can now create another.');
            }
            
            // Default action (just save)
            return redirect()->route('employees.show', $employee->slug)
                ->with('success', 'Employee created successfully.');

        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            // Log the error
            Log::error('Employee creation failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('employees.create')
                ->withInput()
                ->with('error', 'An error occurred while creating the employee. Please try again.');
        }

    } catch (\Exception $e) {
        Log::error('Employee validation/creation failed: ' . $e->getMessage());
        Log::error($e->getTraceAsString());

        return redirect()->route('employees.create')
            ->withInput()
            ->with('error', 'An error occurred while processing your request. Please try again.');
    }
}


    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        // Find the employee by slug
        $employee = Employee::where('slug', $slug)->firstOrFail();

        // Return the employee view with the data
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        // Find the employee by slug
        $employee = Employee::where('slug', $slug)->firstOrFail();

        // Fetch related data
        $genders = Gender::all();
        $provinces = Province::all();
        $city = City::all();
        $barangay = Barangay::all();
        $positions = Position::all();
        $departments = Department::all();

        // Return the edit view with the data
        return view('employees.edit', compact(
            'employee',
            'genders',
            'provinces',
            'city',
            'barangay',
            'positions',
            'departments'
        ));
    }


/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\Models\Employee  $employee
 * @return \Illuminate\Http\RedirectResponse
 */
public function update(Request $request, $slug): RedirectResponse
{
    // Find the employee by slug
    $employee = Employee::where('slug', $slug)->firstOrFail();

    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'company_id' => 'required',
        'profile' => 'nullable|image',
        'first_name' => 'required',
        'middle_name' => 'nullable',
        'last_name' => 'required',
        'suffix' => 'nullable',
        'email_address' => 'required|email',
        'contact_no' => 'required',
        'birth_date' => 'required|date_format:Y-m-d',
        'birth_place_province' => 'nullable',
        'birth_place_city' => 'nullable',
        'birth_place_barangay' => 'nullable',
        'province_id' => 'required',
        'city_id' => 'required',
        'barangay_id' => 'required',
        'gender_id' => 'required',
        'position_id' => 'required',
        'department_id' => 'required',
        'salary' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        'zip_code' => 'required|numeric',
        'date_hired' => 'required|date_format:Y-m-d',
        'sss_no' => 'nullable|numeric',
        'pagibig_no' => 'nullable|numeric',
        'tin_no' => 'nullable|numeric',
        'philhealth_no' => 'nullable|numeric',
        'elementary' => 'nullable',
        'secondary' => 'nullable',
        'tertiary' => 'nullable',
        'emergency_name' => 'required',
        'emergency_no' => 'required|numeric',
        'employee_status' => 'required|in:active,resigned',
        'rank' => 'required|in:Rank File,Managerial',
    ]);

    // Update the employee with the request data
    $employee->update($request->except(['profile']));

    // Handle the profile image upload
    if ($request->hasFile('profile')) {
        $image = $request->file('profile');
        $filename = $image->store('profiles', 'public');
        $employee->profile = $filename;
    }
    $employee->employment_status = $request->input('employee_status');
    $employee->rank = $request->input('rank');
    $employee->save();

    // Save employment status
    $employee->saveEmploymentStatus();

    return redirect()->route('employees.show', $employee->slug)
                     ->with('success', 'Employee updated successfully');
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')
                         ->with('success', 'Employee deleted successfully');
    }



    public function createUser(Request $request, Employee $employee): RedirectResponse
    {
        // Check if the user already exists
        $existingUser = User::where('email', $employee->email_address)->first();

        if ($existingUser) {
            return redirect()->route('employees.index')
                             ->with('error', 'User already exists for this employee.');
        }

        // Get the appropriate role based on employee rank
        $roleName = $employee->rank === 'Rank File' ? 'Employee' : 'Supervisor';
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return redirect()->route('employees.index')
                             ->with('error', "Role '{$roleName}' not found.");
        }

        // Create a user for the employee
        $userData = [
            'company_id' => $employee->company_id,
            'first_name' => $employee->first_name,
            'middle_name' => $employee->middle_name,
            'last_name' => $employee->last_name,
            'suffix' => $employee->suffix,
            'email' => $employee->email_address,
            'password' => Hash::make($employee->company_id), // Set the default password as the company_id
            'bio' => $employee->position->name,
            'profile_image' => $employee->profile,
            'contact_no' => $employee->contact_no,
            'date_hired' => $employee->date_hired,
            'department_id' => $employee->department_id,
        ];

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = $image->store('profile_images', 'public');
            $userData['profile_image'] = $filename;
        }

        $user = User::create($userData);

        // Assign the appropriate role to the user
        $user->assignRole($role);

        // Send notification email to the employee
        $user->notify(new EmployeeAccountActivated($employee));

        return redirect()->route('employees.index')
                         ->with('success', "User created successfully with {$roleName} role.");
    }

    /**
     * Import employees from an Excel file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new EmployeesImport, $request->file('file'));

            return redirect()->route('employees.index')->with('success', 'Employees imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->with('error', 'Error importing employees: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new EmployeesExport, 'employees.xlsx');
    }

    public function getStatus($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json([
            'employment_status' => $employee->employment_status,
        ]);
    }
    public function viewOwnProfile(Request $request)
    {
        $user = $request->user();
        $employee = Employee::where('first_name', $user->first_name)->first();

        if ($employee) {
            // Load related data
            $employee->load('position', 'department');

            // Get employment status
            $employee->employment_status = $employee->employmentStatus();

            // Prepare the response data
            $responseData = [
                'id' => $employee->id,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'email_address' => $employee->email_address,
                'position' => [
                    'name' => $employee->position->name,
                ],
                'department' => [
                    'name' => $employee->department->name,
                ],
                'employment_status' => $employee->employment_status,
                // Add any other fields you want to include
            ];

            return response()->json($responseData);
        } else {
            return response()->json(['message' => 'Employee profile not found'], 404);
        }
    }
    public function disable(Request $request, Employee $employee)
    {
        // Validate the status and date
        $request->validate([
            'status' => 'required|in:Resigned,Terminated',
            'action_date' => 'required|date'
        ]);

        try {
            DB::beginTransaction();

            // Update the employee's status and appropriate date field
            $updateData = [
                'employee_status' => $request->status
            ];

            if ($request->status === 'Resigned') {
                $updateData['resigned_date'] = $request->action_date;
            } else {
                $updateData['terminated_date'] = $request->action_date;
            }

            $employee->update($updateData);

            // Find the user associated with the employee
            $user = User::where('email', $employee->email_address)->first();

            if ($user) {
                // Disable the user account
                $user->update(['status' => 'disabled']);

                // Send appropriate notification
                if ($request->status === 'Resigned') {
                    Mail::to($employee->email_address)->send(new EmployeeResignationNotification($employee));
                } else {
                    // You might want to create a new EmployeeTerminationNotification class
                    Mail::to($employee->email_address)->send(new UserAccountDisabledNotification($user));
                }
            }

            DB::commit();

            return redirect()->route('employees.index')
                ->with('success', "Employee marked as {$request->status} successfully with " . ($request->status === 'Resigned' ? 'resignation' : 'termination') . " date " . $request->action_date);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Employee status update failed: ' . $e->getMessage());
            
            return redirect()->route('employees.index')
                ->with('error', 'Failed to update employee status. Please try again.');
        }
    }

    /**
     * Display the authenticated employee's own profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function viewOwnEmployeeProfile(Request $request)
    {
        $user = $request->user();

        // Check if the user has the 'Employee' role
        if (!$user->hasRole('Employee') && !$user->hasRole('Supervisor')) {
            return redirect()->route('home')->with('error', 'You do not have permission to view this page.');
        }

        // Find the employee profile associated with the user
        $employee = Employee::where('email_address', $user->email)->first();

        if (!$employee) {
            return redirect()->route('home')->with('error', 'Employee profile not found.');
        }

        // Load related data
        $employee->load(['position', 'department', 'gender', 'province', 'city', 'barangay']);

        // Get employment status
        $employee->employment_status = $employee->employmentStatus();

        return view('employees.own-profile', compact('employee'));
    }

    public function updateSignature(Request $request)
    {
        try {
            // Get authenticated user
            $user = auth()->user();

            // Find employee with matching email
            $employee = Employee::where('email_address', $user->email)->first();

            // Check if employee exists and matches the authenticated user
            if (!$employee) {
                Log::warning('Unauthorized signature update attempt by user: ' . $user->id);
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this signature.'
                ], 403);
            }

            // Validate the request
            $request->validate([
                'signature' => 'required|string'
            ]);

            // Decode base64 image
            $image = $request->input('signature');

            // Remove data:image/png;base64, from the beginning
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);

            // Generate unique filename
            $imageName = 'signature_' . $employee->id . '_' . time() . '.png';
            $path = 'signatures/' . $imageName;

            // Ensure the signatures directory exists
            if (!Storage::disk('public')->exists('signatures')) {
                Storage::disk('public')->makeDirectory('signatures');
            }

            // Delete old signature if exists
            if ($employee->signature && Storage::disk('public')->exists($employee->signature)) {
                Storage::disk('public')->delete($employee->signature);
            }

            // Store new signature
            $stored = Storage::disk('public')->put($path, base64_decode($image));

            if (!$stored) {
                throw new \Exception('Failed to store signature file');
            }

            // Update employee record
            $updated = $employee->update([
                'signature' => $path
            ]);

            if (!$updated) {
                throw new \Exception('Failed to update employee record');
            }

            Log::info('Signature saved successfully for employee ID: ' . $employee->id);

            return response()->json([
                'success' => true,
                'message' => 'Signature saved successfully',
                'path' => Storage::url($path)
            ]);

        } catch (\Exception $e) {
            Log::error('Signature save failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save signature: ' . $e->getMessage()
            ], 500);
        }
    }

    public function publicProfile($slug)
    {
        $employee = Employee::where('slug', $slug)
            ->where('employee_status', 'Active')
            ->firstOrFail();

        return view('employees.public-profile', compact('employee'));
    }

    // ... existing code ...

/**
 * Create user accounts for all active employees who don't have accounts yet.
 *
 * @return \Illuminate\Http\RedirectResponse
 */
public function createBulkUsers(): RedirectResponse
{
    try {
        // Begin transaction
        DB::beginTransaction();

        // Get all active employees without user accounts
        $activeEmployees = Employee::where('employee_status', 'Active')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereRaw('users.email = employees.email_address');
            })
            ->get();

        if ($activeEmployees->isEmpty()) {
            return redirect()->route('employees.index')
                ->with('info', 'No eligible employees found for user account creation.');
        }

        // Get the Employee role
        $employeeRole = Role::where('name', 'Employee')->firstOrFail();

        $createdCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($activeEmployees as $employee) {
            try {
                // Create user account
                $user = User::create([
                    'company_id' => $employee->company_id,
                    'first_name' => $employee->first_name,
                    'middle_name' => $employee->middle_name,
                    'last_name' => $employee->last_name,
                    'suffix' => $employee->suffix,
                    'email' => $employee->email_address,
                    'password' => Hash::make($employee->company_id),
                    'bio' => $employee->position->name,
                    'profile_image' => $employee->profile,
                    'contact_no' => $employee->contact_no,
                    'date_hired' => $employee->date_hired,
                ]);

                // Assign the Employee role
                $user->assignRole($employeeRole);

                // Send notification email
                $user->notify(new EmployeeAccountActivated($employee));

                $createdCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = "Failed to create user for {$employee->first_name} {$employee->last_name}: {$e->getMessage()}";
                Log::error("User creation failed for employee {$employee->id}: " . $e->getMessage());
            }
        }

        DB::commit();

        // Prepare the response message
        $message = "Successfully created {$createdCount} user accounts.";
        if ($failedCount > 0) {
            $message .= " Failed to create {$failedCount} accounts.";
        }

        $type = $failedCount > 0 ? 'warning' : 'success';

        return redirect()->route('employees.index')
            ->with($type, $message)
            ->with('errors', $errors);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Bulk user creation failed: ' . $e->getMessage());
        Log::error($e->getTraceAsString());

        return redirect()->route('employees.index')
            ->with('error', 'An error occurred during bulk user creation. Please try again.');
    }
}

/**
 * Generate a secure password-protected zip file for ID card download
 *
 * @param string $slug
 * @return \Illuminate\Http\JsonResponse
 */
public function downloadSecureIdCard($slug)
{
    try {
        // Find employee or throw 404
        $employee = Employee::where('slug', $slug)
            ->where('employee_status', 'Active')
            ->firstOrFail();

        // Generate a unique temporary directory name
        $tempDir = 'temp/' . uniqid('id_card_' . $employee->company_id . '_');
        Storage::makeDirectory('public/' . $tempDir);

        // Store the password temporarily (will be used client-side)
        $password = $employee->company_id;

        // Generate a secure random token for this download
        $downloadToken = bin2hex(random_bytes(32));
        
        // Store the token with expiration (15 minutes)
        Cache::put('id_card_download_' . $downloadToken, [
            'employee_id' => $employee->id,
            'password' => $password,
            'attempts' => 0
        ], now()->addMinutes(15));

        return response()->json([
            'success' => true,
            'download_token' => $downloadToken,
            'message' => 'Download token generated successfully',
            'expires_in' => 900 // 15 minutes in seconds
        ]);

    } catch (\Exception $e) {
        Log::error('Secure ID card download failed: ' . $e->getMessage());
        Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate secure download token'
        ], 500);
    }
}

/**
 * Process the secure download with the provided token
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function processSecureDownload(Request $request)
{
    try {
        $downloadToken = $request->input('download_token');
        
        // Verify token exists and is valid
        $downloadData = Cache::get('id_card_download_' . $downloadToken);
        
        if (!$downloadData) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired download token'
            ], 400);
        }

        // Increment attempt counter
        $downloadData['attempts']++;
        if ($downloadData['attempts'] > 3) {
            Cache::forget('id_card_download_' . $downloadToken);
            return response()->json([
                'success' => false,
                'message' => 'Maximum download attempts exceeded'
            ], 400);
        }
        
        Cache::put('id_card_download_' . $downloadToken, $downloadData, now()->addMinutes(15));

        // Find employee
        $employee = Employee::findOrFail($downloadData['employee_id']);

        // Generate unique filename with timestamp
        $timestamp = date('Ymd_His');
        $filename = "secure_id_card_{$employee->company_id}_{$timestamp}.zip";

        // Log download attempt
        Log::info('Secure ID card download initiated', [
            'employee_id' => $employee->company_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'success' => true,
            'password' => $downloadData['password'],
            'filename' => $filename,
            'timestamp' => $timestamp
        ]);

    } catch (\Exception $e) {
        Log::error('Secure download processing failed: ' . $e->getMessage());
        Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Failed to process secure download'
        ], 500);
    }
}

public function updateProfile(Request $request)
{
    try {
        // Get authenticated user
        $user = auth()->user();

        // Find employee with matching email
        $employee = Employee::where('email_address', $user->email)->first();

        // Check if employee exists and matches the authenticated user
        if (!$employee) {
            Log::warning('Unauthorized profile update attempt by user: ' . $user->id);
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this profile.'
            ], 403);
        }

        // Check if profile was updated within the last 60 days
        if ($employee->profile_updated_at && 
            now()->diffInDays($employee->profile_updated_at) < 60) {
            $nextUpdateDate = Carbon::parse($employee->profile_updated_at)->addDays(60);
            $daysRemaining = now()->diffInDays($nextUpdateDate);
            
            return response()->json([
                'success' => false,
                'message' => "Profile can only be updated every 60 days. Please wait {$daysRemaining} more days.",
                'next_update_date' => $nextUpdateDate->format('Y-m-d'),
                'days_remaining' => $daysRemaining
            ], 403);
        }

        // Validate the request with specific image requirements
        $validator = Validator::make($request->all(), [
            'profile' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Process the uploaded image
        if ($request->hasFile('profile')) {
            $image = $request->file('profile');

            // Generate unique filename with timestamp and employee ID
            $filename = 'profile_' . $employee->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = 'profiles/' . $filename;

            // Ensure the profiles directory exists
            if (!Storage::disk('public')->exists('profiles')) {
                Storage::disk('public')->makeDirectory('profiles');
            }

            // Delete old profile if exists
            if ($employee->profile && Storage::disk('public')->exists($employee->profile)) {
                Storage::disk('public')->delete($employee->profile);
            }

            // Store new profile image
            $stored = Storage::disk('public')->putFileAs(
                'profiles',
                $image,
                $filename
            );

            if (!$stored) {
                throw new \Exception('Failed to store profile image file');
            }

            // Update employee record with new profile and update timestamp
            $updated = $employee->update([
                'profile' => $path,
                'profile_updated_at' => now()
            ]);

            if (!$updated) {
                // If employee update fails, delete the uploaded image
                Storage::disk('public')->delete($path);
                throw new \Exception('Failed to update employee record');
            }

            // Also update the associated user's profile image if exists
            if ($user) {
                $user->update([
                    'profile_image' => $path
                ]);
            }

            Log::info('Profile image updated successfully for employee ID: ' . $employee->id, [
                'file_size' => $image->getSize(),
                'file_type' => $image->getMimeType(),
                'file_path' => $path,
                'next_update_available' => now()->addDays(60)->format('Y-m-d')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully',
                'path' => Storage::url($path),
                'next_update_available' => now()->addDays(60)->format('Y-m-d')
            ]);
        }

        throw new \Exception('No profile image file provided');

    } catch (\Exception $e) {
        Log::error('Profile image update failed: ' . $e->getMessage());
        Log::error($e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Failed to update profile image: ' . $e->getMessage()
        ], 500);
    }
}

public function getSignature(Employee $employee): JsonResponse
{
    return response()->json([
        'signature' => $employee->signature ? Storage::url($employee->signature) : null,
        'signature_path' => $employee->signature
    ]);
}

}
