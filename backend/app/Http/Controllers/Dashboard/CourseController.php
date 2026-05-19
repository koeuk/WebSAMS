<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = QueryBuilder::for(Course::class)
            ->withCount('subjects')
            ->allowedFilters(
                AllowedFilter::callback('search', fn ($q, $v) => $q->where(fn ($q) => $q
                    ->where('name', 'like', "%{$v}%")
                    ->orWhere('code', 'like', "%{$v}%")
                )),
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Courses/Index', [
            'courses' => $courses,
            'filters' => $request->input('filter', []),
        ]);
    }

    public function create()
    {
        return Inertia::render('Courses/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:20|unique:courses,code',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            Course::create($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        return Inertia::render('Courses/Edit', ['course' => $course]);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => ['required', 'string', 'max:20', Rule::unique('courses', 'code')->ignore($course->id)],
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $course->update($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function delete(Course $course)
    {
        return Inertia::render('Courses/Delete', ['course' => $course]);
    }

    public function destroy(Course $course)
    {
        DB::beginTransaction();
        try {
            $course->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
