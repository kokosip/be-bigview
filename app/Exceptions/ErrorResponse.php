<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

use Exception;

class ErrorResponse extends Exception
{
    protected $type;
    protected $errors;
    protected $statusCode;

    public function __construct(string $type = null, mixed $errors = null, string $message = null, int $statusCode = 500)
    {
        parent::__construct($message, $statusCode);

        $this->type = $type;
        $this->errors = $errors;
        $this->statusCode = $statusCode;
    }

    public function render(): JsonResponse
    {
        if ($this->errors == null) {
            return response()->json([
                'type' => $this->type,
                'message' => $this->getMessage()
            ], $this->statusCode);
        } else {
            return response()->json([
                'type' => $this->type,
                'message' => $this->getMessage()
            ], $this->statusCode);
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
