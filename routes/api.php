<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Auth\SocialiteController;

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SampleController;
use App\Http\Controllers\Api\DamageController;

use App\Http\Controllers\Api\DamageMeasurementController;
use App\Http\Controllers\Api\DeductedValuesController;
use App\Http\Controllers\Api\PCIController;

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
    Route::get('projects/{id}/samples', [SampleController::class, 'index']);
    Route::post('samples/{id}', [SampleController::class, 'store']);
    Route::get('samples/{id}', [SampleController::class, 'read']);
    Route::post('samples/{id}/update', [SampleController::class, 'update']);
    Route::get('samples/{id}/damages', [DamageController::class, 'index']);
    Route::post('damages/{id}', [DamageController::class, 'store']);
    Route::get('damages/{id}', [DamageController::class, 'read']);
    Route::post('damages/{id}/update', [DamageController::class, 'update']);
    Route::get('damages/{id}/image', [DamageController::class, 'getImage']);

    //Metrado de Daños
    Route::get('damage-measurement/{projectId}/{sampleId}', [DamageMeasurementController::class, 'getSummaryBySampleId']);

    //Valores Deducidos
    Route::get('deducted-values/{projectId}/{sampleId}', [DeductedValuesController::class, 'getDeductedValues']);

    //PCI
    Route::get('calculate-pci/{projectId}', [PCIController::class, 'calculatePCI']);
});
