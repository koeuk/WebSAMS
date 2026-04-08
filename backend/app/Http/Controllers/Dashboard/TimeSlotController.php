<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimeSlotController extends Controller
{
    public function index()
    {
        return Inertia::render('TimeSlots/Index', [
            'timeSlots' => TimeSlot::orderBy('start_time')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('TimeSlots/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:morning,evening,afternoon',
        ]);

        TimeSlot::create($validated);

        return redirect()->route('admin.time-slots.index')->with('success', 'Time slot created.');
    }

    public function edit(TimeSlot $timeSlot)
    {
        return Inertia::render('TimeSlots/Edit', ['timeSlot' => $timeSlot]);
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:morning,evening,afternoon',
        ]);

        $timeSlot->update($validated);

        return redirect()->route('admin.time-slots.index')->with('success', 'Time slot updated.');
    }

    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();
        return redirect()->route('admin.time-slots.index')->with('success', 'Time slot deleted.');
    }
}
