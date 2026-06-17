<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComplexity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $today = today();
        $range = $request->query('range', 'all');
        $fromDate = $request->filled('from') ? Carbon::parse($request->query('from'))->startOfDay() : null;
        $toDate = $request->filled('to') ? Carbon::parse($request->query('to'))->endOfDay() : null;

        if (!$request->filled('from') && !$request->filled('to')) {
            $fromDate = match ($range) {
                'last7' => now()->subDays(6)->startOfDay(),
                'last30' => now()->subDays(29)->startOfDay(),
                'last3months' => now()->subMonths(3)->startOfDay(),
                default => null,
            };
            $toDate = $range === 'all' ? null : now()->endOfDay();
        }

        $applyDateFilter = function ($query) use ($fromDate, $toDate) {
            return $query
                ->when($fromDate, fn ($query) => $query->whereDate('tasks.created_at', '>=', $fromDate))
                ->when($toDate, fn ($query) => $query->whereDate('tasks.created_at', '<=', $toDate));
        };

        $filteredTasks = fn () => $applyDateFilter(Task::query());
        $filterLabel = $fromDate || $toDate
            ? ($fromDate?->format('d M Y') ?? 'Beginning') . ' - ' . ($toDate?->format('d M Y') ?? 'Today')
            : 'All Time';

        $totalTasks = $filteredTasks()->count();
        $completedTasks = $filteredTasks()->whereRaw('LOWER(status) = ?', ['completed'])->count();
        $completionRate = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100, 1)
            : 0;

        $overdueTasks = $filteredTasks()->whereNotNull('due_at')
            ->whereDate('due_at', '<', $today)
            ->whereRaw('LOWER(status) != ?', ['completed'])
            ->count();

        $statusLabels = ['Pending', 'In Progress', 'Completed', 'On Hold'];
        $statusDistribution = collect($statusLabels)->mapWithKeys(function ($status) use ($filteredTasks) {
            return [
                $status => $filteredTasks()->whereRaw('LOWER(status) = ?', [strtolower($status)])->count(),
            ];
        });

        $complexityLabels = ['Very Simple', 'Simple', 'Medium', 'Complex', 'Very Complex'];
        $complexities = TaskComplexity::whereIn('name', $complexityLabels)->get()->keyBy('name');
        $complexityDistribution = collect($complexityLabels)->mapWithKeys(function ($label) use ($complexities, $filteredTasks) {
            $complexity = $complexities->get($label);

            return [
                $label => $complexity
                    ? $filteredTasks()->where('complexity_id', $complexity->id)->count()
                    : 0,
            ];
        });

        $mostProductiveUsers = User::query()
            ->withCount([
                'tasks as completed_tasks_count' => fn ($query) => $applyDateFilter($query)->whereRaw('LOWER(status) = ?', ['completed']),
                'tasks as total_tasks_count' => fn ($query) => $applyDateFilter($query),
            ])
            ->orderByDesc('completed_tasks_count')
            ->take(5)
            ->get()
            ->map(function ($user) {
                $user->completion_rate = $user->total_tasks_count > 0
                    ? round(($user->completed_tasks_count / $user->total_tasks_count) * 100, 1)
                    : 0;

                return $user;
            });

        $workloadPerUser = User::query()
            ->withCount(['tasks as tasks_count' => fn ($query) => $applyDateFilter($query)])
            ->having('tasks_count', '>', 0)
            ->orderByDesc('tasks_count')
            ->get();

        $weekStart = Carbon::now()->startOfWeek()->subWeeks(7);
        $completedTasksByWeek = Task::query()
            ->selectRaw('YEARWEEK(COALESCE(completed_at, updated_at), 1) as week_key, COUNT(*) as total')
            ->whereRaw('LOWER(status) = ?', ['completed'])
            ->where('updated_at', '>=', $weekStart)
            ->when($fromDate, fn ($query) => $query->whereDate('tasks.created_at', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('tasks.created_at', '<=', $toDate))
            ->groupBy('week_key')
            ->pluck('total', 'week_key');

        $tasksCompletedPerWeek = collect(range(7, 0))->map(function ($weeksAgo) use ($completedTasksByWeek) {
            $week = Carbon::now()->startOfWeek()->subWeeks($weeksAgo);
            $weekKey = (int) $week->format('oW');

            return [
                'label' => $week->format('M d'),
                'count' => (int) ($completedTasksByWeek[$weekKey] ?? 0),
            ];
        });

        $pendingCompletedOverdue = [
            'pending' => $filteredTasks()->whereRaw('LOWER(status) = ?', ['pending'])->count(),
            'completed' => $completedTasks,
            'overdue' => $overdueTasks,
        ];

        $averageCompletionTimes = User::query()
            ->select('users.id', 'users.name', 'users.email')
            ->selectRaw('COUNT(tasks.id) as completed_tasks_count')
            ->selectRaw('ROUND(AVG(TIMESTAMPDIFF(HOUR, tasks.created_at, COALESCE(tasks.completed_at, tasks.updated_at))) / 24, 1) as average_completion_days')
            ->join('tasks', 'tasks.user_id', '=', 'users.id')
            ->whereRaw('LOWER(tasks.status) = ?', ['completed'])
            ->when($fromDate, fn ($query) => $query->whereDate('tasks.created_at', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('tasks.created_at', '<=', $toDate))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('average_completion_days')
            ->get();

        $complexityCompletionRaw = TaskComplexity::query()
            ->select('task_complexities.name')
            ->selectRaw('ROUND(AVG(TIMESTAMPDIFF(HOUR, tasks.created_at, COALESCE(tasks.completed_at, tasks.updated_at))) / 24, 1) as average_days')
            ->join('tasks', 'tasks.complexity_id', '=', 'task_complexities.id')
            ->whereRaw('LOWER(tasks.status) = ?', ['completed'])
            ->when($fromDate, fn ($query) => $query->whereDate('tasks.created_at', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('tasks.created_at', '<=', $toDate))
            ->groupBy('task_complexities.id', 'task_complexities.name', 'task_complexities.level')
            ->orderBy('task_complexities.level')
            ->pluck('average_days', 'name');

        $complexityCompletionTime = collect($complexityLabels)->mapWithKeys(fn ($label) => [
            $label => (float) ($complexityCompletionRaw[$label] ?? 0),
        ]);

        $simpleAverage = $complexityCompletionTime['Simple'] ?? 0;
        $complexAverage = $complexityCompletionTime['Complex'] ?? 0;
        $complexityCompletionInsight = 'Task completion time appears consistent across complexity levels.';

        if ($complexAverage > 0 && $simpleAverage > 0 && $complexAverage > $simpleAverage) {
            $difference = round($complexAverage - $simpleAverage, 1);
            $complexityCompletionInsight = "Complex tasks take on average {$difference} days longer to complete than Simple tasks.";
        } elseif ($complexAverage > 0 && $simpleAverage > 0 && $simpleAverage > $complexAverage) {
            $difference = round($simpleAverage - $complexAverage, 1);
            $complexityCompletionInsight = "Simple tasks currently take on average {$difference} days longer to complete than Complex tasks.";
        } elseif ($complexityCompletionTime->sum() === 0) {
            $complexityCompletionInsight = 'No completed task timing data is available yet for complexity comparison.';
        }

        $forecastStart = $today->copy()->startOfDay();
        $forecastEnd = $today->copy()->addDays(6)->endOfDay();
        $upcomingTasksByDate = Task::query()
            ->selectRaw('DATE(due_at) as due_date, COUNT(*) as total')
            ->whereNotNull('due_at')
            ->whereBetween('due_at', [$forecastStart, $forecastEnd])
            ->whereRaw('LOWER(status) != ?', ['completed'])
            ->groupBy(DB::raw('DATE(due_at)'))
            ->pluck('total', 'due_date');

        $upcomingWorkloadForecast = collect(range(0, 6))->map(function ($dayOffset) use ($today, $upcomingTasksByDate) {
            $date = $today->copy()->addDays($dayOffset);

            return [
                'label' => $date->format('D d'),
                'count' => (int) ($upcomingTasksByDate[$date->toDateString()] ?? 0),
            ];
        });

        $highWorkloadDays = $upcomingWorkloadForecast->where('count', '>=', 5)->count();
        $forecastSummary = $highWorkloadDays > 0
            ? "{$highWorkloadDays} high workload day" . ($highWorkloadDays === 1 ? '' : 's') . " detected in the next 7 days. Consider redistributing tasks."
            : 'Workload looks manageable for the next 7 days.';

        $highRiskTasksQuery = Task::with(['user', 'complexity'])
            ->where(function ($query) use ($today) {
                $query->where(function ($query) use ($today) {
                    $query->whereNotNull('due_at')
                        ->whereDate('due_at', '>=', $today)
                        ->whereDate('due_at', '<=', $today->copy()->addDays(2))
                        ->whereRaw('LOWER(status) != ?', ['completed']);
                })->orWhere(function ($query) use ($today) {
                    $query->whereNotNull('due_at')
                        ->whereDate('due_at', '<', $today)
                        ->whereRaw('LOWER(status) != ?', ['completed']);
                })->orWhere(function ($query) {
                    $query->whereRaw('LOWER(status) = ?', ['pending'])
                        ->whereHas('complexity', function ($query) {
                            $query->whereIn(DB::raw('LOWER(name)'), ['complex', 'very complex']);
                        });
                });
            });

        $applyDateFilter($highRiskTasksQuery);

        $highRiskTasks = $highRiskTasksQuery
            ->orderBy('due_at')
            ->take(15)
            ->get()
            ->map(function ($task) use ($today) {
                $status = strtolower($task->status ?? '');
                $complexity = strtolower($task->complexity?->name ?? '');
                $reasons = [];

                if ($task->due_at && $task->due_at->lt($today) && $status !== 'completed') {
                    $reasons[] = 'Overdue';
                }

                if ($task->due_at && $task->due_at->betweenIncluded($today, $today->copy()->addDays(2)->endOfDay()) && $status !== 'completed') {
                    $reasons[] = 'Due within 2 days';
                }

                if ($status === 'pending' && in_array($complexity, ['complex', 'very complex'], true)) {
                    $reasons[] = 'High complexity still pending';
                }

                $task->risk_reason = implode(', ', $reasons);

                return $task;
            });

        $userPerformanceScores = User::query()
            ->withCount([
                'tasks as total_tasks_count' => fn ($query) => $applyDateFilter($query),
                'tasks as completed_tasks_count' => fn ($query) => $applyDateFilter($query)->whereRaw('LOWER(status) = ?', ['completed']),
                'tasks as overdue_tasks_count' => fn ($query) => $query
                    ->when($fromDate, fn ($query) => $query->whereDate('tasks.created_at', '>=', $fromDate))
                    ->when($toDate, fn ($query) => $query->whereDate('tasks.created_at', '<=', $toDate))
                    ->whereNotNull('due_at')
                    ->whereDate('due_at', '<', $today)
                    ->whereRaw('LOWER(status) != ?', ['completed']),
            ])
            ->get()
            ->map(function ($user) {
                $completionPercent = $user->total_tasks_count > 0
                    ? ($user->completed_tasks_count / $user->total_tasks_count) * 100
                    : 0;

                $user->performance_score = round($completionPercent - ($user->overdue_tasks_count * 5), 1);

                return $user;
            })
            ->sortByDesc('performance_score')
            ->values();

        $workloadUsers = User::query()
            ->withCount(['tasks as tasks_count' => fn ($query) => $applyDateFilter($query)])
            ->having('tasks_count', '>', 0)
            ->orderByDesc('tasks_count')
            ->get();

        $maxWorkloadUser = $workloadUsers->first();
        $minWorkloadUser = $workloadUsers->last();
        $workloadImbalance = null;

        if ($maxWorkloadUser && $minWorkloadUser && $minWorkloadUser->tasks_count > 0 && $maxWorkloadUser->tasks_count > ($minWorkloadUser->tasks_count * 3)) {
            $workloadImbalance = [
                'overloaded_user' => $maxWorkloadUser,
                'underloaded_user' => $minWorkloadUser,
                'message' => "{$maxWorkloadUser->name} has more than 3 times the tasks assigned to {$minWorkloadUser->name}.",
            ];
        }

        $analytics = [
            'totalTasks' => $totalTasks,
            'completionRate' => $completionRate,
            'completedTasks' => $completedTasks,
            'overdueTasks' => $overdueTasks,
            'activeUsers' => User::whereHas('tasks', fn ($query) => $applyDateFilter($query))->count(),
            'filterLabel' => $filterLabel,
            'filterRange' => $range,
            'filterFrom' => $fromDate?->format('Y-m-d'),
            'filterTo' => $toDate?->format('Y-m-d'),
            'statusDistribution' => $statusDistribution,
            'complexityDistribution' => $complexityDistribution,
            'mostProductiveUsers' => $mostProductiveUsers,
            'workloadPerUser' => $workloadPerUser,
            'tasksCompletedPerWeek' => $tasksCompletedPerWeek,
            'pendingCompletedOverdue' => $pendingCompletedOverdue,
            'averageCompletionTimes' => $averageCompletionTimes,
            'complexityCompletionTime' => $complexityCompletionTime,
            'complexityCompletionInsight' => $complexityCompletionInsight,
            'upcomingWorkloadForecast' => $upcomingWorkloadForecast,
            'forecastSummary' => $forecastSummary,
            'highRiskTasks' => $highRiskTasks,
            'userPerformanceScores' => $userPerformanceScores,
            'workloadImbalance' => $workloadImbalance,
        ];

        return view('admin.analytics', compact('analytics'));
    }
}
