<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\QrSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrSessionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'time_slot_id'     => 'nullable|exists:time_slots,id',
            'date'             => 'required|date',
            'duration_minutes' => 'integer|min:5|max:120',
        ]);

        $teacherId = $request->user()->id;
        $cs = ClassSubject::findOrFail($data['class_subject_id']);
        if ($cs->teacher_id !== $teacherId) abort(403);

        QrSession::where('class_subject_id', $cs->id)
            ->where('date', $data['date'])
            ->update(['is_active' => false]);

        $session = QrSession::create([
            'class_subject_id' => $cs->id,
            'teacher_id'       => $teacherId,
            'time_slot_id'     => $data['time_slot_id'] ?? null,
            'token'            => Str::random(48),
            'date'             => $data['date'],
            'expires_at'       => now()->addMinutes($data['duration_minutes'] ?? 15),
            'is_active'        => true,
        ]);

        return response()->json($session);
    }

    public function deactivate(Request $request, QrSession $qrSession)
    {
        if ($qrSession->teacher_id !== $request->user()->id) abort(403);
        $qrSession->update(['is_active' => false]);
        return response()->json(['message' => 'QR session deactivated.']);
    }
}
