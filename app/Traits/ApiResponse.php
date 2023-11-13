<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function successResponse(array $data = [], string $message = null, int $statusCode = 200, object $metadata = null): JsonResponse
    {
        $response = [
            'data' => $data,
        ];

        if (!is_null($message)) {
            $response['message'] = $message;
        }

        if (!is_null($metadata)) {
            $response['metadata'] = $metadata;
        }

        return response()->json($response, $statusCode);
    }

    public function validationResponse(Validator $validator): JsonResponse
    {
        return response()->json([
            'type' => 'validation',
            'validation' => $validator->errors(),
        ], 422);
    }

    public function errorResponse(string $type, object $errors = null, string $message = null, int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'type' => $type,
            'message' => $message,
            'error' => $errors
        ], $statusCode);
    }
}
