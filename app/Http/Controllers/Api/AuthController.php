<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\{
    LoginRequest,
    RegisterRequest,
};
use App\Http\Requests\Api\Otp\{
    ResendOtpRequest,
    VerifyOtpRequest,
};
use App\Http\Services\Facades\AuthFacade;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        return AuthFacade::login($request);
    }

    public function register(RegisterRequest $request)
    {
        return AuthFacade::register($request);
    }

    public function sendOtp($phone, $otp)
    {
        return AuthFacade::sendOtp($phone, $otp);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        return AuthFacade::verifyOtp($request);
    }

    public function resendOtp(ResendOtpRequest $request)
    {
        return AuthFacade::resendOtp($request);
    }

    public function logout()
    {
        return AuthFacade::logout();
    }
}
