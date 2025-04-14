<?php

namespace App\Http\Traits;

trait ResponseFormatter
{

    public function success($data = [], $message = 'Successfully fetch resource.', $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function error($data = [], $message = 'Failed to fetch resource.')
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], 500);
    }
}
