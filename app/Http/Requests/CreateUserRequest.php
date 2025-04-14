<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUserRequest extends BaseFormRequest
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
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'is_active' => 'required|boolean',
            'role' => 'required|integer|exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username is required.',
            'username.unique' => 'The username must be unique.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'firstname.required' => 'The first name is required.',
            'lastname.required' => 'The last name is required.',
            'is_active.required' => 'The active status is required.',
            'role.required' => 'The role is required.',
            'role.exists' => 'The selected role does not exist.',
        ];
    }
}
