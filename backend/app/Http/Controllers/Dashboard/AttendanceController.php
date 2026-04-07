<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSubject;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
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

    public function create()
    {
        return Inertia::render('Attendance/Create', [
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'students' => User::where('role', 'student')->get(['id', 'name', 'email']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string',
        ]);

        $validated['recorded_by'] = auth()->id();

        Attendance::updateOrCreate(
            [
                'class_subject_id' => $validated['class_subject_id'],
                'student_id' => $validated['student_id'],
                'date' => $validated['date'],
            ],
            [
                'status' => $validated['status'],
                'remarks' => $validated['remarks'],
                'recorded_by' => $validated['recorded_by'],
            ]
        );

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance recorded successfully.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['student', 'classSubject.subject.course', 'classSubject.schoolClass', 'recorder']);

        return Inertia::render('Attendance/Show', [
            'record' => $attendance,
        ]);
    }

    public function edit(Attendance $attendance)
    {
        $attendance->load(['student', 'classSubject.subject', 'classSubject.schoolClass']);

        return Inertia::render('Attendance/Edit', [
            'record' => $attendance,
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'students' => User::where('role', 'student')->get(['id', 'name', 'email']),
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string',
        ]);

        $validated['recorded_by'] = auth()->id();
        $attendance->update($validated);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance deleted successfully.');
    }
}
