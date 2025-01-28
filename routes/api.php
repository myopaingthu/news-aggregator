<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\SourceController;
use App\Http\Controllers\API\UserPreferenceController;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index']);
    Route::get('/search', [NewsController::class, 'search']);
});

Route::get('/sources', [SourceController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('user_preferences', UserPreferenceController::class);
});
