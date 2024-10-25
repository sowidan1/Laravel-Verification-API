<?php

use App\Http\Controllers\Api\{
    AuthController,
    StatusController,
    TagController,
    PostController,
};

use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::prefix('auth')->controller(AuthController::class)->group(function () {

    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/verify-otp', 'verifyOtp');
    Route::post('/resend-otp', 'resendOtp');

    // Auth-specific Routes for logged-in users
    Route::post('/logout', 'logout')->middleware('auth:sanctum');

});

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {

    // Tag Management Routes
    Route::apiResource('tags', TagController::class);

    // Post Management Routes
    Route::apiResource('posts', PostController::class);
    Route::get('/soft-deleted', [PostController::class, 'deletedPosts']);
    Route::post('/soft-deleted/restore/{post}', [PostController::class, 'restoreDeletedPost']);

    // Statistics Route
    Route::get('/status', [StatusController::class, 'index']);

});
