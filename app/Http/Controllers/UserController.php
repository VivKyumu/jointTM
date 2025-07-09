<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index()
    {
        $users = User::with('groups')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('admin.users.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'is_admin' => 'boolean',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'is_admin' => $request->has('is_admin')
        ]);

        if (isset($validated['groups'])) {
            $user->groups()->sync($validated['groups']);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        return view('admin.users.show', ['user' => $user->load('groups')]);
    }

    public function edit(User $user)
    {
        $groups = Group::all();
        return view('admin.users.edit', [
            'user' => $user,
            'groups' => $groups
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'is_admin' => 'boolean',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id'
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_admin' => $request->has('is_admin')
        ]);

        $user->groups()->sync($validated['groups'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->groups()->detach();
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deactivated successfully');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'is_admin' => 'required|boolean'
        ]);

        $user->update(['is_admin' => $request->is_admin]);

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully'
        ]);
    }

    public function updateGroups(Request $request, User $user)
    {
        $request->validate([
            'group_ids' => 'nullable|array',
            'group_ids.*' => 'exists:groups,id'
        ]);

        $user->groups()->sync($request->group_ids ?? []);

        return response()->json([
            'success' => true,
            'message' => 'User groups updated successfully'
        ]);
    }

    public function syncGroups(Request $request, User $user)
    {
        $validated = $request->validate([
            'groups' => 'required|array',
            'groups.*' => 'exists:groups,id'
        ]);

        $user->groups()->sync($validated['groups']);

        return response()->json([
            'success' => true,
            'message' => 'User groups synchronized successfully'
        ]);
    }
}