<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\Schedule;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'schedules'     => $schedules,
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'timeSlots'     => TimeSlot::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Schedules/Create', [
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'timeSlots'     => TimeSlot::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'time_slot_id'     => 'required|exists:time_slots,id',
            'day_of_week'      => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'room'             => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            Schedule::create($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        return Inertia::render('Schedules/Edit', [
            'schedule'      => $schedule->load(['classSubject', 'timeSlot']),
            'classSubjects' => ClassSubject::with(['schoolClass', 'subject', 'teacher'])->get(),
            'timeSlots'     => TimeSlot::all(),
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'class_subject_id' => 'required|exists:class_subject,id',
            'time_slot_id'     => 'required|exists:time_slots,id',
            'day_of_week'      => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'room'             => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            $schedule->update($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function delete(Schedule $schedule)
    {
        return Inertia::render('Schedules/Delete', [
            'schedule' => $schedule->load(['classSubject.schoolClass', 'classSubject.subject', 'timeSlot']),
        ]);
    }

    public function destroy(Schedule $schedule)
    {
        DB::beginTransaction();
        try {
            $schedule->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
