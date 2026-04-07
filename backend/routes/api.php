<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [AuthController::class, 'login']);

// Authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Teacher routes
    Route::prefix('teacher')->middleware('role:teacher')->group(function () {
        // TODO: Phase 6
    });

    // Student routes
    Route::prefix('student')->middleware('role:student')->group(function () {
        // TODO: Phase 7
    });
});
