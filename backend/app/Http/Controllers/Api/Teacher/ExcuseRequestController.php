<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ExcuseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ExcuseRequestController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = $request->user()->id;

        $excuseRequests = QueryBuilder::for(ExcuseRequest::class)
            ->allowedFilters(
                AllowedFilter::exact('status'),
            )
            ->with(['student', 'attendance.classSubject.schoolClass', 'attendance.classSubject.subject'])
            ->whereHas('attendance.classSubject', fn ($q) => $q->where('teacher_id', $teacherId))
            ->latest()
            ->paginate(20);

        return response()->json($excuseRequests);
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

        DB::beginTransaction();
        try {
            $excuseRequest->update([
                'status'        => $data['status'],
                'reviewer_note' => $data['reviewer_note'] ?? null,
                'reviewed_by'   => $teacherId,
                'reviewed_at'   => now(),
            ]);

            if ($data['status'] === 'approved') {
                $excuseRequest->attendance->update(['status' => 'excused']);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json(['message' => 'Excuse request reviewed.', 'request' => $excuseRequest]);
    }
}
