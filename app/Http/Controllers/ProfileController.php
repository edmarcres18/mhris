<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProfileUpdated; // Import the ProfileUpdated Mailable class
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

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

        $originalData = $user->toArray();

        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->suffix = $request->suffix;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = $image->store('profile_images', 'public');
            $user->profile_image = $filename;
        }

        $user->bio = $request->bio;

        if ($request->has('signature')) {
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
                $user->signature = $path;
            }
        }

        $user->save();

        $updatedData = $user->fresh()->toArray();
        $changedData = array_diff_assoc($updatedData, $originalData);

        if (!empty($changedData)) {
            Mail::to($user->email)->send(new ProfileUpdated($user, $changedData));
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updateSignature(Request $request)
    {
        try {
            $user = Auth::user();

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

            $updated = $user->update([
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

    public function details()
    {
        $user = Auth::user();
        return view('profile.details', compact('user'));
    }
}
