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
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\StudentTrackingController;
use App\Http\Controllers\Dashboard\TimeSlotController;
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
    Route::get('/users/{user}/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::resource('courses', CourseController::class);
    Route::get('/courses/{course}/delete', [CourseController::class, 'delete'])->name('courses.delete');
    Route::resource('subjects', SubjectController::class);
    Route::get('/subjects/{subject}/delete', [SubjectController::class, 'delete'])->name('subjects.delete');
    Route::resource('classes', SchoolClassController::class)->parameters(['classes' => 'class']);
    Route::get('/classes/{class}/delete', [SchoolClassController::class, 'delete'])->name('classes.delete');
    Route::post('/class-subjects', [ClassSubjectController::class, 'store'])->name('class-subjects.store');
    Route::delete('/class-subjects/{classSubject}', [ClassSubjectController::class, 'destroy'])->name('class-subjects.destroy');
    Route::post('/class-students', [ClassStudentController::class, 'store'])->name('class-students.store');
    Route::delete('/class-students/{classStudent}', [ClassStudentController::class, 'destroy'])->name('class-students.destroy');
    Route::resource('attendance', AttendanceController::class);
    Route::resource('semesters', SemesterController::class)->except(['show']);
    Route::get('/semesters/{semester}/delete', [SemesterController::class, 'delete'])->name('semesters.delete');
    Route::resource('schedules', ScheduleController::class);
    Route::get('/schedules/{schedule}/delete', [ScheduleController::class, 'delete'])->name('schedules.delete');
    Route::get('/student-tracking', [StudentTrackingController::class, 'index'])->name('student-tracking.index');
    Route::get('/student-tracking/{student}', [StudentTrackingController::class, 'show'])->name('student-tracking.show');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::resource('time-slots', TimeSlotController::class)->except(['show']);
    Route::get('/time-slots/{timeSlot}/delete', [TimeSlotController::class, 'delete'])->name('time-slots.delete');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});
