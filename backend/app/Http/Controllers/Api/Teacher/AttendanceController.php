<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSubject;
use App\Models\Notification as AppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id'         => 'required|exists:class_subject,id',
            'date'                     => 'required|date',
            'time_slot_id'             => 'required|exists:time_slots,id',
            'attendances'              => 'required|array|min:1',
            'attendances.*.student_id' => 'required|exists:users,id',
            'attendances.*.status'     => 'required|in:present,absent,late,excused',
            'attendances.*.remarks'    => 'nullable|string',
            );

        $classSubject = ClassSubject::with(['subject', 'schoolClass'            )
            ->findOrFail($validated['class_subject_id'            );

        if ($classSubject->teacher_id !== $request->user()->id) {
            abort(403, 'Unauthorized.');
        }

        DB::beginTransaction();
        try {
            foreach ($validated['attendances'] as $record) {
                Attendance::updateOrCreate(
                    [
                        'class_subject_id' => $validated['class_subject_id'],
                        'student_id'       => $record['student_id'],
                        'date'             => $validated['date'],
                        'time_slot_id'     => $validated['time_slot_id'],
                    ],
                    [
                        'status'      => $record['status'],
                        'remarks'     => $record['remarks'] ?? null,
                        'recorded_by' => $request->user()->id,
                    ]
                );
            }

            $notifications = [];
            foreach ($validated['attendances'] as $record) {
                if (in_array($record['status'], ['absent', 'late'            )) {
                    $notifications[] = [
                        'user_id'    => $record['student_id'],
                        'title'      => ucfirst($record['status'            ) . ' Notice',
                        'message'    => "You were marked as {$record['status']} for {$classSubject->subject->name} ({$classSubject->schoolClass->name}) on {$validated['date']}.",
                        'type'       => $record['status'] === 'absent' ? 'absence' : 'late',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if ($notifications) {
                AppNotification::insert($notifications);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json(['message' => 'Attendance recorded successfully.'            );
    }

    public function index(Request $request)
    {
        $attendance = QueryBuilder::for(Attendance::class)
            ->allowedFilters(
                AllowedFilter::exact('class_subject_id'),
                AllowedFilter::callback('date_from', fn ($q, $v) => $q->where('date', '>=', $v)),
                AllowedFilter::callback('date_to',   fn ($q, $v) => $q->where('date', '<=', $v)),
            )
            ->allowedSorts(['date', 'id'            )
            ->with(['student', 'classSubject.subject', 'classSubject.schoolClass', 'timeSlot'            )
            ->whereHas('classSubject', fn ($q) => $q->where('teacher_id', $request->user()->id))
            ->latest('date')
            ->latest('id')
            ->paginate(20);

        return response()->json($attendance);
    }
}
