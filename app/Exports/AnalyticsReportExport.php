<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class AnalyticsReportExport implements FromArray
{
    public function __construct(private array $analytics)
    {
    }

    public function array(): array
    {
        $rows = [
            ['Task Manager System - Analytics Report'],
            ['Generated At', $this->analytics['generatedAt']->format('M d, Y h:i A')],
            [],
            ['KPI Summary'],
            ['Total Tasks', $this->analytics['totalTasks']],
            ['Completion Rate', $this->analytics['completionRate'] . '%'],
            ['Overdue Tasks', $this->analytics['overdueTasks']],
            ['Active Users', $this->analytics['activeUsers']],
            [],
            ['Task Status Distribution'],
            ['Status', 'Count'],
        ];

        foreach ($this->analytics['statusDistribution'] as $status => $count) {
            $rows[] = [$status, $count];
        }

        $rows[] = [];
        $rows[] = ['Task Complexity Distribution'];
        $rows[] = ['Complexity', 'Count'];

        foreach ($this->analytics['complexityDistribution'] as $complexity => $count) {
            $rows[] = [$complexity, $count];
        }

        $rows[] = [];
        $rows[] = ['Top 5 Most Productive Users'];
        $rows[] = ['Name', 'Tasks Completed', 'Completion Rate'];

        foreach ($this->analytics['mostProductiveUsers'] as $user) {
            $rows[] = [$user->name, $user->completed_tasks_count, $user->completion_rate . '%'];
        }

        $rows[] = [];
        $rows[] = ['User Performance Leaderboard'];
        $rows[] = ['Name', 'Score', 'Completed', 'Overdue'];

        foreach ($this->analytics['userPerformanceScores'] as $user) {
            $rows[] = [$user->name, $user->performance_score, $user->completed_tasks_count, $user->overdue_tasks_count];
        }

        $rows[] = [];
        $rows[] = ['Average Task Completion Time'];
        $rows[] = ['User', 'Email', 'Completed Tasks', 'Average Days'];

        foreach ($this->analytics['averageCompletionTimes'] as $user) {
            $rows[] = [$user->name, $user->email, $user->completed_tasks_count, $user->average_completion_days ?? 0];
        }

        $rows[] = [];
        $rows[] = ['High Risk Tasks'];
        $rows[] = ['Task', 'Assigned User', 'Due Date', 'Complexity', 'Risk Reason'];

        foreach ($this->analytics['highRiskTasks'] as $task) {
            $rows[] = [
                $task->title,
                $task->user->name ?? 'Unassigned',
                $task->due_at ? $task->due_at->format('M d, Y') : 'No due date',
                $task->complexity->name ?? 'Unassigned',
                $task->risk_reason,
            ];
        }

        return $rows;
    }
}
