<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\Schedule;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index()
    {
        $dayOrder = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        $schedules = Schedule::with(['classSubject.schoolClass', 'classSubject.subject', 'classSubject.teacher', 'timeSlot'])
            ->get()
            ->sortBy(fn ($s) => $s->timeSlot?->start_time)
            ->groupBy('day_of_week')
            ->sortBy(fn ($items, $key) => array_search($key, $dayOrder));

        return Inertia::render('Schedules/Index', [
            'schedules' => $schedules,
        ]);
    }

    public function create()
    {
        return Inertia::render('Schedules/Create', [
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'timeSlots' => TimeSlot::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'day_of_week' => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'room' => 'nullable|string|max:50',
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        return Inertia::render('Schedules/Edit', [
            'schedule' => $schedule->load(['classSubject', 'timeSlot']),
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'timeSlots' => TimeSlot::all(),
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'day_of_week' => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'room' => 'nullable|string|max:50',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
