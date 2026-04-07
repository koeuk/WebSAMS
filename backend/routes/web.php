<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\ClassStudentController;
use App\Http\Controllers\Dashboard\ClassSubjectController;
use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SchoolClassController;
use App\Http\Controllers\Dashboard\SubjectController;
use App\Http\Controllers\Dashboard\AttendanceController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\ScheduleController;
use App\Http\Controllers\Dashboard\SemesterController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to admin dashboard
Route::get('/', fn () => redirect('/admin/dashboard'));

// Admin routes (session auth + admin role)
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('classes', SchoolClassController::class)->parameters(['classes' => 'class']);
    Route::post('/class-subjects', [ClassSubjectController::class, 'store'])->name('class-subjects.store');
    Route::delete('/class-subjects/{classSubject}', [ClassSubjectController::class, 'destroy'])->name('class-subjects.destroy');
    Route::post('/class-students', [ClassStudentController::class, 'store'])->name('class-students.store');
    Route::delete('/class-students/{classStudent}', [ClassStudentController::class, 'destroy'])->name('class-students.destroy');
    Route::resource('attendance', AttendanceController::class);
    Route::resource('semesters', SemesterController::class)->except(['show']);
    Route::resource('schedules', ScheduleController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
});
