<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');

    Route::post('/verify-otp', 'verifyOtp');
    Route::post('/resend-otp', 'resendOtp');
});


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::apiResource('tags', TagController::class);

    Route::apiResource('posts', PostController::class);

    Route::get('/deleted', [PostController::class, 'deletedPosts']);
    Route::post('/restore/{post}', [PostController::class, 'restoreDeletedPost']);

});
