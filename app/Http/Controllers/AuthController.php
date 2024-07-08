<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            'g-recaptcha-response' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';

        $response = Http::asForm()->post($recaptchaUrl, [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
        ]);

        $responseBody = json_decode($response->body());
        if (!$responseBody->success) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'Verifikasi reCAPTCHA gagal.', statusCode: 400);
        }

        $credentials = $validator->validate();
        if (!$token = Auth::attempt($credentials)) {
            throw new ErrorResponse(type: 'Unauthorized', message:"username atau password tidak sesuai", statusCode: 400);
        }

        Auth::factory()->getTTL() * 60 * 8;
        $user = Auth::user();

        $authResponse = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->successResponse($authResponse);
    }
}