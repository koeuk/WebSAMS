<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Reports/Index', [
            'semesters' => Semester::orderBy('start_date', 'desc')->get(),
            'courses' => Course::all(['id', 'name', 'code']),
            'classes' => SchoolClass::all(['id', 'name']),
            'subjects' => Subject::all(['id', 'name', 'code']),
        ]);
    }

    public function generate(Request $request)
    {
        // If semester selected, use its dates
        if ($request->filled('semester_id')) {
            $semester = Semester::findOrFail($request->semester_id);
            $request->merge([
                'date_from' => $semester->start_date->toDateString(),
                'date_to' => $semester->end_date->toDateString(),
            ]);
        }

        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $query = Attendance::with(['student', 'classSubject.subject.course', 'classSubject.schoolClass'])
            ->whereBetween('date', [$request->date_from, $request->date_to]);

        if ($request->filled('course_id')) {
            $query->whereHas('classSubject.subject', fn ($q) => $q->where('course_id', $request->course_id));
        }

        if ($request->filled('class_id')) {
            $query->whereHas('classSubject', fn ($q) => $q->where('school_class_id', $request->class_id));
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('classSubject', fn ($q) => $q->where('subject_id', $request->subject_id));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
        })->sortByDesc('late')->values();

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

        $semester = $request->filled('semester_id') ? Semester::find($request->semester_id) : null;

        return Inertia::render('Reports/Show', [
            'studentSummary' => $studentSummary,
            'overall' => $overall,
            'semester' => $semester,
            'filters' => $request->only(['semester_id', 'course_id', 'class_id', 'subject_id', 'status', 'date_from', 'date_to']),
            'semesters' => Semester::orderBy('start_date', 'desc')->get(),
            'courses' => Course::all(['id', 'name', 'code']),
            'classes' => SchoolClass::all(['id', 'name']),
            'subjects' => Subject::all(['id', 'name', 'code']),
        ]);
    }
}
