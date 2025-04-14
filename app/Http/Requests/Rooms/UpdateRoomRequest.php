<?php

namespace App\Http\Requests\Rooms;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends BaseFormRequest
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
            'room_category_id' => 'nullable|exists:room_categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'pax' => 'integer|min:0',
            'availability' => 'boolean',
        ];
    }
}
