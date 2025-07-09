<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index()
    {
        $groups = Group::withCount('users')->latest()->paginate(10);
        return view('admin.groups.index', compact('groups'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.groups.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id'
        ]);

        $group = Group::create($validated);

        if (isset($validated['users'])) {
            $group->users()->sync($validated['users']);
        }

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group created successfully');
    }

    public function show(Group $group)
    {
        $availableUsers = User::whereDoesntHave('groups', function($query) use ($group) {
            $query->where('group_id', $group->id);
        })->get();

        return view('admin.groups.show', [
            'group' => $group->load('users'),
            'availableUsers' => $availableUsers
        ]);
    }

    public function edit(Group $group)
    {
        $users = User::all();
        return view('admin.groups.edit', [
            'group' => $group,
            'users' => $users
        ]);
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:groups,name,' . $group->id,
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id'
        ]);

        $group->update(['name' => $validated['name']]);
        $group->users()->sync($validated['users'] ?? []);

        return redirect()->route('admin.groups.index')
            ->with('success', 'Group updated successfully');
    }

    public function destroy(Group $group)
    {
        $group->users()->detach();
        $group->delete();
        
        return redirect()->route('admin.groups.index')
            ->with('success', 'Group deleted successfully');
    }

    public function addUser(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        $group->users()->syncWithoutDetaching([$request->user_id]);
        
        return back()->with('success', 'User added to group');
    }

    public function removeUser(Group $group, User $user)
    {
        $group->users()->detach($user->id);
        return back()->with('success', 'User removed from group');
    }

    public function syncUsers(Request $request, Group $group)
    {
        $validated = $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $group->users()->sync($validated['users']);

        return response()->json([
            'success' => true,
            'message' => 'Group users synchronized successfully'
        ]);
    }
}