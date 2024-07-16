<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Auth\SocialiteController;

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SampleController;
use App\Http\Controllers\Api\SurveyController;

Route::middleware(['api', 'web'])->group(function () {
    Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
});

Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ApiController::class, 'profile']);
    Route::get('logout', [ApiController::class, 'logout']);
    Route::post('change-password', [PasswordController::class, 'changePassword']);
    
    Route::get('projects', [ProjectController::class, 'index']);
    Route::post('projects', [ProjectController::class, 'store']);
    Route::get('samples/{id}', [SampleController::class, 'index']);
    Route::post('samples/{id}', [SampleController::class, 'store']);
    Route::post('samples/{id}/edit', [SampleController::class, 'edit']);
    Route::get('surveys/{id}', [SurveyController::class, 'index']);
    Route::post('surveys/{id}', [SurveyController::class, 'store']);
    Route::post('surveys/{id}/edit', [SurveyController::class, 'edit']);
});
