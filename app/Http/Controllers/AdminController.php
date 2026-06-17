<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Status;
use App\Models\Task;
use App\Models\TaskComplexity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $dashboardFilter = $request->query('filter', 'all');
        $dashboardFilters = [
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'all' => 'All Time',
        ];
        $dashboardFilter = array_key_exists($dashboardFilter, $dashboardFilters) ? $dashboardFilter : 'all';
        $filterStart = null;
        $filterEnd = null;
        $dueColumn = Schema::hasColumn('tasks', 'due_date') ? 'due_date' : 'due_at';

        if ($dashboardFilter === 'today') {
            $filterStart = today();
        } elseif ($dashboardFilter === 'week') {
            $filterStart = now()->startOfWeek()->startOfDay();
            $filterEnd = now()->endOfWeek()->endOfDay();
        } elseif ($dashboardFilter === 'month') {
            $filterStart = now()->startOfMonth()->startOfDay();
            $filterEnd = now()->endOfMonth()->endOfDay();
        }

        $filteredTaskQuery = Task::with(['status', 'complexity']);

        if ($dashboardFilter === 'today') {
            $filteredTaskQuery->where(function ($query) use ($filterStart, $dueColumn) {
                $query->whereDate('created_at', $filterStart)
                    ->orWhereDate($dueColumn, $filterStart)
                    ->orWhereDate('updated_at', $filterStart);
            });
        } elseif ($filterStart && $filterEnd) {
            $filteredTaskQuery->where(function ($query) use ($filterStart, $filterEnd, $dueColumn) {
                $query->whereBetween('created_at', [$filterStart, $filterEnd])
                    ->orWhereBetween($dueColumn, [$filterStart, $filterEnd])
                    ->orWhereBetween('updated_at', [$filterStart, $filterEnd]);
            });
        }

        $allTasks = $filteredTaskQuery->get();
        $tasks = Task::with(['user.groups', 'status', 'complexity'])
            ->latest()
            ->paginate(8);

        $statusCount = function (string $status) use ($allTasks): int {
            return $allTasks
                ->filter(fn ($task) => strtolower($task->status_name) === strtolower($status))
                ->count();
        };

        $completedTasksCount = $statusCount('completed');
        $totalTasksCount = $allTasks->count();

        $stats = [
            'users_count' => User::count(),
            'admins_count' => User::where('is_admin', true)->count(),
            'active_users_count' => Schema::hasColumn('users', 'is_active')
                ? User::where('is_active', true)->count()
                : User::count(),
            'groups_count' => Group::count(),
            'tasks_count' => $totalTasksCount,
            'pending_tasks_count' => $statusCount('pending'),
            'in_progress_tasks_count' => $statusCount('in progress'),
            'completed_tasks_count' => $completedTasksCount,
            'overdue_tasks_count' => $allTasks
                ->filter(fn ($task) => $task->due_at && $task->due_at->lt(today()) && strtolower($task->status_name) !== 'completed')
                ->count(),
            'statuses_count' => Status::count(),
            'complexities_count' => TaskComplexity::count(),
        ];

        $completionRate = $totalTasksCount > 0
            ? round(($completedTasksCount / $totalTasksCount) * 100)
            : 0;

        $recentUsers = User::with(['groups', 'tasks'])
            ->withCount('tasks')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recentTasks = Task::with(['user.groups', 'status', 'complexity'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $statuses = Status::defaultOrder()->get();
        $complexities = TaskComplexity::byLevel()->get();
        $groups = Group::withCount('users')->get();

        $statusLabels = ['Pending', 'In Progress', 'Completed', 'On Hold'];
        $statusChartData = [
            $stats['pending_tasks_count'],
            $stats['in_progress_tasks_count'],
            $stats['completed_tasks_count'],
            $statusCount('on hold'),
        ];

        $complexityLabels = $complexities->pluck('name')->values();
        $complexityChartData = $complexities
            ->map(fn ($complexity) => $allTasks->where('complexity_id', $complexity->id)->count())
            ->values();

        $unassignedComplexityCount = $allTasks->whereNull('complexity_id')->count();

        if ($unassignedComplexityCount > 0) {
            $complexityLabels->push('Unassigned');
            $complexityChartData->push($unassignedComplexityCount);
        }

        $complexityChartColors = $complexityLabels
            ->map(fn ($label, $index) => [
                '#2563eb',
                '#0f766e',
                '#f59e0b',
                '#dc2626',
                '#64748b',
                '#7c3aed',
            ][$index % 6])
            ->values();

        $latestUpdatedTasks = Task::with(['user', 'complexity'])
            ->latest('updated_at')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentTasks',
            'tasks',
            'statuses',
            'complexities',
            'groups',
            'completionRate',
            'statusLabels',
            'statusChartData',
            'complexityLabels',
            'complexityChartData',
            'complexityChartColors',
            'latestUpdatedTasks',
            'dashboardFilter',
            'dashboardFilters'
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
        'default_user_role' => 'required|in:user,admin',
        'allow_registration' => 'required|boolean',
        'site_mode' => 'required|in:live,maintenance',
        'timezone' => 'required|timezone',
        'support_email' => 'nullable|email|max:255',
        'items_per_page' => 'required|integer|min:5|max:100',
    ]);

    // Simulate saving settings (replace with actual saving logic if using database or config helper)
    foreach ($validated as $key => $value) {
        // For example, save to cache or config file
        // Setting::set($key, $value); // if using a settings model
    }

    return redirect()->route('admin.settings')
                     ->with('success', 'Settings updated successfully');
}

}

 
