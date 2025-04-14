<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends BaseFormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nationality' => 'required|string|max:50',
            'type' => 'required|string',
            'check_in_date' => 'required|date',
            'check_out_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $checkInDate = request('check_in_date');
                    $type = request('type');

                    if (!$checkInDate) {
                        return; // No need to validate if check_in_date is missing
                    }

                    if ($type !== 'tour' && $value <= $checkInDate) {
                        $fail('The check-out date must be after the check-in date.');
                    }
                }
            ],
            'remarks' => 'nullable|string',
        ];
    }
}
