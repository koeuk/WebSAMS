<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ExcuseRequest;
use Illuminate\Http\Request;

class ExcuseRequestController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = $request->user()->id;

        $query = ExcuseRequest::with(['student', 'attendance.classSubject.schoolClass', 'attendance.classSubject.subject'])
            ->whereHas('attendance.classSubject', fn ($q) => $q->where('teacher_id', $teacherId));

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function review(Request $request, ExcuseRequest $excuseRequest)
    {
        $teacherId = $request->user()->id;
        $excuseRequest->load('attendance.classSubject');

        if ($excuseRequest->attendance->classSubject->teacher_id !== $teacherId) {
            abort(403);
        }

        $data = $request->validate([
            'status'        => 'required|in:approved,rejected',
            'reviewer_note' => 'nullable|string|max:500',
        ]);

        $excuseRequest->update([
            'status'        => $data['status'],
            'reviewer_note' => $data['reviewer_note'] ?? null,
            'reviewed_by'   => $teacherId,
            'reviewed_at'   => now(),
        ]);

        if ($data['status'] === 'approved') {
            $excuseRequest->attendance->update(['status' => 'excused']);
        }

        return response()->json(['message' => 'Excuse request reviewed.', 'request' => $excuseRequest]);
    }
}
