<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BlockedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get user profile details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile($id)
    {
        try {
            $user = User::with(['department', 'position'])->findOrFail($id);
            
            // Get additional user data
            $lastSeen = $user->last_seen;
            $profileImage = $user->adminlte_image();
            
            // Check if this user is blocked by the current user
            $isBlocked = BlockedUser::where('user_id', Auth::id())
                ->where('blocked_user_id', $user->id)
                ->exists();
            
            return response()->json([
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'position' => $user->position,
                'department' => $user->department,
                'profile_image' => $profileImage,
                'last_seen' => $lastSeen,
                'is_blocked' => $isBlocked
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve user profile',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Block a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function blockUser(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);
            
            $currentUser = Auth::user();
            $blockedUserId = $request->user_id;
            
            // Don't allow blocking yourself
            if ($currentUser->id == $blockedUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot block yourself'
                ], 400);
            }
            
            // Check if already blocked
            $alreadyBlocked = BlockedUser::where('user_id', $currentUser->id)
                ->where('blocked_user_id', $blockedUserId)
                ->exists();
                
            if ($alreadyBlocked) {
                return response()->json([
                    'success' => true,
                    'message' => 'User is already blocked'
                ]);
            }
            
            // Create new block
            $block = new BlockedUser();
            $block->user_id = $currentUser->id;
            $block->blocked_user_id = $blockedUserId;
            $block->save();
            
            return response()->json([
                'success' => true,
                'message' => 'User has been blocked successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to block user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Unblock a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unblockUser(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);
            
            $currentUser = Auth::user();
            $blockedUserId = $request->user_id;
            
            // Remove the block
            $deleted = BlockedUser::where('user_id', $currentUser->id)
                ->where('blocked_user_id', $blockedUserId)
                ->delete();
                
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'User has been unblocked successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User was not blocked'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unblock user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 