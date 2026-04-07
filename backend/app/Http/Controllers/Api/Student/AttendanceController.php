<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['classSubject.subject', 'classSubject.schoolClass'])
            ->where('student_id', $request->user()->id);

        if ($request->filled('class_subject_id')) {
            $query->where('class_subject_id', $request->class_subject_id);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendance = $query->latest('date')->paginate(20);

        return response()->json($attendance);
    }
}
