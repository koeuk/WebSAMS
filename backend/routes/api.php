<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Api\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Api\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Api\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Api\Teacher\NotificationController as TeacherNotificationController;
use App\Http\Controllers\Api\Teacher\ExcuseRequestController as TeacherExcuseController;
use App\Http\Controllers\Api\Teacher\QrSessionController as TeacherQrController;
use App\Http\Controllers\Api\Teacher\AnnouncementController as TeacherAnnouncementController;
use App\Http\Controllers\Api\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Api\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Api\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Api\Student\ExcuseRequestController as StudentExcuseController;
use App\Http\Controllers\Api\Student\AnnouncementController as StudentAnnouncementController;
use App\Http\Controllers\Api\Student\QrAttendController;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/login', [AuthController::class, 'login']);

$buildLocaleResponse = function (array $enabled, string $default): array {
    return [
        'enabled_languages' => $enabled,
        'default_language'  => $default,
        'locales' => collect([
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'km', 'name' => 'ខ្មែរ'],
            ['code' => 'zh', 'name' => '中文'],
        ])->whereIn('code', $enabled)->values()->all(),
    ];
};

Route::get('/locales', function () use ($buildLocaleResponse) {
    return response()->json($buildLocaleResponse(
        \App\Http\Controllers\Dashboard\SettingController::enabledLanguages(),
        \App\Models\Setting::get('default_language', 'en'),
    ));
});

Route::get('/teacher/locales', function () use ($buildLocaleResponse) {
    return response()->json($buildLocaleResponse(
        \App\Http\Controllers\Dashboard\SettingController::teacherEnabledLanguages(),
        \App\Http\Controllers\Dashboard\SettingController::teacherDefaultLanguage(),
    ));
});

Route::get('/student/locales', function () use ($buildLocaleResponse) {
    return response()->json($buildLocaleResponse(
        \App\Http\Controllers\Dashboard\SettingController::studentEnabledLanguages(),
        \App\Http\Controllers\Dashboard\SettingController::studentDefaultLanguage(),
    ));
});

// Authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Teacher routes
    Route::prefix('teacher')->middleware('role:teacher')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index']);
        Route::get('/profile', [TeacherProfileController::class, 'show']);
        Route::get('/classes', [TeacherClassController::class, 'index']);
        Route::get('/classes/{classSubject}/students', [TeacherClassController::class, 'students']);
        Route::get('/time-slots', fn () => response()->json(TimeSlot::all()));
        Route::post('/attendance', [TeacherAttendanceController::class, 'store']);
        Route::get('/attendance', [TeacherAttendanceController::class, 'index']);
        Route::get('/notifications', [TeacherNotificationController::class, 'index']);
        Route::put('/notifications/{notification}/read', [TeacherNotificationController::class, 'markRead']);
        Route::post('/notifications/read-all', [TeacherNotificationController::class, 'markAllRead']);
        Route::get('/excuse-requests', [TeacherExcuseController::class, 'index']);
        Route::put('/excuse-requests/{excuseRequest}/review', [TeacherExcuseController::class, 'review']);
        Route::post('/qr-sessions', [TeacherQrController::class, 'store']);
        Route::delete('/qr-sessions/{qrSession}', [TeacherQrController::class, 'deactivate']);
        Route::get('/announcements', [TeacherAnnouncementController::class, 'index']);
        Route::post('/announcements', [TeacherAnnouncementController::class, 'store']);
        Route::put('/announcements/{announcement}', [TeacherAnnouncementController::class, 'update']);
        Route::delete('/announcements/{announcement}', [TeacherAnnouncementController::class, 'destroy']);
    });

    // Student routes
    Route::prefix('student')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index']);
        Route::get('/attendance', [StudentAttendanceController::class, 'index']);
        Route::get('/profile', [StudentProfileController::class, 'show']);
        Route::get('/notifications', fn (\Illuminate\Http\Request $r) => response()->json(
            \App\Models\Notification::where('user_id', $r->user()->id)->latest()->paginate(20)
        ));
        Route::put('/notifications/{notification}/read', function (\Illuminate\Http\Request $r, \App\Models\Notification $notification) {
            if ($notification->user_id !== $r->user()->id) abort(403);
            $notification->update(['is_read' => true]);
            return response()->json(['message' => 'Marked as read.']);
        });
        Route::get('/excuse-requests', [StudentExcuseController::class, 'index']);
        Route::post('/excuse-requests', [StudentExcuseController::class, 'store']);
        Route::get('/announcements', [StudentAnnouncementController::class, 'index']);
        Route::post('/attend', [QrAttendController::class, 'attend']);
    });
});
