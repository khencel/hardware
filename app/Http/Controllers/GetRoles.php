<?php 

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;

class GetRoles extends Controller
{
    public function __invoke()
    {
        try {
            $roles = Role::query();

            return response()->json([
                'data' => $roles->get(),
                'code' => 200,
                'message' => 'Roles retrieved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
}