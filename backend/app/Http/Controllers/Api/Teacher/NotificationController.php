<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Notification::where('user_id', $request->user()->id)->latest()->paginate(20)
        );
    }

    public function markRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) abort(403);
        $notification->update(['is_read' => true]);
        return response()->json(['message' => 'Marked as read.']);
    }

    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)->update(['is_read' => true]);
        return response()->json(['message' => 'All notifications marked as read.']);
    }
}
