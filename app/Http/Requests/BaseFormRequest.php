<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation errors occurred',
                'errors' => $validator->errors(),
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
