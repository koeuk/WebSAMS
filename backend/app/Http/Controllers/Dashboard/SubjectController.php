<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = QueryBuilder::for(Subject::class)
            ->allowedFilters(
                AllowedFilter::exact('course_id'),
                AllowedFilter::callback('search', fn ($q, $v) => $q->where(fn ($q) => $q
                    ->where('name', 'like', "%{$v}%")
                    ->orWhere('code', 'like', "%{$v}%")
                )),
            ])
            ->with('course')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Subjects/Index', [
            'subjects' => $subjects,
            'courses'  => Course::all(['id', 'name', 'code']),
            'filters'  => $request->input('filter', []),
        ]);
    }

    public function create()
    {
        return Inertia::render('Subjects/Create', [
            'courses' => Course::all(['id', 'name', 'code']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:20|unique:subjects,code',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            Subject::create($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return Inertia::render('Subjects/Edit', [
            'subject' => $subject,
            'courses' => Course::all(['id', 'name', 'code']),
        ]);
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'name'        => 'required|string|max:255',
            'code'        => ['required', 'string', 'max:20', Rule::unique('subjects', 'code')->ignore($subject->id)],
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $subject->update($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function delete(Subject $subject)
    {
        return Inertia::render('Subjects/Delete', ['subject' => $subject]);
    }

    public function destroy(Subject $subject)
    {
        DB::beginTransaction();
        try {
            $subject->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
