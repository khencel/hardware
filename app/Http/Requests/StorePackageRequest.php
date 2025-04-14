<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends BaseFormRequest
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
            'display_name' => 'required|string|max:255',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'availability' => 'nullable|boolean',
        ];
    }
}
