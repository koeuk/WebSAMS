<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index()
    {
        $dayOrder = ['mon', 'tue', 'wed', 'thu', 'fri'];
        $schedules = Schedule::with(['classSubject.schoolClass', 'classSubject.subject', 'classSubject.teacher'])
            ->orderBy('start_time')
            ->get()
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
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'day_of_week' => 'required|in:mon,tue,wed,thu,fri',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:50',
        ]);

        Schedule::create($validated);

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        return Inertia::render('Schedules/Edit', [
            'schedule' => $schedule->load('classSubject'),
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'day_of_week' => 'required|in:mon,tue,wed,thu,fri',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
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
