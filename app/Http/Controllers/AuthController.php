<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use ApiResponse;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $credentials = $validator->validate();
        if (!$token = Auth::attempt($credentials)) {
            return $this->errorResponse(type: 'Unauthorized', message:"username atau password tidak sesuai", statusCode: 400);
        }

        Auth::factory()->getTTL() * 60 * 8;
        $user = Auth::user();

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->successResponse($response);
    }
}