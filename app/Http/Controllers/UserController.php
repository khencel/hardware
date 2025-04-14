<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ResponseFormatter;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseFormatter;
    public function index(Request $request)
    {
        try {
            $users = User::query();

            // Check if soft-deleted users should be included
            if ($request->include_deleted) {
                $users->withTrashed();
            }

            $users->where('id', '!=', 1);
            return response()->json([
                'data' => $users->with('roles.role')->paginate(request()->get('per_page') ?? 10),
                'code' => 200,
                'message' => 'Users retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    // Create a new user
    public function store(CreateUserRequest $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:8|confirmed',
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'is_active' => 'required|boolean',
                'role' => 'required|integer|exists:roles,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation errors occurred',
                    'errors' => $validator->errors(),
                    'code' => 422
                ], 422);
            }

            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'is_active' => $request->is_active
            ]);

            // Assign role to the user
            $user->roles()->create(['role_id' => $request->role]);
            $user = User::with('roles.role')->find($user->id);

            DB::commit();
            return response()->json([
                'data' => $user,
                'code' => 200,
                'message' => 'User created successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    public function view($id)
    {
        try {
            $user = User::with('roles.role')->findOrFail($id);
            return response()->json([
                'data' => $user,
                'code' => 200,
                'message' => 'User retrieved successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    // Update an existing user
    public function update(Request $request)
    {
        try {
            $user = auth()->user(); // Get the currently authenticated user

            // Validation for new password only
            $rules = [
                'newPassword' => 'sometimes|string|min:6',  // New Password
                'confirmPassword' => 'sometimes|string|same:newPassword',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first(),
                    'code' => 422
                ], 422);
            }

            // Update the password
            $user->password = Hash::make($request->newPassword);
            $user->save();

            return response()->json([
                'message' => 'Password updated successfully',
                'code' => 200
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }


    // Soft delete a user
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();
            return response()->json([
                'code' => 200,
                'message' => 'User soft-deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    // Restore a soft-deleted user
    public function restore($id)
    {
        try {
            DB::beginTransaction();
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();
            $user = User::with('roles.role')->find($user->id);
            DB::commit();
            return response()->json([
                'data' => $user,
                'code' => 200,
                'message' => 'User restored successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = auth()->user(); // Get the currently authenticated user

            $userData = $request->only(['firstname', 'lastname', 'username', 'email', 'is_active']);

            // if ($request->filled('password')) {
            //     $userData['password'] = Hash::make($request->password);
            // }

            // Handle image upload and remove the old image if exists
            if ($request->hasFile('image')) {
                if (! empty($user->image)) {
                    if (Storage::disk('public')->exists($user->image)) {
                        Storage::disk('public')->delete($user->image); // Delete the old image
                    }
                }

                // Store the new image in the 'profile_images' folder in the 'public' disk
                $imagePath = $request->file('image')->store('profile_images', 'public');
                $userData['image'] = $imagePath; // Store the relative path to the image
            }

            if (!empty($request->void_password)) {
                $userData['void_password'] = Hash::make($request->void_password);
            }

            if ($request->filled('role')) {
                $role = $user->roles()->first();
                $role->update([
                    'role_id' => $request->role
                ]);
            }

            // Update user details
            $user->update($userData);

            return back()->with('success', 'Profile updated successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
