<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Reports/Index', [
            'classes' => SchoolClass::all(['id', 'name']),
            'subjects' => Subject::all(['id', 'name']),
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $query = Attendance::with(['student', 'classSubject.subject', 'classSubject.schoolClass'])
            ->whereBetween('date', [$request->date_from, $request->date_to]);

        if ($request->filled('class_id')) {
            $query->whereHas('classSubject', fn ($q) => $q->where('school_class_id', $request->class_id));
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('classSubject', fn ($q) => $q->where('subject_id', $request->subject_id));
        }

        $records = $query->get();

        // Per-student summary
        $studentSummary = $records->groupBy('student_id')->map(function ($studentRecords) {
            $total = $studentRecords->count();
            return [
                'student' => $studentRecords->first()->student,
                'total' => $total,
                'present' => $studentRecords->where('status', 'present')->count(),
                'absent' => $studentRecords->where('status', 'absent')->count(),
                'late' => $studentRecords->where('status', 'late')->count(),
                'excused' => $studentRecords->where('status', 'excused')->count(),
                'rate' => $total > 0 ? round(($studentRecords->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
            ];
        })->sortByDesc('rate')->values();

        // Overall summary
        $total = $records->count();
        $overall = [
            'total' => $total,
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'rate' => $total > 0 ? round(($records->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
        ];

        return Inertia::render('Reports/Show', [
            'studentSummary' => $studentSummary,
            'overall' => $overall,
            'filters' => $request->only(['class_id', 'subject_id', 'date_from', 'date_to']),
            'classes' => SchoolClass::all(['id', 'name']),
            'subjects' => Subject::all(['id', 'name']),
        ]);
    }
}
