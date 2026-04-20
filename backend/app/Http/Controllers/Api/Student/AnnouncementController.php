<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user();
        $classIds = $student->enrolledClasses()->pluck('school_classes.id');

        return response()->json(
            Announcement::with('author', 'schoolClass')
                ->where(fn ($q) => $q
                    ->whereIn('audience', ['all', 'students'])
                    ->where(fn ($q2) => $q2
                        ->whereNull('class_id')
                        ->orWhereIn('class_id', $classIds)
                    )
                )
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->paginate(20)
        );
    }
}
