<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    /**
     * Get all staff members.
     */
    public function index()
    {
        $staff = User::where('role', 'staff')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'created_at']);

        return response()->json($staff);
    }

    /**
     * Create a new staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $staff = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff member created successfully!',
            'staff' => $staff
        ]);
    }

    /**
     * Update staff member or reset password.
     */
    public function update(Request $request, User $staff)
    {
        // Ensure we're only modifying staff members
        if ($staff->role !== 'staff') {
            return response()->json([
                'success' => false,
                'message' => 'You can only modify staff members.'
            ], 403);
        }

        // Reset password mode
        if ($request->has('reset_password') && $request->reset_password) {
            $validated = $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);

            $staff->update([
                'password' => Hash::make($validated['password'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully!'
            ]);
        }

        // Regular update mode
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $staff->id],
        ]);

        $staff->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Staff member updated successfully!',
            'staff' => $staff
        ]);
    }

    /**
     * Delete a staff member.
     */
    public function destroy(User $staff)
    {
        // Ensure we're only deleting staff members
        if ($staff->role !== 'staff') {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete staff members.'
            ], 403);
        }

        $staff->delete();

        return response()->json([
            'success' => true,
            'message' => 'Staff member deleted successfully!'
        ]);
    }
}
