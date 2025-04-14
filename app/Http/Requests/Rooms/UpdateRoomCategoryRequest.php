<?php

namespace App\Http\Requests\Rooms;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomCategoryRequest extends BaseFormRequest
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
            'bed_type' => 'required|string|max:255',
            'near_at' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'availability' => 'boolean'
        ];
    }
}
