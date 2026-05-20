<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::latest('start_date')->paginate($this->limit());

        return Inertia::render('Semesters/Index', ['semesters' => $semesters]);
    }

    public function create()
    {
        return Inertia::render('Semesters/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
        ]);

        DB::beginTransaction();
        try {
            Semester::create($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.semesters.index')->with('success', 'Semester created successfully.');
    }

    public function edit(Semester $semester)
    {
        return Inertia::render('Semesters/Edit', ['semester' => $semester]);
    }

    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
        ]);

        DB::beginTransaction();
        try {
            $semester->update($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.semesters.index')->with('success', 'Semester updated successfully.');
    }

    public function delete(Semester $semester)
    {
        return Inertia::render('Semesters/Delete', ['semester' => $semester]);
    }

    public function destroy(Semester $semester)
    {
        DB::beginTransaction();
        try {
            $semester->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.semesters.index')->with('success', 'Semester deleted successfully.');
    }
}
