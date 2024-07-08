<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResponse;
use Exception;
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

        try {
            $recaptchaResponse = $request->input('captcha');
            $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
            $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';

            $response = Http::asForm()->post($recaptchaUrl, [
                'secret' => $recaptchaSecret,
                'response' => $recaptchaResponse,
            ]);

            $responseBody = json_decode($response->body());

            $credentials = $validator->validate();
            if (!Auth::attempt($credentials)) {
                throw new Exception('Username atau password tidak sesuai.');
            }

            // Authentication successful
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 200);

        } catch (Exception $e) {
            throw new ErrorResponse(message: $e->getMessage());
        }
    }
}