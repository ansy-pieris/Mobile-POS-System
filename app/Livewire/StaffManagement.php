<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Staff Management Livewire Component
 * 
 * Allows admin users to:
 * - Create new staff members with name and password
 * - Reset staff member passwords
 * - View all staff members
 * - Delete staff members
 */
class StaffManagement extends Component
{
    use WithPagination;

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    
    // Edit mode
    public $staffId = null;
    public $isEditing = false;
    public $isResettingPassword = false;
    
    // Search
    public $search = '';

    protected function rules()
    {
        $rules = [];

        // When resetting password, only password is required
        if ($this->isResettingPassword) {
            $rules['password'] = 'required|string|min:8|confirmed';
            return $rules;
        }

        // For creating or editing, name and email are required
        $rules['name'] = 'required|string|max:255';
        $rules['email'] = 'required|email|max:255';

        if (!$this->isEditing) {
            // Creating new user - password required and email must be unique
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['email'] .= '|unique:users,email';
        } else {
            // Editing - email unique except for current user
            $rules['email'] .= '|unique:users,email,' . $this->staffId;
        }

        return $rules;
    }

    public function render()
    {
        // Ensure only admin can access
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not permitted to access this area. Contact the owner.');
        }

        $query = User::where('role', 'staff');
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.staff-management', [
            'staffMembers' => $query->latest()->paginate(10)
        ])->layout('layouts.app');
    }

    /**
     * Create a new staff member
     */
    public function save()
    {
        $this->validate();

        if ($this->isResettingPassword) {
            // Reset password only
            $staff = User::findOrFail($this->staffId);
            $staff->update([
                'password' => Hash::make($this->password),
            ]);
            session()->flash('message', 'Password reset successfully for ' . $staff->name);
        } elseif ($this->isEditing) {
            // Update staff details (name/email only)
            $staff = User::findOrFail($this->staffId);
            $staff->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            session()->flash('message', 'Staff member updated successfully.');
        } else {
            // Create new staff
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'staff',
            ]);
            session()->flash('message', 'Staff member created successfully.');
        }

        $this->resetForm();
    }

    /**
     * Edit staff member details
     */
    public function edit($id)
    {
        $staff = User::where('role', 'staff')->findOrFail($id);
        $this->staffId = $id;
        $this->name = $staff->name;
        $this->email = $staff->email;
        $this->isEditing = true;
        $this->isResettingPassword = false;
    }

    /**
     * Initiate password reset for a staff member
     */
    public function resetPassword($id)
    {
        $staff = User::where('role', 'staff')->findOrFail($id);
        $this->staffId = $id;
        $this->name = $staff->name;
        $this->email = $staff->email;
        $this->isEditing = true;
        $this->isResettingPassword = true;
    }

    /**
     * Delete a staff member
     */
    public function delete($id)
    {
        $staff = User::where('role', 'staff')->findOrFail($id);
        
        // Don't allow deleting yourself
        if ($staff->id === auth()->id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        $staff->delete();
        session()->flash('message', 'Staff member deleted successfully.');
    }

    /**
     * Cancel editing
     */
    public function cancel()
    {
        $this->resetForm();
    }

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'staffId', 'isEditing', 'isResettingPassword']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
