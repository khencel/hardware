<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles.role')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user-management.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('user-management.process', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'nullable|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'nullable|min:8',
            'role' => 'required|exists:roles,id',
        ]);

        // Create the new user
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : null,
        ]);

        // Create a new UserRole record to associate the user and role
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'New User added.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user-management.process', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'username' => 'required|unique:users,username,' . $id,
            'password' => 'nullable|min:8',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update the user details
        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // Only hash the new password if provided
        ]);

        // Update the UserRole record (this assumes a one-to-one relation between User and Role)
        if ($request->has('role') && $request->role !== null) {
            UserRole::where('user_id', $user->id)
                ->update([
                    'role_id' => $request->role,
                    'updated_at' => now(),
                ]);
        }

        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Toggle the status of a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id, Request $request)
    {
        $user = User::findOrFail($id);

        // Toggle the status
        $user->is_active = ($request->status == 'Inactive') ? false : true;
        $user->save();

        // Return a response
        return response()->json([
            'status' => $user->status,
            'message' => 'Status updated successfully.',
        ]);
    }
}
