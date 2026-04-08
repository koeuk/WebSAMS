<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with('user')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        return Inertia::render('Notifications/Index', [
            'notifications' => $query->paginate(20)->withQueryString(),
            'filters' => $request->only(['type', 'search']),
        ]);
    }
}
