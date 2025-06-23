<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Constructor for setting up middleware
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login', 'forgotPassword', 'resetPassword', 'checkAuth']);
    }

    /**
     * Update user's last seen timestamp to track online status
     */
    private function updateLastSeen()
    {
        if (Auth::check()) {
            User::where('id', Auth::id())->update(['last_seen' => now()]);
        }
    }

    /**
     * Check if user has Employee or Supervisor role
     * 
     * @param User $user
     * @return bool
     */
    private function hasRequiredRole(User $user)
    {
        // Get role IDs for Employee and Supervisor
        $roles = Role::whereIn('name', ['Employee', 'Supervisor'])->pluck('id');
        
        // Check if user has any of these roles
        $hasRole = DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->where('model_type', get_class($user))
            ->whereIn('role_id', $roles)
            ->exists();
            
        return $hasRole;
    }
    
    /**
     * Check if user has Supervisor role
     * 
     * @param User $user
     * @return bool
     */
    private function hasSupervisorRole(User $user)
    {
        // Get role ID for Supervisor
        $roleId = Role::where('name', 'Supervisor')->value('id');
        
        // Check if user has Supervisor role
        $hasSupervisorRole = DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->where('model_type', get_class($user))
            ->where('role_id', $roleId)
            ->exists();
            
        return $hasSupervisorRole;
    }

    /**
     * Check if user has required roles and matching email
     * 
     * @return \Illuminate\Http\JsonResponse|null
     */
    private function checkRoleAndEmail()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user has either Employee or Supervisor role
            if (!$this->hasRequiredRole($user)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized. Only employees and supervisors can access this API.'
                ], 403);
            }
            
            // For Supervisors, check if their email matches any employee's email_address
            if ($this->hasSupervisorRole($user)) {
                $employee = Employee::where('email_address', $user->email)->first();
                if (!$employee) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized. Supervisor email must match an employee email address.'
                    ], 403);
                }
            }
        }
        
        return null;
    }

    /**
     * Handle user login request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find the user
            $user = User::where('email', $request->email)->first();

            // Check if user exists and has correct password
            if (!$user || !Hash::check($request->password, $user->password)) {
                // Log failed login attempt if user exists
                if ($user) {
                    LoginHistory::create([
                        'user_id' => $user->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'login_at' => now(),
                        'login_successful' => false
                    ]);
                }

                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            // Check if user has Employee or Supervisor role
            if (!$this->hasRequiredRole($user)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized. Only employees and supervisors can access the mobile app.'
                ], 403);
            }
            
            // For Supervisors, check if their email matches any employee's email_address
            if ($this->hasSupervisorRole($user)) {
                $employee = Employee::where('email_address', $user->email)->first();
                if (!$employee) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized. Supervisor email must match an employee email address.'
                    ], 403);
                }
            }

            // Check if user account is disabled
            if ($user->status === 'disabled') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is disabled.'
                ], 403);
            }

            // Create token for the device
            $token = $user->createToken($request->device_name)->plainTextToken;

            // Update device token if provided (for push notifications)
            if ($request->has('device_token')) {
                $user->update([
                    'device_token' => $request->device_token,
                    'last_seen' => now() // Update last_seen timestamp for online status tracking
                ]);
            } else {
                $user->update(['last_seen' => now()]); // Update last_seen timestamp
            }

            // Log successful login
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'login_successful' => true
            ]);

            // Get user roles
            $roleNames = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->pluck('roles.name');

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                        'is_online' => true,
                        'roles' => $roleNames,
                    ],
                    'token' => $token,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle user logout request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Update last_seen timestamp
            $this->updateLastSeen();
            
            // Check roles
            $roleCheck = $this->checkRoleAndEmail();
            if ($roleCheck) {
                return $roleCheck;
            }
            
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get authenticated user details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        try {
            // Update last_seen timestamp
            $this->updateLastSeen();
            
            // Check roles
            $roleCheck = $this->checkRoleAndEmail();
            if ($roleCheck) {
                return $roleCheck;
            }
            
            $user = $request->user();
            
            // Load employee relation if it exists
            $user->load('employee');
            
            // Determine user's online status
            $onlineStatus = $user->last_seen >= now()->subMinutes(2) ? 'Online' : 'Offline';
            
            // Get user roles
            $roleNames = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->pluck('roles.name');
            
            return response()->json([
                'status' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                        'is_online' => $onlineStatus,
                        'last_seen' => $user->last_seen,
                        'roles' => $roleNames,
                        'employee' => $user->employee ? [
                            'id' => $user->employee->id,
                            'employee_id' => $user->employee->employee_id,
                            'department' => $user->employee->department ? $user->employee->department->name : null,
                            'position' => $user->employee->position ? $user->employee->position->name : null,
                        ] : null,
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh user token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        try {
            // Update last_seen timestamp
            $this->updateLastSeen();
            
            // Check roles
            $roleCheck = $this->checkRoleAndEmail();
            if ($roleCheck) {
                return $roleCheck;
            }
            
            $user = $request->user();
            
            // Revoke the current token
            $request->user()->currentAccessToken()->delete();
            
            // Create a new token
            $token = $user->createToken($request->device_name ?? 'mobile_app')->plainTextToken;
            
            return response()->json([
                'status' => true,
                'message' => 'Token refreshed successfully',
                'data' => [
                    'token' => $token
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send password reset link
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate a random 6-digit OTP
            $otp = random_int(100000, 999999);

            // Store the OTP in the database
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($otp),
                    'created_at' => now()
                ]
            );

            // In a real application, you would send this OTP via email
            // For this example, we'll just return it in the response
            return response()->json([
                'status' => true,
                'message' => 'Password reset OTP has been sent to your email',
                'data' => [
                    'otp' => $otp // In production, remove this and send via email instead
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset password with OTP
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find the token record
            $tokenRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$tokenRecord) {
                return response()->json([
                    'status' => false,
                    'message' => 'No password reset request found for this email'
                ], 404);
            }

            // Check if token is valid
            if (!Hash::check($request->otp, $tokenRecord->token)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP'
                ], 400);
            }

            // Check if token is expired (e.g., older than 60 minutes)
            if (Carbon::parse($tokenRecord->created_at)->addMinutes(60)->isPast()) {
                return response()->json([
                    'status' => false,
                    'message' => 'OTP has expired'
                ], 400);
            }

            // Update the user's password
            $user = User::where('email', $request->email)->first();
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Delete the token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return response()->json([
                'status' => true,
                'message' => 'Password has been reset successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            // Update last_seen timestamp
            $this->updateLastSeen();
            
            // Check roles
            $roleCheck = $this->checkRoleAndEmail();
            if ($roleCheck) {
                return $roleCheck;
            }
            
            $user = $request->user();
            
            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
                'profile_image' => 'sometimes|image|mimes:jpeg,png,jpg',
                'current_password' => 'sometimes|required_with:new_password',
                'new_password' => 'sometimes|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check current password if attempting to change password
            if ($request->has('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Current password is incorrect'
                    ], 400);
                }
            }

            // Update basic info
            $userData = [];
            
            if ($request->has('first_name')) {
                $userData['first_name'] = $request->first_name;
            }
            
            if ($request->has('last_name')) {
                $userData['last_name'] = $request->last_name;
            }
            
            if ($request->has('email')) {
                $userData['email'] = $request->email;
            }
            
            if ($request->has('new_password')) {
                $userData['password'] = Hash::make($request->new_password);
            }

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('profile-images', 'public');
                $userData['profile_image'] = $imagePath;
            }

            $user->update($userData);
            
            // Get user roles
            $roleNames = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->pluck('roles.name');

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                        'is_online' => true,
                        'roles' => $roleNames,
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user's session is valid based on session lifetime from env
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAuth(Request $request)
    {
        try {
            // Check if there's a valid token in the request
            if ($request->bearerToken()) {
                $token = DB::table('personal_access_tokens')
                    ->where('token', hash('sha256', $request->bearerToken()))
                    ->first();
                
                if (!$token) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Unauthenticated',
                        'is_authenticated' => false
                    ], 200); // Using 200 to allow Flutter to process the response
                }
                
                // Check if token has expired based on .env session lifetime
                $sessionLifetime = config('session.lifetime', 120); // Default 120 minutes if not set
                $tokenCreatedAt = Carbon::parse($token->created_at);
                $isExpired = $tokenCreatedAt->addMinutes($sessionLifetime)->isPast();
                
                if ($isExpired) {
                    // Revoke the token
                    DB::table('personal_access_tokens')
                        ->where('token', hash('sha256', $request->bearerToken()))
                        ->delete();
                    
                    return response()->json([
                        'status' => false,
                        'message' => 'Session expired',
                        'is_authenticated' => false
                    ], 200);
                }
                
                // Get user and update last_seen
                $user = User::find($token->tokenable_id);
                if ($user) {
                    $user->update(['last_seen' => now()]);
                    
                    // Check roles
                    if (!$this->hasRequiredRole($user)) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Unauthorized. Only employees and supervisors can access this API.',
                            'is_authenticated' => false
                        ], 200);
                    }
                    
                    // For Supervisors, check if their email matches any employee's email_address
                    if ($this->hasSupervisorRole($user)) {
                        $employee = Employee::where('email_address', $user->email)->first();
                        if (!$employee) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Unauthorized. Supervisor email must match an employee email address.',
                                'is_authenticated' => false
                            ], 200);
                        }
                    }
                    
                    // User is authenticated and session is valid
                    return response()->json([
                        'status' => true,
                        'message' => 'Authenticated',
                        'is_authenticated' => true,
                        'data' => [
                            'user' => [
                                'id' => $user->id,
                                'first_name' => $user->first_name,
                                'last_name' => $user->last_name,
                                'email' => $user->email
                            ]
                        ]
                    ], 200);
                }
            }
            
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
                'is_authenticated' => false
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'is_authenticated' => false
            ], 500);
        }
    }
}