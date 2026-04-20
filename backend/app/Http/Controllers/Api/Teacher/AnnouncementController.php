<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Announcement::with('schoolClass')
                ->where('author_id', $request->user()->id)
                ->latest()->paginate(20)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'audience'     => 'in:all,teachers,students',
            'class_id'     => 'nullable|exists:school_classes,id',
            'published_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create(array_merge($data, [
            'author_id'    => $request->user()->id,
            'published_at' => $data['published_at'] ?? now(),
        ]));

        return response()->json($announcement, 201);
    }

    public function update(Request $request, Announcement $announcement)
    {
        if ($announcement->author_id !== $request->user()->id) abort(403);

        $data = $request->validate([
            'title'    => 'sometimes|string|max:255',
            'body'     => 'sometimes|string',
            'audience' => 'sometimes|in:all,teachers,students',
            'class_id' => 'nullable|exists:school_classes,id',
        ]);

        $announcement->update($data);
        return response()->json($announcement);
    }

    public function destroy(Request $request, Announcement $announcement)
    {
        if ($announcement->author_id !== $request->user()->id) abort(403);
        $announcement->delete();
        return response()->json(['message' => 'Deleted.']);
    }
}
