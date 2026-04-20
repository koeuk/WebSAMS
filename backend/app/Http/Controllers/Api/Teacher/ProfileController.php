<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $teacher = $request->user()->load('teachingClasses.schoolClass', 'teachingClasses.subject');

        $classes = $teacher->teachingClasses->map(fn ($cs) => [
            'id'           => $cs->id,
            'class_name'   => $cs->schoolClass?->name,
            'subject_name' => $cs->subject?->name,
        ]);

        return response()->json(array_merge($teacher->toArray(), ['teaching_classes' => $classes]));
    }
}
