<?php
namespace App\Http\Services\Services;

use App\Http\Responses\ApiResponse;
use App\Http\Services\Contracts\AuthContract;

use App\Models\{
    Post,
    User,
};

use Illuminate\Support\Facades\{
    Cache,
    Hash,
    Http,
};

class AuthService implements AuthContract
{
    public function login($request)
    {
        $validated = $request->validated();
        $phone = $validated['phone'];
        $password = $validated['password'];

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return ApiResponse::error('User not found.', [],404);
        }

        if ($user->is_verified !== User::IS_VERIFIED) {
            return ApiResponse::error('Phone number not verified.', [],400);
        }

        if (!Hash::check($password, $user->password)) {
            return ApiResponse::error('Invalid credentials.', [],401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'token' => $token,
        ], 'Logged in successfully.');
    }

    public function register($request)
    {
        $validated = $request->validated();

        $otp = rand(100000, 999999);

        $phone = '+20' . ltrim($validated['phone'], '0');

        $user = User::create($validated);

        Cache::put('otp_' . $phone, $otp, now()->addMinutes(10));

        $response = $this->sendOtp($phone, $otp);

        if (!$response) {
            return ApiResponse::error('Failed to send SMS. Please try again later.', [], 500);
        }

        // Uncomment to automatically verify the phone number
        // $user->update(['is_verified' => User::IS_VERIFIED]);

        return ApiResponse::success($user, 'User registered successfully. Please verify your phone number.');
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

    public function verifyOtp($request)
    {
        $validated = $request->validated();

        $phone = '+20' . ltrim($validated['phone'], '0');

        $inputOtp = $validated['otp'];

        $cachedOtp = Cache::get('otp_' . $phone);

        if (!$cachedOtp) {
            return ApiResponse::error('OTP expired. Please request a new one.', [], 400);
        }

        if ($inputOtp !== $cachedOtp) {
            return ApiResponse::error('Invalid OTP.', [], 400);
        }

        $user = User::where('phone', $validated['phone'])->first();

        if (!$user) {
            return ApiResponse::error('User not found.', [], 404);
        }

        $user->update(['is_verified' => User::IS_VERIFIED]);

        Cache::forget('otp_' . $phone);

        return ApiResponse::success($user, 'Phone number verified successfully.');
    }


    public function resendOtp($request)
    {

        $validated = $request->validated();
        $phone = $validated['phone'];

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return ApiResponse::error('User not found.', [], 404);
        }

        if ($user->is_verified === User::IS_VERIFIED) {
            return ApiResponse::error('Phone number already verified.', [],400);
        }

        $phone = '+20' . ltrim($phone, '0');

        $otp = Cache::get('otp_' . $phone);

        if ($otp) {
            return ApiResponse::error('OTP already sent. Please check your phone.', [],400);
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

    public function index()
    {
        $stats = Cache::remember('stats', 60 * 60, function () {
            return [
                'total_users' => User::count(),
                'total_posts' => Post::count(),
                'users_with_no_posts' => User::doesntHave('posts')->count(),
            ];
        });

        return ApiResponse::success($stats, 'Statistics retrieved successfully.');
    }
}
