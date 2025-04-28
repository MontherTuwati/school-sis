<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /** index page */
    public function index()
    {
        $users = User::with('department')->get();
        return view('usermanagement.index', compact('users'));
    }

    /** user view */
    public function show($id)
    {
        $user = User::with('department')->findOrFail($id);
        return view('usermanagement.show', compact('user'));
    }

    /** Add User */
    public function create()
    {
        $departments = Department::all();
        $roles = ['admin', 'teacher', 'student', 'staff'];
        return view('usermanagement.create', compact('roles', 'departments'));
    }

    /** store new user */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'role' => 'required|string|in:admin,teacher,student,staff',
            'department_id' => 'nullable|exists:departments,id',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->role = $request->role;
            $user->department_id = $request->department_id;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->join_date = Carbon::now();
            $user->password = Hash::make($request->password);
            $user->save();

            DB::commit();
            return redirect()->route('usermanagement.index')->with('success', 'User created successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Failed to create user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create user. Please try again.');
        }
    }

    /** user edit */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $roles = ['admin', 'teacher', 'student', 'staff'];
        return view('usermanagement.edit', compact('user', 'departments', 'roles'));
    }

    /** user Update */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'role' => 'required|string|in:admin,teacher,student,staff',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->role = $request->role;
            $user->department_id = $request->department_id;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->save();

            DB::commit();
            return redirect()->route('usermanagement.index')->with('success', 'User updated successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Failed to update user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update user. Please try again.');
        }
    }

    /** delete user */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting the current user
            if ($user->id === Auth::id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }
            
            $user->delete();
            
            DB::commit();
            return redirect()->route('usermanagement.index')->with('success', 'User deleted successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /** change password */
    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
            
            $user->password = Hash::make($request->new_password);
            $user->save();

            DB::commit();
            return redirect()->back()->with('success', 'Password changed successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Failed to change password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to change password. Please try again.');
        }
    }

    /** toggle user status */
    public function toggleStatus($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->is_active = !$user->is_active;
            $user->save();

            DB::commit();
            $status = $user->is_active ? 'activated' : 'deactivated';
            return redirect()->back()->with('success', "User {$status} successfully!");
        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Failed to toggle user status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update user status. Please try again.');
        }
    }
}
