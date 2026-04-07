<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $todayTotal = Attendance::where('date', $today)->count();
        $todayPresent = Attendance::where('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();
        $todayRate = $todayTotal > 0 ? round(($todayPresent / $todayTotal) * 100) : 0;

        $recentAttendance = Attendance::with(['student', 'classSubject.subject', 'classSubject.schoolClass'])
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'totalStudents' => User::where('role', 'student')->count(),
                'totalTeachers' => User::where('role', 'teacher')->count(),
                'totalClasses' => SchoolClass::count(),
                'todayAttendance' => $todayRate,
            ],
            'recentAttendance' => $recentAttendance,
        ]);
    }
}
