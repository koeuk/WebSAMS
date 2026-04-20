<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\QrSession;
use Illuminate\Http\Request;

class QrAttendController extends Controller
{
    public function attend(Request $request)
    {
        $data = $request->validate(['token' => 'required|string']);

        $session = QrSession::where('token', $data['token'])
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $student = $request->user();

        $enrolled = ClassStudent::where('school_class_id', $session->classSubject?->school_class_id)
            ->where('student_id', $student->id)
            ->exists();

        if (!$enrolled) {
            return response()->json(['message' => 'You are not enrolled in this class.'], 403);
        }

        Attendance::updateOrCreate(
            [
                'class_subject_id' => $session->class_subject_id,
                'student_id'       => $student->id,
                'date'             => $session->date->toDateString(),
            ],
            [
                'status'       => 'present',
                'time_slot_id' => $session->time_slot_id,
                'recorded_by'  => $session->teacher_id,
                'remarks'      => 'QR attendance',
            ]
        );

        return response()->json(['message' => 'Attendance recorded successfully.']);
    }
}
