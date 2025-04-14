<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

trait UpdateUser
{

    public function prepareUserData($request, $user)
    {
        $userData = $request->only(['firstname', 'lastname', 'username', 'email', 'is_active']);

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if (!empty($request->void_password)) {
            $userData['void_password'] = Hash::make($request->void_password);
        }

        return $userData;
    }

    public function handleImageUpload($request, $user, &$userData)
    {
        if ($request->hasFile('image')) {
            // Delete old image if exists
            $this->deleteOldImage($user);

            // Store the new image
            $imagePath = $request->file('image')->store('profile_images', 'public');
            $userData['image'] = $imagePath; // Store the relative path to the image
        }
    }

    public function deleteOldImage($user)
    {
        if (!empty($user->image) && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image); // Delete the old image
        }
    }

    public function updateRole($request, $user)
    {
        if ($request->filled('role')) {
            $role = $user->roles()->first();
            $role->update([
                'role_id' => $request->role
            ]);
        }
    }
}
