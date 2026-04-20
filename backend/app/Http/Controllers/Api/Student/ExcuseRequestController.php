<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ExcuseRequest;
use Illuminate\Http\Request;

class ExcuseRequestController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            ExcuseRequest::with(['attendance.classSubject.schoolClass', 'attendance.classSubject.subject'])
                ->where('student_id', $request->user()->id)
                ->latest()->paginate(20)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'reason'        => 'required|string|max:1000',
        ]);

        $attendance = Attendance::findOrFail($data['attendance_id']);

        if ($attendance->student_id !== $request->user()->id) abort(403);

        if (ExcuseRequest::where('attendance_id', $attendance->id)->exists()) {
            return response()->json(['message' => 'An excuse request already exists for this attendance record.'], 422);
        }

        $excuse = ExcuseRequest::create([
            'student_id'    => $request->user()->id,
            'attendance_id' => $attendance->id,
            'reason'        => $data['reason'],
        ]);

        return response()->json($excuse, 201);
    }
}
