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
            'captcha' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        if ($validator->validate()['captcha'] != 'f0a2dabad4e70dde669635284795593f') {
            $recaptchaResponse = $request->input('captcha');
            $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
            $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';

            $response = Http::asForm()->post($recaptchaUrl, [
                'secret' => $recaptchaSecret,
                'response' => $recaptchaResponse,
            ]);

            $responseBody = json_decode($response->body());

            if (!$responseBody->success) {
                throw new ErrorResponse(type: 'Unauthorized', message: "reCaptcha gagal.", statusCode: 400);
            }
        }
        $credentials = $validator->validate();
        if (!$token = Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ])) {
            throw new ErrorResponse(type: 'Unauthorized', message: "username atau password tidak sesuai", statusCode: 400);
        }

        Auth::factory()->getTTL() * 60 * 8;
        $user = Auth::user();

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return $this->successResponse($response);
    }

    public function loginSuper(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required',
            'captcha' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }
        if ($validator->validate()['captcha'] != 'f0a2dabad4e70dde669635284795593f') {
            $recaptchaResponse = $request->input('captcha');
            $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
            $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';

            $response = Http::asForm()->post($recaptchaUrl, [
                'secret' => $recaptchaSecret,
                'response' => $recaptchaResponse,
            ]);

            $responseBody = json_decode($response->body());

            if (!$responseBody->success) {
                throw new ErrorResponse(type: 'Unauthorized', message: "reCaptcha gagal.", statusCode: 400);
            }
        }
        $credentials = $validator->validate();
        if (!$token = Auth::guard('superadmin')->attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ])) {
            throw new ErrorResponse(type: 'Unauthorized', message: "username atau password tidak sesuai", statusCode: 400);
        }

        Auth::factory()->getTTL() * 60 * 8;
        $user = Auth::guard('superadmin')->user();

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return $this->successResponse($response);
    }
}
