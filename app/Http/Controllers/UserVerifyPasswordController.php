<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseFormatter;
use Exception;
use Illuminate\Support\Facades\Hash;


class UserVerifyPasswordController extends Controller
{
    use ResponseFormatter;
    public function verify()
    {
        try {
            $password = request()->get('password');

            $verified = Hash::check($password, auth()->user()->password);

            if (! $verified) {
                throw new Exception('Wrong password. Please try again.');
            }

            return $this->success([], 'Password verification successful.');
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
