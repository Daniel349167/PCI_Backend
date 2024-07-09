<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Auth\SocialiteController;

Route::middleware(['api', 'web'])->group(function () {
    Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
});
use App\Http\Controllers\Api\ProjectController;

Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ApiController::class, 'profile']);
    Route::get('logout', [ApiController::class, 'logout']);
    Route::post('change-password', [PasswordController::class, 'changePassword']);
    Route::get('projects', [ProjectController::class, 'index']);
});
