<?php

namespace App\Http\Controllers;

use App\Exports\AnalyticsReportExport;
use App\Models\Task;
use App\Models\TaskComplexity;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class AnalyticsExportController extends Controller
{
    public function pdf()
    {
        $analytics = $this->analyticsData();

        return Pdf::loadView('admin.exports.analytics-report', compact('analytics'))
            ->setPaper('a4', 'portrait')
            ->download('task-manager-analytics-report.pdf');
    }

    public function excel()
    {
        return Excel::download(
            new AnalyticsReportExport($this->analyticsData()),
            'task-manager-analytics-report.csv',
            ExcelFormat::CSV
        );
    }

    private function analyticsData(): array
    {
        $today = today();
        $totalTasks = Task::count();
        $completedTasks = Task::whereRaw('LOWER(status) = ?', ['completed'])->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        $overdueTasks = Task::whereNotNull('due_at')
            ->whereDate('due_at', '<', $today)
            ->whereRaw('LOWER(status) != ?', ['completed'])
            ->count();

        $statusLabels = ['Pending', 'In Progress', 'Completed', 'On Hold'];
        $statusDistribution = collect($statusLabels)->mapWithKeys(fn ($status) => [
            $status => Task::whereRaw('LOWER(status) = ?', [strtolower($status)])->count(),
        ]);

        $complexityLabels = ['Very Simple', 'Simple', 'Medium', 'Complex', 'Very Complex'];
        $complexities = TaskComplexity::whereIn('name', $complexityLabels)->get()->keyBy('name');
        $complexityDistribution = collect($complexityLabels)->mapWithKeys(function ($label) use ($complexities) {
            $complexity = $complexities->get($label);

            return [$label => $complexity ? Task::where('complexity_id', $complexity->id)->count() : 0];
        });

        $mostProductiveUsers = User::query()
            ->withCount([
                'tasks as completed_tasks_count' => fn ($query) => $query->whereRaw('LOWER(status) = ?', ['completed']),
                'tasks as total_tasks_count',
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

        $averageCompletionTimes = User::query()
            ->select('users.id', 'users.name', 'users.email')
            ->selectRaw('COUNT(tasks.id) as completed_tasks_count')
            ->selectRaw('ROUND(AVG(TIMESTAMPDIFF(HOUR, tasks.created_at, COALESCE(tasks.completed_at, tasks.updated_at))) / 24, 1) as average_completion_days')
            ->join('tasks', 'tasks.user_id', '=', 'users.id')
            ->whereRaw('LOWER(tasks.status) = ?', ['completed'])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('average_completion_days')
            ->get();

        $highRiskTasks = Task::with(['user', 'complexity'])
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
            })
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
                'tasks as total_tasks_count',
                'tasks as completed_tasks_count' => fn ($query) => $query->whereRaw('LOWER(status) = ?', ['completed']),
                'tasks as overdue_tasks_count' => fn ($query) => $query
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

        return [
            'generatedAt' => Carbon::now(),
            'totalTasks' => $totalTasks,
            'completionRate' => $completionRate,
            'completedTasks' => $completedTasks,
            'overdueTasks' => $overdueTasks,
            'activeUsers' => User::whereHas('tasks')->count(),
            'statusDistribution' => $statusDistribution,
            'complexityDistribution' => $complexityDistribution,
            'mostProductiveUsers' => $mostProductiveUsers,
            'averageCompletionTimes' => $averageCompletionTimes,
            'highRiskTasks' => $highRiskTasks,
            'userPerformanceScores' => $userPerformanceScores,
        ];
    }
}
