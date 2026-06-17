@extends('layouts.partials')

@section('title', 'Dashboard')
@section('header', 'My Dashboard')

@section('content')
@php
    $completionRate = $taskStats['total'] > 0
        ? round(($taskStats['completed'] / $taskStats['total']) * 100)
        : 0;

    $statusClass = function ($status) {
        return match (strtolower($status ?? '')) {
            'completed' => 'status-completed',
            'in progress' => 'status-in-progress',
            'on hold' => 'status-on-hold',
            'pending' => 'status-pending',
            default => 'status-pending',
        };
    };
    $complexityClass = function ($complexity) {
        return match (strtolower($complexity ?? '')) {
            'very simple' => 'complexity-very-simple',
            'simple' => 'complexity-simple',
            'medium' => 'complexity-medium',
            'complex' => 'complexity-complex',
            'very complex' => 'complexity-very-complex',
            default => 'status-pending',
        };
    };
    $hasUserStatusChartData = array_sum($chartData ?? []) > 0;
@endphp

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <span class="text-uppercase text-muted font-weight-bold small">Personal Workspace</span>
                        <h2 class="mb-2">Hello, {{ Auth::user()->name }}</h2>
                        <p class="text-muted mb-0">Track your tasks, deadlines, and recent updates from one place.</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> New Task
                        </a>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list mr-1"></i> My Tasks
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card bg-gradient-info">
                <div class="card-body">
                    <span class="text-uppercase small">My Completion Rate</span>
                    <div class="d-flex align-items-end justify-content-between">
                        <h1 class="mb-0">{{ $completionRate }}%</h1>
                        <i class="fas fa-check-circle fa-3x opacity-75"></i>
                    </div>
                    <div class="progress progress-sm mt-3">
                        <div class="progress-bar bg-white" style="width: {{ $completionRate }}%"></div>
                    </div>
                    <small>{{ $taskStats['completed'] }} completed out of {{ $taskStats['total'] }} tasks</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach([
            ['label' => 'Total Tasks', 'count' => $taskStats['total'], 'icon' => 'tasks', 'color' => 'primary'],
            ['label' => 'Pending', 'count' => $taskStats['pending'], 'icon' => 'hourglass-half', 'color' => 'status-pending'],
            ['label' => 'In Progress', 'count' => $taskStats['in_progress'], 'icon' => 'spinner', 'color' => 'status-in-progress'],
            ['label' => 'Overdue', 'count' => $taskStats['overdue'], 'icon' => 'clock', 'color' => 'status-overdue'],
        ] as $box)
            <div class="col-lg-3 col-md-6">
                <div class="small-box bg-{{ $box['color'] }}">
                    <div class="inner">
                        <h3>{{ $box['count'] }}</h3>
                        <p>{{ $box['label'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-{{ $box['icon'] }}"></i>
                    </div>
                    <a href="{{ route('tasks.index') }}" class="small-box-footer">
                        View Tasks <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-2 text-primary"></i>My Task Status</h3>
                </div>
                <div class="card-body">
                    @unless($hasUserStatusChartData)
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <p class="mb-0">No data available for this chart.</p>
                        </div>
                    @endunless
                    <div class="chart-loader" data-chart-loader="userStatusChart">
                        <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
                    </div>
                    <canvas id="userStatusChart" height="160" style="display: none;"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-2 text-danger"></i>Upcoming Deadlines</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($upcomingTasks as $task)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $task->title }}</strong>
                                    <span class="badge badge-{{ optional($task->due_at)->isPast() ? 'status-overdue' : 'status-on-hold' }}">
                                        {{ optional($task->due_at)->format('M d') }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    {{ $task->complexity_name }} | Created {{ $task->created_at->format('M d, Y') }}
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No upcoming deadlines found.</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-list-check mr-2 text-success"></i>Recent Tasks</h3>
                    <div class="card-tools">
                        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-primary">Open Tasks</a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Complexity</th>
                                <th>Due</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        <div class="text-muted small text-truncate" style="max-width: 240px;">{{ $task->description ?? 'No description' }}</div>
                                    </td>
                                    <td><span class="badge badge-{{ $statusClass($task->status_name) }}">{{ ucfirst($task->status_name) }}</span></td>
                                    <td>
                                        <span class="badge badge-{{ $complexityClass($task->complexity_name) }}">
                                            {{ $task->complexity_name }}
                                        </span>
                                    </td>
                                    <td>{{ optional($task->due_at)->format('M d, Y') ?? 'Not set' }}</td>
                                    <td>{{ $task->created_at->format('M d, Y') }}</td>
                                    <td>{{ $task->updated_at->diffForHumans() }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-info" title="View task">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p class="mb-0">No tasks found.</p>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusChart = document.getElementById('userStatusChart');

        if (statusChart) {
            new Chart(statusChart, {
                type: 'doughnut',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        data: @json($chartData),
                        backgroundColor: ['#6c757d', '#ffc107', '#28a745']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '65%'
                }
            });
            window.hideChartLoader('userStatusChart');
        }
    });
</script>
@endpush
