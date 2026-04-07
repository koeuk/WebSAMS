<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolClass::withCount(['subjects', 'students']);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $classes = $query->latest()->paginate(15)->withQueryString();

        return Inertia::render('Classes/Index', [
            'classes' => $classes,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Classes/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'academic_year' => 'required|string|max:20',
        ]);

        SchoolClass::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    public function show(SchoolClass $class)
    {
        $class->load([
            'subjects.subject',
            'subjects.teacher',
            'students',
        ]);

        return Inertia::render('Classes/Show', [
            'schoolClass' => $class,
            'availableSubjects' => Subject::all(['id', 'name', 'code']),
            'availableTeachers' => User::where('role', 'teacher')->get(['id', 'name', 'email']),
            'availableStudents' => User::where('role', 'student')
                ->whereNotIn('id', $class->students->pluck('id'))
                ->get(['id', 'name', 'email']),
        ]);
    }

    public function edit(SchoolClass $class)
    {
        return Inertia::render('Classes/Edit', [
            'schoolClass' => $class,
        ]);
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'academic_year' => 'required|string|max:20',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }
}
