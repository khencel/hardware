<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLeisureRequest extends BaseFormRequest
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
            'item_name' => 'sometimes|string|max:255',
            'type' => ['sometimes', Rule::in(config('pww.leisure.types'))],
            'price_rate' => 'sometimes|numeric|min:0',
            'counter' => ['sometimes', Rule::in(config('pww.leisure.counter'))],
            // 'package_id' => 'sometimes|integer|exists:packages,id',
            'availability' => ['sometimes'],
            'image' => 'nullable|image'
        ];
    }
}
