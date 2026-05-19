<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'name'       => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'type'       => 'required|in:morning,afternoon,evening',
        ]);

        DB::beginTransaction();
        try {
            TimeSlot::create($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.time-slots.index')->with('success', 'Time slot created.');
    }

    public function edit(TimeSlot $timeSlot)
    {
        return Inertia::render('TimeSlots/Edit', ['timeSlot' => $timeSlot]);
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'type'       => 'required|in:morning,afternoon,evening',
        ]);

        DB::beginTransaction();
        try {
            $timeSlot->update($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.time-slots.index')->with('success', 'Time slot updated.');
    }

    public function delete(TimeSlot $timeSlot)
    {
        return Inertia::render('TimeSlots/Delete', ['timeSlot' => $timeSlot]);
    }

    public function destroy(TimeSlot $timeSlot)
    {
        DB::beginTransaction();
        try {
            $timeSlot->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.time-slots.index')->with('success', 'Time slot deleted.');
    }
}
