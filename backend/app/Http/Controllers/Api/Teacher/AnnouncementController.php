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
            'title'        => 'required|array',
            'title.en'     => 'required|string|max:255',
            'title.km'     => 'nullable|string|max:255',
            'title.zh'     => 'nullable|string|max:255',
            'body'         => 'required|array',
            'body.en'      => 'required|string',
            'body.km'      => 'nullable|string',
            'body.zh'      => 'nullable|string',
            'audience'     => 'in:all,teachers,students',
            'class_id'     => 'nullable|exists:school_classes,id',
            'published_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create(array_merge($data, [
            'author_id'    => $request->user()->id,
            'published_at' => $data['published_at'] ?? now(),
        ]));

        return response()->json($this->withTranslations($announcement), 201);
    }

    public function update(Request $request, Announcement $announcement)
    {
        if ($announcement->author_id !== $request->user()->id) abort(403);

        $data = $request->validate([
            'title'    => 'sometimes|array',
            'title.en' => 'sometimes|string|max:255',
            'title.km' => 'nullable|string|max:255',
            'title.zh' => 'nullable|string|max:255',
            'body'     => 'sometimes|array',
            'body.en'  => 'sometimes|string',
            'body.km'  => 'nullable|string',
            'body.zh'  => 'nullable|string',
            'audience' => 'sometimes|in:all,teachers,students',
            'class_id' => 'nullable|exists:school_classes,id',
        ]);

        $announcement->update($data);
        return response()->json($this->withTranslations($announcement->refresh()));
    }

    private function withTranslations(Announcement $a): array
    {
        return array_merge($a->toArray(), [
            'title_translations' => $a->getTranslations('title'),
            'body_translations'  => $a->getTranslations('body'),
        ]);
    }

    public function destroy(Request $request, Announcement $announcement)
    {
        if ($announcement->author_id !== $request->user()->id) abort(403);
        $announcement->delete();
        return response()->json(['message' => 'Deleted.']);
    }
}
