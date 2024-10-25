<?php

namespace App\Http\Services\Contracts;

interface AuthContract
{
    public function login($request);
    public function register($request);
    public function sendOtp($phone, $otp);
    public function verifyOtp($request);
    public function resendOtp($request);
    public function logout();
}
