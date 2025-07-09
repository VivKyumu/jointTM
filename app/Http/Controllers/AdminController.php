<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Status;
use App\Models\Task;
use App\Models\TaskComplexity;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'groups_count' => Group::withCount('users')->get()->sum('users_count'),
            'tasks_count' => Task::count(),
            'statuses_count' => Status::count(),
            'complexities_count' => TaskComplexity::count(),
        ];

        // Get recent users with their tasks count and groups
        $recentUsers = User::with(['groups', 'tasks'])
            ->withCount('tasks')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get recent tasks with all relationships
        $recentTasks = Task::with(['user.groups', 'status', 'complexity'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get paginated tasks with all relationships (5 per page)
        $tasks = Task::with(['user.groups', 'status', 'complexity'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Get all statuses, complexities, and groups for dropdowns
        $statuses = Status::all();
        $complexities = TaskComplexity::all();
        $groups = Group::withCount('users')->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentTasks',
            'tasks',
            'statuses',
            'complexities',
            'groups'
        ));
    }

    /**
     * Handle group updates for users
     */
    public function updateUserGroups(Request $request, User $user)
    {
        $request->validate([
            'group_ids' => 'nullable|array',
            'group_ids.*' => 'exists:groups,id'
        ]);

        $user->groups()->sync($request->input('group_ids', []));

        return response()->json([
            'success' => true,
            'message' => 'User groups updated successfully'
        ]);
    }

    /**
     * Display system information and settings.
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'items_per_page' => 'required|integer|min:5|max:100',
        ]);

        return redirect()->route('admin.settings')
                         ->with('success', 'Settings updated successfully');
    }
}

