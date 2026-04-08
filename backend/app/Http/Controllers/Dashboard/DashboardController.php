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

        // Weekly chart data (last 7 days)
        $weeklyChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $total = Attendance::where('date', $date)->count();
            $present = Attendance::where('date', $date)->where('status', 'present')->count();
            $absent = Attendance::where('date', $date)->where('status', 'absent')->count();
            $late = Attendance::where('date', $date)->where('status', 'late')->count();
            $weeklyChart[] = [
                'date' => now()->subDays($i)->format('M d'),
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'rate' => $total > 0 ? round(($present + $late) / $total * 100) : 0,
            ];
        }

        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'totalStudents' => User::where('role', 'student')->count(),
                'totalTeachers' => User::where('role', 'teacher')->count(),
                'totalClasses' => SchoolClass::count(),
                'todayAttendance' => $todayRate,
            ],
            'weeklyChart' => $weeklyChart,
            'recentAttendance' => $recentAttendance,
        ]);
    }
}
