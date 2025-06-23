<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProfileUpdated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Get user details by id or email
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetails(Request $request)
    {
        $request->validate([
            'user_id' => 'required_without:email|integer|exists:users,id',
            'email' => 'required_without:user_id|email|exists:users,email',
        ]);

        if ($request->has('user_id')) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = User::where('email', $request->email)->firstOrFail();
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the authenticated user's profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'bio' => 'nullable|string|max:255',
            'signature' => 'nullable|string',
        ]);

        // Create data array for updates
        $userData = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'bio' => $request->bio,
        ];
        
        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = $image->store('profile_images', 'public');
            $userData['profile_image'] = $filename;
        }

        if ($request->has('signature') && !empty($request->signature)) {
            $image = $request->input('signature');
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);

            $imageName = 'signature_' . $user->id . '_' . time() . '.png';
            $path = 'signatures/' . $imageName;

            if (!Storage::disk('public')->exists('signatures')) {
                Storage::disk('public')->makeDirectory('signatures');
            }

            if ($user->signature && Storage::disk('public')->exists($user->signature)) {
                Storage::disk('public')->delete($user->signature);
            }

            if (Storage::disk('public')->put($path, base64_decode($image))) {
                $userData['signature'] = $path;
            }
        }

        // Track changes before update
        $originalEmail = $user->email;
        
        // Update user with provided data
        $updated = User::where('id', $user->id)->update($userData);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile'
            ], 500);
        }

        // Get the fresh user data for mail notification
        $updatedUser = User::find($user->id);
        
        // Send email notification if email changed
        if ($originalEmail != $updatedUser->email) {
            try {
                Mail::to($updatedUser->email)->send(new ProfileUpdated($updatedUser, $userData));
            } catch (\Exception $e) {
                Log::error('Failed to send profile update email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $updatedUser
        ]);
    }

    /**
     * Update the user's signature
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSignature(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $request->validate([
                'signature' => 'required|string'
            ]);

            $image = $request->input('signature');
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
            $image = str_replace(' ', '+', $image);

            $imageName = 'signature_' . $user->id . '_' . time() . '.png';
            $path = 'signatures/' . $imageName;

            if (!Storage::disk('public')->exists('signatures')) {
                Storage::disk('public')->makeDirectory('signatures');
            }

            if ($user->signature && Storage::disk('public')->exists($user->signature)) {
                Storage::disk('public')->delete($user->signature);
            }

            $stored = Storage::disk('public')->put($path, base64_decode($image));

            if (!$stored) {
                throw new \Exception('Failed to store signature file');
            }

            $updated = User::where('id', $user->id)->update([
                'signature' => $path
            ]);

            if (!$updated) {
                throw new \Exception('Failed to update user record');
            }

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

    /**
     * Get detailed profile information
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        // You may want to load relationships here if needed
        // $user = User::with(['roles', 'permissions', 'department'])->find($user->id);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
} 