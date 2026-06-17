<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        return view('admin.users.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,manager,staff',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'is_admin' => $validated['role'] === 'admin',
        'is_active' => true,
    ]);
    ActivityLog::record('User Created', auth()->user()->name . " created user: {$user->name} ({$user->email}).");

    return redirect()->route('users.index')->with('success', 'User created successfully.');
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
        'email' => 'required|email|unique:users,email,' . $user->id,
        'is_admin' => 'nullable|boolean',
    ]);

    $oldName = $user->name;
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->is_admin = $request->has('is_admin') ? 1 : 0;
    $user->save();
    ActivityLog::record('User Updated', auth()->user()->name . " updated user: {$oldName}.");

    return redirect()->route('users.index')->with('success', 'User updated successfully.');

    if ($request->filled('password')) {
    $user->password = bcrypt($request->password);
}
}

    public function destroy(User $user)
    {
        ActivityLog::record('User Deleted', auth()->user()->name . " deleted user: {$user->name} ({$user->email}).");
        $user->groups()->detach();
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deactivated successfully');
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
    public function disable($id)
{
    $user = User::findOrFail($id);
    $user->is_active = false;
    $user->save();

    return redirect()->route('users.index')->with('success', 'User has been disabled successfully.');
}

public function enable($id)
{
    $user = User::findOrFail($id);
    $user->is_active = true;
    $user->save();

    return redirect()->route('users.index')->with('success', 'User has been re-enabled successfully.');
}

   
}
