<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentTrackingController extends Controller
{
    public function index(Request $request)
    {
        $threshold = $request->input('threshold', 80);

        // Determine date range
        $dateFrom = null;
        $dateTo = null;

        if ($request->filled('semester_id')) {
            $semester = Semester::find($request->semester_id);
            if ($semester) {
                $dateFrom = $semester->start_date->toDateString();
                $dateTo = $semester->end_date->toDateString();
            }
        } elseif ($request->filled('academic_year')) {
            // Academic year format: "2025-2026"
            $years = explode('-', $request->academic_year);
            if (count($years) === 2) {
                $dateFrom = $years[0] . '-09-01';
                $dateTo = $years[1] . '-06-30';
            }
        }

        // Get all students
        $studentsQuery = User::where('role', 'student');

        if ($request->filled('class_id')) {
            $studentsQuery->whereHas('enrolledClasses', fn ($q) => $q->where('school_classes.id', $request->class_id));
        }

        $students = $studentsQuery->get();

        // Calculate attendance for each student
        $tracking = [];
        foreach ($students as $student) {
            $query = Attendance::where('student_id', $student->id);

            if ($dateFrom && $dateTo) {
                $query->whereBetween('date', [$dateFrom, $dateTo]);
            }

            if ($request->filled('course_id')) {
                $query->whereHas('classSubject.subject', fn ($q) => $q->where('course_id', $request->course_id));
            }

            $records = $query->get();
            $total = $records->count();

            if ($total === 0) continue;

            $present = $records->where('status', 'present')->count();
            $absent = $records->where('status', 'absent')->count();
            $late = $records->where('status', 'late')->count();
            $excused = $records->where('status', 'excused')->count();
            $rate = round(($records->whereIn('status', ['present', 'late'])->count() / $total) * 100);

            $tracking[] = [
                'student' => $student,
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'excused' => $excused,
                'rate' => $rate,
                'status' => $rate >= $threshold ? 'good' : ($rate >= 60 ? 'warning' : 'danger'),
            ];
        }

        // Sort by rate ascending (lowest attendance first)
        usort($tracking, fn ($a, $b) => $a['rate'] - $b['rate']);

        // Get unique academic years
        $academicYears = SchoolClass::distinct()->pluck('academic_year');

        return Inertia::render('StudentTracking/Index', [
            'tracking' => $tracking,
            'semesters' => Semester::orderBy('start_date', 'desc')->get(),
            'academicYears' => $academicYears,
            'courses' => Course::all(['id', 'name', 'code']),
            'classes' => SchoolClass::all(['id', 'name']),
            'filters' => $request->only(['semester_id', 'academic_year', 'course_id', 'class_id', 'threshold']),
            'threshold' => (int) $threshold,
            'summary' => [
                'totalStudents' => count($tracking),
                'lowAttendance' => count(array_filter($tracking, fn ($t) => $t['rate'] < $threshold)),
                'averageRate' => count($tracking) > 0 ? round(array_sum(array_column($tracking, 'rate')) / count($tracking)) : 0,
            ],
        ]);
    }

    public function show(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        // Get date range from filters
        $dateFrom = null;
        $dateTo = null;

        if ($request->filled('semester_id')) {
            $semester = Semester::find($request->semester_id);
            if ($semester) {
                $dateFrom = $semester->start_date->toDateString();
                $dateTo = $semester->end_date->toDateString();
            }
        }

        // Attendance by subject
        $query = Attendance::with(['classSubject.subject.course', 'classSubject.schoolClass'])
            ->where('student_id', $student->id);

        if ($dateFrom && $dateTo) {
            $query->whereBetween('date', [$dateFrom, $dateTo]);
        }

        $records = $query->latest('date')->get();

        // Group by subject
        $bySubject = $records->groupBy(fn ($r) => $r->classSubject?->subject?->id)->map(function ($subjectRecords) {
            $total = $subjectRecords->count();
            $subject = $subjectRecords->first()->classSubject?->subject;
            $schoolClass = $subjectRecords->first()->classSubject?->schoolClass;
            return [
                'subject' => $subject?->name,
                'course' => $subject?->course?->name,
                'class' => $schoolClass?->name,
                'total' => $total,
                'present' => $subjectRecords->where('status', 'present')->count(),
                'absent' => $subjectRecords->where('status', 'absent')->count(),
                'late' => $subjectRecords->where('status', 'late')->count(),
                'excused' => $subjectRecords->where('status', 'excused')->count(),
                'rate' => $total > 0 ? round(($subjectRecords->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
            ];
        })->values();

        // Overall
        $total = $records->count();
        $overall = [
            'total' => $total,
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'rate' => $total > 0 ? round(($records->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
        ];

        // Recent records
        $recentRecords = $records->take(20)->map(fn ($r) => [
            'id' => $r->id,
            'date' => $r->date,
            'subject' => $r->classSubject?->subject?->name,
            'class' => $r->classSubject?->schoolClass?->name,
            'course' => $r->classSubject?->subject?->course?->name,
            'status' => $r->status,
            'remarks' => $r->remarks,
        ]);

        $semester = $request->filled('semester_id') ? Semester::find($request->semester_id) : null;

        return Inertia::render('StudentTracking/Show', [
            'student' => $student->load('enrolledClasses'),
            'bySubject' => $bySubject,
            'overall' => $overall,
            'recentRecords' => $recentRecords,
            'semester' => $semester,
            'semesters' => Semester::orderBy('start_date', 'desc')->get(),
            'filters' => $request->only(['semester_id']),
        ]);
    }
}
