<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Api\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Api\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Api\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Api\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Api\Student\ProfileController as StudentProfileController;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [AuthController::class, 'login']);

// Authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Teacher routes
    Route::prefix('teacher')->middleware('role:teacher')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index']);
        Route::get('/classes', [TeacherClassController::class, 'index']);
        Route::get('/classes/{classSubject}/students', [TeacherClassController::class, 'students']);
        Route::get('/time-slots', fn () => response()->json(TimeSlot::all()));
        Route::post('/attendance', [TeacherAttendanceController::class, 'store']);
        Route::get('/attendance', [TeacherAttendanceController::class, 'index']);
    });

    // Student routes
    Route::prefix('student')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index']);
        Route::get('/attendance', [StudentAttendanceController::class, 'index']);
        Route::get('/profile', [StudentProfileController::class, 'show']);
    });
});
