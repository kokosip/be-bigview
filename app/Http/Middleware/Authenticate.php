<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use App\Exceptions\ErrorResponse;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'Anda tidak memiliki akses untuk fitur ini.', statusCode: 403);
        }
    }
}
