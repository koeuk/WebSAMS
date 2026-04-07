<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['student', 'classSubject.subject.course', 'classSubject.schoolClass', 'recorder']);

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

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendance = $query->latest('date')->latest('id')->paginate(20)->withQueryString();

        // Filter subjects by selected course
        $subjectsQuery = Subject::query();
        if ($request->filled('course_id')) {
            $subjectsQuery->where('course_id', $request->course_id);
        }

        return Inertia::render('Attendance/Index', [
            'attendance' => $attendance,
            'courses' => Course::all(['id', 'name', 'code']),
            'classes' => SchoolClass::all(['id', 'name']),
            'subjects' => $subjectsQuery->get(['id', 'name', 'code']),
            'filters' => $request->only(['course_id', 'class_id', 'subject_id', 'status', 'date_from', 'date_to']),
        ]);
    }
}
