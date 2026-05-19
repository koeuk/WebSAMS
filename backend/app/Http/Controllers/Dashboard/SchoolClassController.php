<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $classes = QueryBuilder::for(SchoolClass::class)
            ->withCount(['subjects', 'students'            )
            ->allowedFilters(
                AllowedFilter::callback('search', fn ($q, $v) => $q->where('name', 'like', "%{$v}%")),
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Classes/Index', [
            'classes' => $classes,
            'filters' => $request->input('filter', [            ),
            );
    }

    public function create()
    {
        return Inertia::render('Classes/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'section'       => 'nullable|string|max:50',
            'academic_year' => 'required|string|max:20',
            );

        DB::beginTransaction();
        try {
            SchoolClass::create($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    public function show(SchoolClass $class)
    {
        $class->load(['subjects.subject', 'subjects.teacher', 'students'            );

        return Inertia::render('Classes/Show', [
            'schoolClass'       => $class,
            'availableSubjects' => Subject::all(['id', 'name', 'code'            ),
            'availableTeachers' => User::where('role', 'teacher')->get(['id', 'name', 'email'            ),
            'availableStudents' => User::where('role', 'student')
                ->whereNotIn('id', $class->students->pluck('id'))
                ->get(['id', 'name', 'email'            ),
            );
    }

    public function edit(SchoolClass $class)
    {
        return Inertia::render('Classes/Edit', ['schoolClass' => $class            );
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'section'       => 'nullable|string|max:50',
            'academic_year' => 'required|string|max:20',
            );

        DB::beginTransaction();
        try {
            $class->update($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function delete(SchoolClass $class)
    {
        return Inertia::render('Classes/Delete', ['schoolClass' => $class            );
    }

    public function destroy(SchoolClass $class)
    {
        DB::beginTransaction();
        try {
            $class->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }
}
