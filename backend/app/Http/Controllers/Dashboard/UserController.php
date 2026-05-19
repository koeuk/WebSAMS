<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\Gender;
use App\Enums\UserStatus;
use App\Enums\YearLevel;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters(
                AllowedFilter::exact('role'),
                AllowedFilter::callback('search', fn ($q, $v) => $q->where(fn ($q) => $q
                    ->where('name', 'like', "%{$v}%")
                    ->orWhere('email', 'like', "%{$v}%")
                    ->orWhere('id_number', 'like', "%{$v}%")
                )),
                AllowedFilter::exact('year_level'),
                AllowedFilter::exact('status'),
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users'       => $users,
            'filters'     => $request->input('filter', []),
            'roles'       => ['admin', 'teacher', 'student'],
            'genders'     => Gender::cases(),
            'statuses'    => UserStatus::cases(),
            'yearLevels'  => YearLevel::options(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create', [
            'roles'      => ['admin', 'teacher', 'student'],
            'genders'    => Gender::cases(),
            'statuses'   => UserStatus::cases(),
            'yearLevels' => YearLevel::options(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6',
            'role'            => 'required|in:admin,teacher,student',
            'phone'           => 'nullable|string|max:20',
            'year_level'      => 'nullable|integer|min:1|max:4',
            'id_number'       => 'nullable|string|max:50|unique:users,id_number',
            'gender'          => ['nullable', Rule::enum(Gender::class)],
            'date_of_birth'   => 'nullable|date',
            'address'         => 'nullable|string',
            'status'          => ['nullable', Rule::enum(UserStatus::class)],
            'guardian_name'   => 'nullable|string|max:255',
            'guardian_phone'  => 'nullable|string|max:20',
            'enrollment_date' => 'nullable|date',
            'department'      => 'nullable|string|max:255',
            'qualification'   => 'nullable|string|max:255',
            'hire_date'       => 'nullable|date',
            'profile_photo'   => 'nullable|image|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        DB::beginTransaction();
        try {
            User::create($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return Inertia::render('Users/Show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user'       => $user,
            'roles'      => ['admin', 'teacher', 'student'],
            'genders'    => Gender::cases(),
            'statuses'   => UserStatus::cases(),
            'yearLevels' => YearLevel::options(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'        => 'nullable|string|min:6',
            'role'            => 'required|in:admin,teacher,student',
            'phone'           => 'nullable|string|max:20',
            'year_level'      => 'nullable|integer|min:1|max:4',
            'id_number'       => ['nullable', 'string', 'max:50', Rule::unique('users', 'id_number')->ignore($user->id)],
            'gender'          => ['nullable', Rule::enum(Gender::class)],
            'date_of_birth'   => 'nullable|date',
            'address'         => 'nullable|string',
            'status'          => ['nullable', Rule::enum(UserStatus::class)],
            'guardian_name'   => 'nullable|string|max:255',
            'guardian_phone'  => 'nullable|string|max:20',
            'enrollment_date' => 'nullable|date',
            'department'      => 'nullable|string|max:255',
            'qualification'   => 'nullable|string|max:255',
            'hire_date'       => 'nullable|date',
            'profile_photo'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        } else {
            unset($validated['profile_photo']);
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        DB::beginTransaction();
        try {
            $user->update($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function delete(User $user)
    {
        return Inertia::render('Users/Delete', ['user' => $user]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
