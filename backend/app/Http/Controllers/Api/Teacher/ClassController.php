<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $classes = ClassSubject::with(['schoolClass', 'subject'])
            ->where('teacher_id', $request->user()->id)
            ->get();

        return response()->json($classes);
    }

    public function students(Request $request, ClassSubject $classSubject)
    {
        if ($classSubject->teacher_id !== $request->user()->id) {
            abort(403, 'Unauthorized.');
        }

        $students = $classSubject->schoolClass->students()->get(['users.id', 'name', 'email']);

        return response()->json($students);
    }
}
