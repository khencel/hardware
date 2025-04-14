<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateLeisureRequest extends BaseFormRequest
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
            'item_name' => 'required|string|max:255',
            'type' => ['required', Rule::in(config('pww.leisure.types'))],
            'price_rate' => 'required|numeric|min:0',
            'counter' => ['required', Rule::in(config('pww.leisure.counter'))],
            // 'package_id' => 'required|integer|exists:packages,id',
            'availability' => ['required'],
            'image' => 'nullable|image'
        ];
    }
}
