<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $studentId = $request->user()->id;
        $records = Attendance::where('student_id', $studentId)->get();

        $total = $records->count();
        $summary = [
            'total' => $total,
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'rate' => $total > 0 ? round(($records->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
        ];

        // Per-subject breakdown
        $bySubject = Attendance::with('classSubject.subject')
            ->where('student_id', $studentId)
            ->get()
            ->groupBy('classSubject.subject.name')
            ->map(function ($subjectRecords, $subjectName) {
                $total = $subjectRecords->count();
                return [
                    'subject' => $subjectName,
                    'total' => $total,
                    'present' => $subjectRecords->where('status', 'present')->count(),
                    'absent' => $subjectRecords->where('status', 'absent')->count(),
                    'rate' => $total > 0 ? round(($subjectRecords->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
                ];
            })->values();

        // Enrolled classes
        $enrolledClasses = $request->user()->enrolledClasses()->get(['school_classes.id', 'name', 'section', 'academic_year']);

        return response()->json([
            'summary' => $summary,
            'bySubject' => $bySubject,
            'enrolledClasses' => $enrolledClasses,
        ]);
    }
}
