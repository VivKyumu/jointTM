<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Manager System - Analytics Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-top: 18px; margin-bottom: 6px; color: #1f2937; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        .muted { color: #6b7280; margin-bottom: 12px; }
    </style>
</head>
<body>
    <h1>Task Manager System - Analytics Report</h1>
    <div class="muted">Generated at {{ $analytics['generatedAt']->format('M d, Y h:i A') }}</div>

    <h2>KPI Summary</h2>
    <table>
        <tr><th>Total Tasks</th><td>{{ $analytics['totalTasks'] }}</td></tr>
        <tr><th>Completion Rate</th><td>{{ $analytics['completionRate'] }}%</td></tr>
        <tr><th>Overdue Tasks</th><td>{{ $analytics['overdueTasks'] }}</td></tr>
        <tr><th>Active Users</th><td>{{ $analytics['activeUsers'] }}</td></tr>
    </table>

    <h2>Task Status Distribution</h2>
    <table>
        <tr><th>Status</th><th>Count</th></tr>
        @foreach($analytics['statusDistribution'] as $status => $count)
            <tr><td>{{ $status }}</td><td>{{ $count }}</td></tr>
        @endforeach
    </table>

    <h2>Task Complexity Distribution</h2>
    <table>
        <tr><th>Complexity</th><th>Count</th></tr>
        @foreach($analytics['complexityDistribution'] as $complexity => $count)
            <tr><td>{{ $complexity }}</td><td>{{ $count }}</td></tr>
        @endforeach
    </table>

    <h2>Top 5 Most Productive Users</h2>
    <table>
        <tr><th>Name</th><th>Tasks Completed</th><th>Completion Rate</th></tr>
        @foreach($analytics['mostProductiveUsers'] as $user)
            <tr><td>{{ $user->name }}</td><td>{{ $user->completed_tasks_count }}</td><td>{{ $user->completion_rate }}%</td></tr>
        @endforeach
    </table>

    <h2>User Performance Leaderboard</h2>
    <table>
        <tr><th>Name</th><th>Score</th><th>Completed</th><th>Overdue</th></tr>
        @foreach($analytics['userPerformanceScores'] as $user)
            <tr><td>{{ $user->name }}</td><td>{{ $user->performance_score }}</td><td>{{ $user->completed_tasks_count }}</td><td>{{ $user->overdue_tasks_count }}</td></tr>
        @endforeach
    </table>

    <h2>Average Task Completion Time Per User</h2>
    <table>
        <tr><th>User</th><th>Email</th><th>Completed Tasks</th><th>Average Days</th></tr>
        @foreach($analytics['averageCompletionTimes'] as $user)
            <tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->completed_tasks_count }}</td><td>{{ $user->average_completion_days ?? 0 }}</td></tr>
        @endforeach
    </table>

    <h2>High Risk Tasks</h2>
    <table>
        <tr><th>Task</th><th>Assigned User</th><th>Due Date</th><th>Complexity</th><th>Risk Reason</th></tr>
        @forelse($analytics['highRiskTasks'] as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->user->name ?? 'Unassigned' }}</td>
                <td>{{ $task->due_at ? $task->due_at->format('M d, Y') : 'No due date' }}</td>
                <td>{{ $task->complexity->name ?? 'Unassigned' }}</td>
                <td>{{ $task->risk_reason }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No high risk tasks detected.</td></tr>
        @endforelse
    </table>
</body>
</html>
