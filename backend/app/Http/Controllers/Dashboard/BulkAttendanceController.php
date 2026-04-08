<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\ClassSubject;
use App\Models\Notification as AppNotification;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BulkAttendanceController extends Controller
{
    public function create()
    {
        return Inertia::render('Attendance/BulkCreate', [
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'timeSlots' => TimeSlot::all(),
        ]);
    }

    public function loadStudents(Request $request)
    {
        $request->validate(['class_subject_id' => 'required|exists:class_subject,id']);

        $classSubject = ClassSubject::findOrFail($request->class_subject_id);
        $students = $classSubject->schoolClass->students()->get(['users.id', 'name', 'email']);

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'attendances' => 'required|array|min:1',
            'attendances.*.student_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.remarks' => 'nullable|string',
        ]);

        $classSubject = ClassSubject::with(['subject', 'schoolClass'])->findOrFail($validated['class_subject_id']);

        foreach ($validated['attendances'] as $record) {
            Attendance::updateOrCreate(
                [
                    'class_subject_id' => $validated['class_subject_id'],
                    'student_id' => $record['student_id'],
                    'date' => $validated['date'],
                    'time_slot_id' => $validated['time_slot_id'],
                ],
                [
                    'status' => $record['status'],
                    'remarks' => $record['remarks'] ?? null,
                    'recorded_by' => auth()->id(),
                ]
            );

            if (in_array($record['status'], ['absent', 'late'])) {
                AppNotification::create([
                    'user_id' => $record['student_id'],
                    'title' => ucfirst($record['status']) . ' Notice',
                    'message' => "You were marked as {$record['status']} for {$classSubject->subject->name} ({$classSubject->schoolClass->name}) on {$validated['date']}.",
                    'type' => $record['status'] === 'absent' ? 'absence' : 'late',
                ]);
            }
        }

        return redirect()->route('admin.attendance.index')->with('success', 'Bulk attendance recorded successfully.');
    }
}
