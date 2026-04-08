<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\ClassSubject;
use App\Models\Schedule;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = $request->user()->id;

        $dayMap = ['Mon' => 'mon', 'Tue' => 'tue', 'Wed' => 'wed', 'Thu' => 'thu', 'Fri' => 'fri', 'Sat' => 'sat', 'Sun' => 'sun'];
        $today = $dayMap[now()->format('D')] ?? null;

        $schedule = [];
        if ($today) {
            $schedule = Schedule::with(['classSubject.schoolClass', 'classSubject.subject', 'timeSlot'])
                ->whereHas('classSubject', fn ($q) => $q->where('teacher_id', $teacherId))
                ->where('day_of_week', $today)
                ->get()
                ->sortBy(fn ($s) => $s->timeSlot?->start_time)
                ->values();
        }

        // Stats
        $myClasses = ClassSubject::where('teacher_id', $teacherId)->get();
        $classIds = $myClasses->pluck('school_class_id')->unique();
        $totalStudents = ClassStudent::whereIn('school_class_id', $classIds)->distinct('student_id')->count('student_id');

        $todayDate = now()->toDateString();
        $todayTotal = Attendance::whereIn('class_subject_id', $myClasses->pluck('id'))
            ->where('date', $todayDate)->count();
        $todayPresent = Attendance::whereIn('class_subject_id', $myClasses->pluck('id'))
            ->where('date', $todayDate)->whereIn('status', ['present', 'late'])->count();

        return response()->json([
            'today' => $today,
            'schedule' => $schedule,
            'stats' => [
                'totalClasses' => $myClasses->count(),
                'totalStudents' => $totalStudents,
                'todayAttendance' => $todayTotal > 0 ? round(($todayPresent / $todayTotal) * 100) : 0,
            ],
        ]);
    }
}
