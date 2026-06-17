@extends('layouts.partials')

@section('title', 'Analytics Dashboard')
@section('header', 'Analytics Dashboard')

@section('content')
@php
    $hasStatusData = collect($analytics['statusDistribution'] ?? [])->sum() > 0;
    $hasWeeklyCompletionData = collect($analytics['tasksCompletedPerWeek'] ?? [])->sum('count') > 0;
    $hasWorkloadData = collect($analytics['workloadPerUser'] ?? [])->sum('tasks_count') > 0;
    $hasComplexityCompletionData = collect($analytics['complexityCompletionTime'] ?? [])->sum() > 0;
    $hasForecastData = collect($analytics['upcomingWorkloadForecast'] ?? [])->sum('count') > 0;
    $statusChartLabels = collect($analytics['statusDistribution'] ?? [])->keys()->values();
    $statusChartValues = collect($analytics['statusDistribution'] ?? [])->values()->map(fn ($count) => (int) $count);
    $statusTotal = max(collect($analytics['statusDistribution'] ?? [])->sum(), 1);
    $statusColors = [
        'Pending' => 'status-pending',
        'In Progress' => 'status-in-progress',
        'Completed' => 'status-completed',
        'On Hold' => 'status-on-hold',
    ];
    $weeklyChartLabels = collect($analytics['tasksCompletedPerWeek'] ?? [])->pluck('label')->values();
    $weeklyChartValues = collect($analytics['tasksCompletedPerWeek'] ?? [])->pluck('count')->map(fn ($count) => (int) $count)->values();
    $workloadChartLabels = collect($analytics['workloadPerUser'] ?? [])->pluck('name')->values();
    $workloadChartValues = collect($analytics['workloadPerUser'] ?? [])->pluck('tasks_count')->map(fn ($count) => (int) $count)->values();
    $workloadChartHeight = max(400, collect($analytics['workloadPerUser'] ?? [])->count() * 30);
    $complexityCompletionLabels = collect($analytics['complexityCompletionTime'] ?? [])->keys()->values();
    $complexityCompletionValues = collect($analytics['complexityCompletionTime'] ?? [])->values()->map(fn ($days) => (float) $days);
    $forecastChartLabels = collect($analytics['upcomingWorkloadForecast'] ?? [])->pluck('label')->values();
    $forecastChartValues = collect($analytics['upcomingWorkloadForecast'] ?? [])->pluck('count')->map(fn ($count) => (int) $count)->values();
@endphp
<div class="container-fluid py-3 analytics-dashboard">
    <div class="card card-outline card-primary mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.analytics') }}" class="row align-items-end">
                <input type="hidden" name="range" value="custom">
                <div class="col-lg-2 col-md-4 mb-2">
                    <label class="small text-muted mb-1">From</label>
                    <input type="date" name="from" value="{{ $analytics['filterFrom'] }}" class="form-control form-control-sm">
                </div>
                <div class="col-lg-2 col-md-4 mb-2">
                    <label class="small text-muted mb-1">To</label>
                    <input type="date" name="to" value="{{ $analytics['filterTo'] }}" class="form-control form-control-sm">
                </div>
                <div class="col-lg-4 col-md-12 mb-2">
                    <label class="small text-muted mb-1 d-block">Quick filters</label>
                    <div class="btn-group btn-group-sm flex-wrap" role="group">
                        <a href="{{ route('admin.analytics', ['range' => 'last7']) }}"
                           class="btn btn-outline-primary {{ $analytics['filterRange'] === 'last7' ? 'active' : '' }}">Last 7 Days</a>
                        <a href="{{ route('admin.analytics', ['range' => 'last30']) }}"
                           class="btn btn-outline-primary {{ $analytics['filterRange'] === 'last30' ? 'active' : '' }}">Last 30 Days</a>
                        <a href="{{ route('admin.analytics', ['range' => 'last3months']) }}"
                           class="btn btn-outline-primary {{ $analytics['filterRange'] === 'last3months' ? 'active' : '' }}">Last 3 Months</a>
                        <a href="{{ route('admin.analytics', ['range' => 'all']) }}"
                           class="btn btn-outline-primary {{ $analytics['filterRange'] === 'all' ? 'active' : '' }}">All Time</a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-2">
                    <button type="submit" class="btn btn-sm btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i> Apply Filter
                    </button>
                </div>
                <div class="col-lg-2 col-md-8 mb-2 text-lg-right">
                    <a href="{{ route('admin.analytics.export.pdf', request()->query()) }}" class="btn btn-sm btn-danger mb-1">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </a>
                    <a href="{{ route('admin.analytics.export.excel', request()->query()) }}" class="btn btn-sm btn-success mb-1">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </a>
                </div>
            </form>
            <div class="small text-muted mt-2">
                <i class="fas fa-calendar-alt mr-1"></i>
                Showing analytics for: <strong>{{ $analytics['filterLabel'] }}</strong>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($analytics['totalTasks']) }}</h3>
                    <p>Total Tasks</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $analytics['completionRate'] }}<sup style="font-size: 20px">%</sup></h3>
                    <p>Completion Rate</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($analytics['overdueTasks']) }}</h3>
                    <p>Overdue Tasks</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($analytics['activeUsers']) }}</h3>
                    <p>Active Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Task Status Distribution</h3>
                </div>
                <div class="card-body workload-chart-body p-2">
                    @unless($hasStatusData)
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <p class="mb-0">No data available for this chart.</p>
                        </div>
                    @endunless
                    <div class="status-distribution-list">
                        @foreach($analytics['statusDistribution'] as $status => $count)
                            @php($percent = round(($count / $statusTotal) * 100))
                            <div class="status-row mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="font-weight-bold">{{ $status }}</span>
                                    <span class="badge badge-{{ $statusColors[$status] ?? 'status-pending' }}">{{ $count }} tasks</span>
                                </div>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-{{ $statusColors[$status] ?? 'status-pending' }}"
                                         style="width: {{ $percent }}%"></div>
                                </div>
                                <small class="text-muted">{{ $percent }}% of all tasks</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-week mr-2"></i>Tasks Completed Per Week</h3>
                </div>
                <div class="card-body chart-box">
                    @unless($hasWeeklyCompletionData)
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <p class="mb-0">No data available for this chart.</p>
                        </div>
                    @endunless
                    <div class="chart-loader" data-chart-loader="completedPerWeekChart">
                        <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <canvas id="completedPerWeekChart" style="display: none;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-check mr-2"></i>Workload Per User</h3>
                </div>
                <div class="card-body chart-box">
                    @unless($hasWorkloadData)
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <p class="mb-0">No data available for this chart.</p>
                        </div>
                    @endunless
                    <div class="chart-loader" data-chart-loader="workloadChart">
                        <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <div class="workload-chart-scroll">
                        <div style="position: relative; height: {{ $workloadChartHeight }}px;">
                            <canvas id="workloadChart" style="display: none;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline card-indigo">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Complexity vs Completion Time</h3>
                </div>
                <div class="card-body">
                    <div class="chart-box">
                        @unless($hasComplexityCompletionData)
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <p class="mb-0">No data available for this chart.</p>
                            </div>
                        @endunless
                        <div class="chart-loader" data-chart-loader="complexityCompletionChart">
                            <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <canvas id="complexityCompletionChart" style="display: none;"></canvas>
                    </div>
                    <div class="alert alert-info mb-0 mt-3">
                        <i class="fas fa-lightbulb mr-1"></i>
                        {{ $analytics['complexityCompletionInsight'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-danger">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Upcoming Workload Forecast</h3>
                </div>
                <div class="card-body">
                    <div class="chart-box">
                        @unless($hasForecastData)
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <p class="mb-0">No upcoming workload data available.</p>
                            </div>
                        @endunless
                        <div class="chart-loader" data-chart-loader="forecastChart">
                            <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <canvas id="forecastChart" style="display: none;"></canvas>
                    </div>
                    <div class="alert {{ str_contains($analytics['forecastSummary'], 'high workload') ? 'alert-warning' : 'alert-success' }} mb-0 mt-3">
                        <i class="fas fa-lightbulb mr-1"></i>
                        {{ $analytics['forecastSummary'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-outline {{ $analytics['workloadImbalance'] ? 'card-warning' : 'card-success' }}">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-balance-scale mr-2"></i>Workload Imbalance Warning</h3>
                </div>
                <div class="card-body">
                    @if($analytics['workloadImbalance'])
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            {{ $analytics['workloadImbalance']['message'] }}
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Overloaded</span>
                            <strong>{{ $analytics['workloadImbalance']['overloaded_user']->name }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Underloaded</span>
                            <strong>{{ $analytics['workloadImbalance']['underloaded_user']->name }}</strong>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                            <p class="mb-0">No workload imbalance detected.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-teal">
                <div class="card-header compact-analytics-header">
                    <h3 class="card-title"><i class="fas fa-stopwatch mr-2"></i>Average Task Completion Time by User</h3>
                </div>
                <div class="card-body table-responsive p-0 compact-analytics-table">
                    <table class="table table-sm table-hover mb-0 compact-table-text">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Completed Tasks</th>
                                <th>Avg. Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['averageCompletionTimes'] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ number_format($user->completed_tasks_count) }}</td>
                                    <td>
                                        <span class="badge badge-status-on-hold">
                                            {{ $user->average_completion_days ?? 0 }} days
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p class="mb-0">No completion time records found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-outline card-secondary">
                <div class="card-header compact-analytics-header">
                    <h3 class="card-title"><i class="fas fa-trophy mr-2"></i>Top 5 Most Productive Users</h3>
                </div>
                <div class="card-body table-responsive p-0 compact-analytics-table">
                    <table class="table table-sm table-hover mb-0 compact-table-text">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Completed</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['mostProductiveUsers'] as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ number_format($user->completed_tasks_count) }}</td>
                                    <td><span class="badge badge-status-completed">{{ $user->completion_rate }}%</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p class="mb-0">No productive users found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header compact-analytics-header">
                    <h3 class="card-title"><i class="fas fa-medal mr-2"></i>User Performance Leaderboard</h3>
                </div>
                <div class="card-body table-responsive p-0 leaderboard-scroll">
                    <table class="table table-sm table-hover mb-0 compact-table-text">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Completed</th>
                                <th>Overdue</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($analytics['userPerformanceScores'] as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ number_format($user->total_tasks_count) }}</td>
                                    <td>{{ number_format($user->completed_tasks_count) }}</td>
                                    <td>{{ number_format($user->overdue_tasks_count) }}</td>
                                    <td>
                                        <span class="badge {{ $user->performance_score >= 70 ? 'badge-status-completed' : ($user->performance_score >= 40 ? 'badge-status-in-progress' : 'badge-status-overdue') }}">
                                            {{ $user->performance_score }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p class="mb-0">No user performance records found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .analytics-dashboard .small-box {
        min-height: 118px;
    }

    .analytics-dashboard .small-box .inner {
        padding: 14px;
    }

    .analytics-dashboard .small-box h3 {
        font-size: 2rem;
        margin-bottom: 4px;
    }

    .analytics-dashboard .small-box p,
    .analytics-dashboard .card-title,
    .analytics-dashboard .table {
        font-size: .95rem;
    }

    .analytics-dashboard .small-box .icon > i {
        font-size: 58px;
        top: 18px;
    }

    .analytics-dashboard .card {
        border-radius: 6px;
    }

    .analytics-dashboard .card-header {
        padding: .65rem .85rem;
    }

    .analytics-dashboard .chart-box {
        height: 300px;
        position: relative;
        width: 100%;
    }

    .analytics-dashboard .workload-chart-body {
        height: 340px;
        max-height: 340px;
        overflow: hidden;
    }

    .analytics-dashboard .workload-chart-scroll {
        height: 320px;
        max-height: 320px;
        overflow-y: auto;
        overflow-x: hidden;
        position: relative;
    }

    .status-distribution-list {
        padding: 22px 12px 6px;
    }

    .status-row .progress {
        height: 8px;
        border-radius: 999px;
    }

    .analytics-table {
        max-height: 280px;
        overflow-y: auto;
    }

    .compact-analytics-header {
        padding: .5rem .75rem;
    }

    .compact-analytics-header .card-title {
        font-size: .9rem;
    }

    .compact-analytics-table {
        max-height: 200px;
        overflow-y: auto;
    }

    .compact-table-text {
        font-size: .85rem;
    }

    .compact-table-text th,
    .compact-table-text td {
        padding: .35rem .45rem;
    }

    .leaderboard-scroll {
        max-height: 320px;
        overflow-y: auto;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const weeklyChartLabels = @json($weeklyChartLabels);
    const weeklyChartValues = @json($weeklyChartValues);
    const workloadChartLabels = @json($workloadChartLabels);
    const workloadChartValues = @json($workloadChartValues);
    const complexityCompletionLabels = @json($complexityCompletionLabels);
    const complexityCompletionValues = @json($complexityCompletionValues);
    const forecastChartLabels = @json($forecastChartLabels);
    const forecastChartValues = @json($forecastChartValues);

    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    boxWidth: 12,
                    font: { size: 11 }
                }
            }
        }
    };

    new Chart(document.getElementById('completedPerWeekChart'), {
        type: 'line',
        data: {
            labels: weeklyChartLabels,
            datasets: [{
                label: 'Completed Tasks',
                data: weeklyChartValues,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, .12)',
                fill: true,
                tension: .35
            }]
        },
        options: {
            ...chartDefaults,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
    window.hideChartLoader('completedPerWeekChart');

    new Chart(document.getElementById('workloadChart'), {
        type: 'bar',
        data: {
            labels: workloadChartLabels,
            datasets: [{
                label: 'Assigned Tasks',
                data: workloadChartValues,
                backgroundColor: '#6610f2',
                borderRadius: 6,
                barThickness: 20,
                maxBarThickness: 25
            }]
        },
        options: {
            ...chartDefaults,
            indexAxis: 'y',
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
    window.hideChartLoader('workloadChart');

    new Chart(document.getElementById('complexityCompletionChart'), {
        type: 'bar',
        data: {
            labels: complexityCompletionLabels,
            datasets: [{
                label: 'Average Days to Complete',
                data: complexityCompletionValues,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'],
                borderRadius: 6
            }]
        },
        options: {
            ...chartDefaults,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true, title: { display: true, text: 'Average days' } },
                y: { grid: { display: false } }
            }
        }
    });
    window.hideChartLoader('complexityCompletionChart');

    new Chart(document.getElementById('forecastChart'), {
        type: 'line',
        data: {
            labels: forecastChartLabels,
            datasets: [{
                label: 'Tasks Due',
                data: forecastChartValues,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, .12)',
                fill: true,
                tension: .35
            }, {
                label: 'High Workload Threshold',
                data: forecastChartLabels.map(() => 5),
                borderColor: '#dc3545',
                borderDash: [6, 6],
                pointRadius: 0,
                fill: false
            }]
        },
        options: {
            ...chartDefaults,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
    window.hideChartLoader('forecastChart');

    window.applyChartTheme?.();
});
</script>
@endpush
