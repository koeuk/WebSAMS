<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClassStudent;
use Illuminate\Http\Request;

class ClassStudentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'student_id' => 'required|exists:users,id',
        ]);

        ClassStudent::create($validated);

        return back()->with('success', 'Student enrolled successfully.');
    }

    public function destroy(ClassStudent $classStudent)
    {
        $classStudent->delete();

        return back()->with('success', 'Student removed from class.');
    }
}
