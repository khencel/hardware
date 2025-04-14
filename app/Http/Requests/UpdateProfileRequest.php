<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username'   => 'sometimes|string|max:255|unique:users,username,' . auth()->user()->id,
            'password'   => 'nullable|string|min:6|confirmed',  // Confirm Password
            'firstname'  => 'sometimes|string|max:255',
            'lastname'   => 'sometimes|string|max:255',
            'is_active'  => 'sometimes|boolean',
            'role'       => 'sometimes|exists:roles,id',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png',
            'void_password' => 'nullable'
        ];
    }
}
