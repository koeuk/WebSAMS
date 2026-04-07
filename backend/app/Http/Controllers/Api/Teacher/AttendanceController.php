<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSubject;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'date' => 'required|date',
            'attendances' => 'required|array|min:1',
            'attendances.*.student_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.remarks' => 'nullable|string',
        ]);

        $classSubject = ClassSubject::findOrFail($validated['class_subject_id']);

        if ($classSubject->teacher_id !== $request->user()->id) {
            abort(403, 'Unauthorized.');
        }

        foreach ($validated['attendances'] as $record) {
            Attendance::updateOrCreate(
                [
                    'class_subject_id' => $validated['class_subject_id'],
                    'student_id' => $record['student_id'],
                    'date' => $validated['date'],
                ],
                [
                    'status' => $record['status'],
                    'remarks' => $record['remarks'] ?? null,
                    'recorded_by' => $request->user()->id,
                ]
            );
        }

        return response()->json(['message' => 'Attendance recorded successfully.']);
    }

    public function index(Request $request)
    {
        $query = Attendance::with(['student', 'classSubject.subject', 'classSubject.schoolClass'])
            ->whereHas('classSubject', fn ($q) => $q->where('teacher_id', $request->user()->id));

        if ($request->filled('class_subject_id')) {
            $query->where('class_subject_id', $request->class_subject_id);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendance = $query->latest('date')->latest('id')->paginate(20);

        return response()->json($attendance);
    }
}
