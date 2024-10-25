<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Otp\ResendOtpRequest;
use App\Http\Requests\Api\Otp\VerifyOtpRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;



class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $phone = $validated['phone'];
        $password = $validated['password'];

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return ApiResponse::error('User not found.', 404);
        }

        if ($user->is_verified != User::IS_VERIFIED) {
            return ApiResponse::error('Phone number not verified.', 400);
        }

        if (!Hash::check($password, $user->password)) {
            return ApiResponse::error('Invalid credentials.', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $data['user'] = $user;
        $data['token'] = $token;

        return ApiResponse::success(
            $data,
            'Logged in successfully.'
        );
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $phone = $validated['phone'];

        $user = User::create($validated);

        $otp = rand(100000, 999999);

        $phone = '+20' . ltrim($phone, '0');

        Cache::put('otp_' . $phone, $otp, now()->addMinutes(10));


        $response = $this->sendOtp($phone, $otp);

        if (!$response) {
            return ApiResponse::error('Failed to send SMS. Please try again later.', 500);
        }

        return ApiResponse::success(
            $user,
            'User registered successfully. Please verify your phone number.',
        );
    }

    public function sendOtp($phone, $otp)
    {
        $messageData = [
            'messages' => [
                [
                    'destinations' => [
                        ['to' => $phone],
                    ],
                    'from' => env('INFOBIP_FROM_NUMBER'),
                    'text' => 'Your verification code is: ' . $otp . '. This code will expire in 10 minutes.',
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'App ' . env('INFOBIP_API_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(env('INFOBIP_BASE_URL') . '/sms/2/text/advanced', $messageData);

        if ($response->successful()) {
            return true;
        }
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {

        $validated = $request->validated();

        $phone = $validated['phone'];

        $otp = $validated['otp'];

        $phone = '+20' . ltrim($phone, '0');

        $otp = Cache::get('otp_' . $phone);

        if (!$otp) {
            return ApiResponse::error('OTP expired. Please request a new one.', 400);
        }

        if ($otp != $otp) {
            return ApiResponse::error('Invalid OTP.', 400);
        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return ApiResponse::error('User not found.', 404);
        }

        $user->update([
            'is_verified' => User::IS_VERIFIED,
        ]);

        Cache::forget('otp_' . $phone);

        return ApiResponse::success(
            $user,
            'Phone number verified successfully.',
        );
    }

    public function resendOtp(ResendOtpRequest $request)
    {

        $validated = $request->validated();
        $phone = $validated['phone'];

        $phone = '+20' . ltrim($phone, '0');

        $user = User::where('phone', $phone)->first();

        if ($user->is_verified == User::IS_VERIFIED) {
            return ApiResponse::error('Phone number already verified.', 400);
        }

        $otp = Cache::get('otp_' . $phone);

        if ($otp) {
            return ApiResponse::error('OTP already sent. Please check your phone.', 400);
        }

        $otp = rand(100000, 999999);

        Cache::put('otp_' . $phone, $otp, now()->addMinutes(10));

        $this->sendOtp($phone, $otp);

        return ApiResponse::success(
            $user,
            'OTP sent successfully. Please check your phone.',
        );
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return ApiResponse::success(
            [],
            'Logged out successfully.',
        );
    }
}
