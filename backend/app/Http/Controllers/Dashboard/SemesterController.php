<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::latest('start_date')->paginate(15);

        return Inertia::render('Semesters/Index', [
            'semesters' => $semesters,
        ]);
    }

    public function create()
    {
        return Inertia::render('Semesters/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Semester::create($validated);

        return redirect()->route('admin.semesters.index')->with('success', 'Semester created successfully.');
    }

    public function edit(Semester $semester)
    {
        return Inertia::render('Semesters/Edit', [
            'semester' => $semester,
        ]);
    }

    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $semester->update($validated);

        return redirect()->route('admin.semesters.index')->with('success', 'Semester updated successfully.');
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();

        return redirect()->route('admin.semesters.index')->with('success', 'Semester deleted successfully.');
    }
}
