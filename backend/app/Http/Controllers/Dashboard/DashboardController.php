<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'totalStudents' => 0,
                'totalTeachers' => 0,
                'totalClasses' => 0,
                'todayAttendance' => 0,
            ],
        ]);
    }
}
