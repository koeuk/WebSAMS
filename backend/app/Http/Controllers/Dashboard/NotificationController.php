<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = QueryBuilder::for(Notification::class)
            ->allowedFilters(
                AllowedFilter::exact('type'),
                AllowedFilter::callback('search', fn ($q, $v) => $q->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$v}%"))),
            ])
            ->with('user')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications,
            'filters'       => $request->input('filter', []),
        ]);
    }
}
