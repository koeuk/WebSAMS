<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\AttendanceStatus;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSubject;
use App\Models\Course;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendance = QueryBuilder::for(Attendance::class)
            ->allowedFilters(
                AllowedFilter::callback('course_id',  fn ($q, $v) => $q->whereHas('classSubject.subject', fn ($q) => $q->where('course_id', $v))),
                AllowedFilter::callback('class_id',   fn ($q, $v) => $q->whereHas('classSubject', fn ($q) => $q->where('school_class_id', $v))),
                AllowedFilter::callback('subject_id', fn ($q, $v) => $q->whereHas('classSubject', fn ($q) => $q->where('subject_id', $v))),
                AllowedFilter::exact('status'),
                AllowedFilter::callback('date_from',  fn ($q, $v) => $q->where('date', '>=', $v)),
                AllowedFilter::callback('date_to',    fn ($q, $v) => $q->where('date', '<=', $v)),
            )
            ->with(['student', 'classSubject.subject.course', 'classSubject.schoolClass', 'recorder', 'timeSlot'])
            ->latest('date')
            ->latest('id')
            ->paginate($this->limit())
            ->withQueryString();

        $courseId = $request->input('filter.course_id');
        $subjectsQuery = Subject::query();
        if ($courseId) {
            $subjectsQuery->where('course_id', $courseId);
        }

        return Inertia::render('Attendance/Index', [
            'attendance'    => $attendance,
            'courses'       => Course::all(['id', 'name', 'code']),
            'classes'       => SchoolClass::all(['id', 'name']),
            'subjects'      => $subjectsQuery->get(['id', 'name', 'code']),
            'timeSlots'     => TimeSlot::all(),
            'statuses'      => AttendanceStatus::options(),
            'filters'       => $request->input('filter', []),
            // Dropdown data for the inline create/edit modal (AttendanceForm)
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'students'      => User::where('role', 'student')->get(['id', 'name', 'email']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Attendance/Create', [
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'students'      => User::where('role', 'student')->get(['id', 'name', 'email']),
            'timeSlots'     => TimeSlot::all(),
            'statuses'      => AttendanceStatus::options(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string',
        ]);

        $validated['recorded_by'] = auth()->id();

        Attendance::updateOrCreate(
            [
                'class_subject_id' => $validated['class_subject_id'],
                'student_id' => $validated['student_id'],
                'date' => $validated['date'],
                'time_slot_id' => $validated['time_slot_id'],
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
        $attendance->load(['student', 'classSubject.subject.course', 'classSubject.schoolClass', 'recorder', 'timeSlot']);

        return Inertia::render('Attendance/Show', [
            'record' => $attendance,
        ]);
    }

    public function edit(Attendance $attendance)
    {
        $attendance->load(['student', 'classSubject.subject', 'classSubject.schoolClass', 'timeSlot']);

        return Inertia::render('Attendance/Edit', [
            'record'        => $attendance,
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'students'      => User::where('role', 'student')->get(['id', 'name', 'email']),
            'timeSlots'     => TimeSlot::all(),
            'statuses'      => AttendanceStatus::options(),
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
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
