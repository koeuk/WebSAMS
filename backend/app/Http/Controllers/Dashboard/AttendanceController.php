<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['student', 'classSubject.subject', 'classSubject.schoolClass', 'recorder']);

        if ($request->filled('class_id')) {
            $query->whereHas('classSubject', fn ($q) => $q->where('school_class_id', $request->class_id));
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('classSubject', fn ($q) => $q->where('subject_id', $request->subject_id));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendance = $query->latest('date')->latest('id')->paginate(20)->withQueryString();

        return Inertia::render('Attendance/Index', [
            'attendance' => $attendance,
            'classes' => SchoolClass::all(['id', 'name']),
            'subjects' => Subject::all(['id', 'name']),
            'filters' => $request->only(['class_id', 'subject_id', 'status', 'date_from', 'date_to']),
        ]);
    }
}
