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
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StudentTrackingController extends Controller
{
    public function index(Request $request)
    {
        $filters   = $request->input('filter', []);
        $threshold = $filters['threshold'] ?? 80;

        $dateFrom = null;
        $dateTo   = null;

        if (!empty($filters['semester_id'])) {
            $semester = Semester::find($filters['semester_id']);
            if ($semester) {
                $dateFrom = $semester->start_date->toDateString();
                $dateTo   = $semester->end_date->toDateString();
            }
        } elseif (!empty($filters['academic_year'])) {
            $years = explode('-', $filters['academic_year']);
            if (count($years) === 2) {
                $dateFrom = $years[0] . '-09-01';
                $dateTo   = $years[1] . '-06-30';
            }
        }

        $students = QueryBuilder::for(User::class)
            ->where('role', 'student')
            ->allowedFilters(
                AllowedFilter::callback('class_id', fn ($q, $v) => $q->whereHas('enrolledClasses', fn ($q) => $q->where('school_classes.id', $v))),
                AllowedFilter::exact('year_level'),
            )
            ->get();

        $courseId = $filters['course_id'] ?? null;
        $tracking = [];

        foreach ($students as $student) {
            $query = Attendance::where('student_id', $student->id);

            if ($dateFrom && $dateTo) {
                $query->whereBetween('date', [$dateFrom, $dateTo]);
            }

            if ($courseId) {
                $query->whereHas('classSubject.subject', fn ($q) => $q->where('course_id', $courseId));
            }

            $records = $query->get();
            $total   = $records->count();

            if ($total === 0) continue;

            $present = $records->where('status', 'present')->count();
            $absent  = $records->where('status', 'absent')->count();
            $late    = $records->where('status', 'late')->count();
            $excused = $records->where('status', 'excused')->count();
            $rate    = round(($records->whereIn('status', ['present', 'late'])->count() / $total) * 100);

            $tracking[] = [
                'student' => $student,
                'total'   => $total,
                'present' => $present,
                'absent'  => $absent,
                'late'    => $late,
                'excused' => $excused,
                'rate'    => $rate,
                'status'  => $rate >= $threshold ? 'good' : ($rate >= 60 ? 'warning' : 'danger'),
            ];
        }

        usort($tracking, fn ($a, $b) => $a['rate'] - $b['rate']);

        $academicYears = SchoolClass::distinct()->pluck('academic_year');

        return Inertia::render('StudentTracking/Index', [
            'tracking'      => $tracking,
            'semesters'     => Semester::orderBy('start_date', 'desc')->get(),
            'academicYears' => $academicYears,
            'courses'       => Course::all(['id', 'name', 'code']),
            'classes'       => SchoolClass::all(['id', 'name']),
            'filters'       => $filters,
            'threshold'     => (int) $threshold,
            'summary'       => [
                'totalStudents' => count($tracking),
                'lowAttendance' => count(array_filter($tracking, fn ($t) => $t['rate'] < $threshold)),
                'averageRate'   => count($tracking) > 0 ? round(array_sum(array_column($tracking, 'rate')) / count($tracking)) : 0,
            ],
        ]);
    }

    public function show(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $filters    = $request->input('filter', []);
        $semesterId = $filters['semester_id'] ?? null;
        $dateFrom   = null;
        $dateTo     = null;

        if ($semesterId) {
            $semester = Semester::find($semesterId);
            if ($semester) {
                $dateFrom = $semester->start_date->toDateString();
                $dateTo   = $semester->end_date->toDateString();
            }
        }

        $query = Attendance::with(['classSubject.subject.course', 'classSubject.schoolClass'])
            ->where('student_id', $student->id);

        if ($dateFrom && $dateTo) {
            $query->whereBetween('date', [$dateFrom, $dateTo]);
        }

        $records = $query->latest('date')->get();

        $bySubject = $records->groupBy(fn ($r) => $r->classSubject?->subject?->id)->map(function ($subjectRecords) {
            $total       = $subjectRecords->count();
            $subject     = $subjectRecords->first()->classSubject?->subject;
            $schoolClass = $subjectRecords->first()->classSubject?->schoolClass;
            return [
                'subject' => $subject?->name,
                'course'  => $subject?->course?->name,
                'class'   => $schoolClass?->name,
                'total'   => $total,
                'present' => $subjectRecords->where('status', 'present')->count(),
                'absent'  => $subjectRecords->where('status', 'absent')->count(),
                'late'    => $subjectRecords->where('status', 'late')->count(),
                'excused' => $subjectRecords->where('status', 'excused')->count(),
                'rate'    => $total > 0 ? round(($subjectRecords->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
            ];
        })->values();

        $total   = $records->count();
        $overall = [
            'total'   => $total,
            'present' => $records->where('status', 'present')->count(),
            'absent'  => $records->where('status', 'absent')->count(),
            'late'    => $records->where('status', 'late')->count(),
            'excused' => $records->where('status', 'excused')->count(),
            'rate'    => $total > 0 ? round(($records->whereIn('status', ['present', 'late'])->count() / $total) * 100) : 0,
        ];

        $allSemesters  = Semester::all();
        $recentRecords = $records->take(20)->map(function ($r) use ($student, $allSemesters) {
            $recordDate      = $r->date;
            $matchedSemester = $allSemesters->first(fn ($s) => $recordDate >= $s->start_date && $recordDate <= $s->end_date);

            return [
                'id'            => $r->id,
                'date'          => $r->date,
                'student_name'  => $student->name,
                'subject'       => $r->classSubject?->subject?->name,
                'class'         => $r->classSubject?->schoolClass?->name,
                'academic_year' => $r->classSubject?->schoolClass?->academic_year,
                'course'        => $r->classSubject?->subject?->course?->name,
                'semester'      => $matchedSemester?->name,
                'status'        => $r->status,
                'remarks'       => $r->remarks,
            ];
        });

        $semester = $semesterId ? Semester::find($semesterId) : null;

        return Inertia::render('StudentTracking/Show', [
            'student'       => $student->load('enrolledClasses'),
            'bySubject'     => $bySubject,
            'overall'       => $overall,
            'recentRecords' => $recentRecords,
            'semester'      => $semester,
            'semesters'     => Semester::orderBy('start_date', 'desc')->get(),
            'filters'       => $filters,
        ]);
    }
}
