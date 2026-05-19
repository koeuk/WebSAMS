<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendance = QueryBuilder::for(Attendance::class)
            ->allowedFilters(
                AllowedFilter::exact('class_subject_id'),
                AllowedFilter::callback('date_from', fn ($q, $v) => $q->where('date', '>=', $v)),
                AllowedFilter::callback('date_to',   fn ($q, $v) => $q->where('date', '<=', $v)),
            )
            ->with(['classSubject.subject', 'classSubject.schoolClass', 'timeSlot'])
            ->where('student_id', $request->user()->id)
            ->latest('date')
            ->paginate(20);

        return response()->json($attendance);
    }
}
