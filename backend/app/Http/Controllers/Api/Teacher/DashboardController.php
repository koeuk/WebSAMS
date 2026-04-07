<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dayMap = ['Mon' => 'mon', 'Tue' => 'tue', 'Wed' => 'wed', 'Thu' => 'thu', 'Fri' => 'fri'];
        $today = $dayMap[now()->format('D')] ?? null;

        $schedule = [];
        if ($today) {
            $schedule = Schedule::with(['classSubject.schoolClass', 'classSubject.subject'])
                ->whereHas('classSubject', fn ($q) => $q->where('teacher_id', $request->user()->id))
                ->where('day_of_week', $today)
                ->orderBy('start_time')
                ->get();
        }

        return response()->json([
            'today' => $today,
            'schedule' => $schedule,
        ]);
    }
}
