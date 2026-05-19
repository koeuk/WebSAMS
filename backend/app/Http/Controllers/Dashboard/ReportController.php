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
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Reports/Index', [
            'semesters' => Semester::orderBy('start_date', 'desc')->get(),
            'courses'   => Course::all(['id', 'name', 'code']),
            'classes'   => SchoolClass::all(['id', 'name']),
            'subjects'  => Subject::all(['id', 'name', 'code']),
        ]);
    }

    public function generate(Request $request)
    {
        $filters    = $request->input('filter', []);
        $semesterId = $filters['semester_id'] ?? null;
        $dateFrom   = $filters['date_from'] ?? null;
        $dateTo     = $filters['date_to'] ?? null;

        if ($semesterId) {
            $semester = Semester::findOrFail($semesterId);
            $dateFrom = $semester->start_date->toDateString();
            $dateTo   = $semester->end_date->toDateString();
        }

        $request->merge(['date_from' => $dateFrom, 'date_to' => $dateTo]);
        $request->validate([
            'date_from' => 'required|date',
            'date_to'   => 'required|date|after_or_equal:date_from',
        ]);

        $records = QueryBuilder::for(Attendance::class)
            ->allowedFilters(
                AllowedFilter::callback('course_id',  fn ($q, $v) => $q->whereHas('classSubject.subject', fn ($q) => $q->where('course_id', $v))),
                AllowedFilter::callback('class_id',   fn ($q, $v) => $q->whereHas('classSubject', fn ($q) => $q->where('school_class_id', $v))),
                AllowedFilter::callback('subject_id', fn ($q, $v) => $q->whereHas('classSubject', fn ($q) => $q->where('subject_id', $v))),
                AllowedFilter::exact('status'),
            )
            ->with(['student', 'classSubject.subject.course', 'classSubject.schoolClass'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();

        // Per-student summary
        $studentSummary = $records->groupBy('student_id')->map(function ($studentRecords) {
            $total = $studentRecords->count();
            return [
                'student' => $studentRecords->first()->student,
                'total'   => $total,
                'present' => $studentRecords->where('status', 'present')->count(),
                'absent'  => $studentRecords->where('status', 'absent')->count(),
                'late'    => $studentRecords->where('status', 'late')->count(),
                'excused' => $studentRecords->where('status', 'excused')->count(),
                'rate'    => $total > 0 ? round(($studentRecords->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
            ];
        })->sortByDesc('late')->values();

        // Overall summary
        $total   = $records->count();
        $overall = [
            'total'   => $total,
            'present' => $records->where('status', 'present')->count(),
            'absent'  => $records->where('status', 'absent')->count(),
            'late'    => $records->where('status', 'late')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'rate'    => $total > 0 ? round(($records->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
        ];

        $semester = $semesterId ? Semester::find($semesterId) : null;

        return Inertia::render('Reports/Show', [
            'studentSummary' => $studentSummary,
            'overall'        => $overall,
            'semester'       => $semester,
            'filters'        => $filters,
            'semesters'      => Semester::orderBy('start_date', 'desc')->get(),
            'courses'        => Course::all(['id', 'name', 'code']),
            'classes'        => SchoolClass::all(['id', 'name']),
            'subjects'       => Subject::all(['id', 'name', 'code']),
        ]);
    }
}
