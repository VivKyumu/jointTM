@extends('layouts.partials')

@section('title', 'Admin Dashboard')
@section('header', 'Admin Dashboard')

@section('content')
@php
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
    $hasStatusChartData = collect($statusChartData ?? [])->sum() > 0;
    $hasComplexityChartData = collect($complexityChartData ?? [])->sum() > 0;
@endphp

@push('styles')
<style>
    :root {
        --tm-blue: #2563eb;
        --tm-teal: #0f766e;
        --tm-amber: #f59e0b;
        --tm-red: var(--status-overdue);
        --tm-slate: #475569;
        --tm-soft: #f8fafc;
        --tm-line: #e5e7eb;
    }

    .dashboard-hero,
    .dashboard-panel,
    .metric-card {
        border-radius: 8px;
    }

    .dashboard-hero {
        border-top: 3px solid var(--tm-blue);
    }

    .metric-card {
        color: #fff;
        min-height: 86px;
        overflow: hidden;
    }

    .metric-card .inner {
        padding: 10px 14px 4px;
    }

    .metric-card h3 {
        font-size: 1.45rem;
        line-height: 1.1;
        margin-bottom: 0;
    }

    .metric-card p {
        margin-bottom: 0;
        font-size: .84rem;
    }

    .metric-card .icon {
        top: 10px;
        right: 12px;
        font-size: 38px;
        opacity: .16;
    }

    .metric-card .small-box-footer {
        padding: 3px 0;
        font-size: .78rem;
        background: rgba(15, 23, 42, .16);
    }

    .metric-blue { background: var(--tm-blue); }
    .metric-teal { background: var(--tm-teal); }
    .metric-amber { background: var(--tm-amber); color: #111827; }
    .metric-red { background: var(--tm-red); }
    .metric-slate { background: var(--tm-slate); }

    .chart-box {
        position: relative;
        width: 100%;
        height: 230px;
    }

    .chart-card-row {
        align-items: stretch;
    }

    .chart-card-row .dashboard-panel {
        height: 100%;
    }

    .chart-card-row .card-body {
        display: flex;
        flex-direction: column;
    }

    .mini-stat {
        border: 1px solid var(--tm-line);
        border-radius: 8px;
        padding: 7px 9px;
        background: var(--tm-soft);
    }

    .dashboard-panel .card-header {
        padding: .65rem .9rem;
    }

    .dashboard-panel .card-title {
        font-size: 1rem;
    }

    .dashboard-panel .card-body {
        padding: .85rem;
    }

    .completion-card .card-body {
        padding: 1rem 1.25rem;
    }

    .completion-card h1 {
        font-size: 2.1rem;
    }

    .completion-card .fa-chart-line {
        font-size: 2.3rem;
    }

    .lower-dashboard-row {
        align-items: stretch;
    }

    .compact-dashboard-card {
        height: 100%;
    }

    .compact-dashboard-card .card-header {
        padding: .55rem .75rem;
        min-height: 42px;
    }

    .compact-dashboard-card .card-title {
        font-size: .94rem;
        font-weight: 600;
    }

    .compact-dashboard-card .card-tools .btn {
        padding: .15rem .45rem;
        font-size: .75rem;
    }

    .compact-scroll-body {
        max-height: 320px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .compact-table {
        font-size: .78rem;
    }

    .compact-table th {
        padding: .45rem .5rem;
        font-weight: 700;
        border-top: 0;
        position: sticky;
        top: 0;
        z-index: 1;
        background: #fff;
    }

    .compact-table td {
        padding: .42rem .5rem;
        vertical-align: middle;
    }

    .compact-table .badge,
    .compact-list .badge {
        font-size: .68rem;
        padding: .24rem .42rem;
    }

    .compact-icon-btn {
        padding: .12rem .32rem;
        font-size: .72rem;
    }

    .compact-avatar {
        width: 30px;
        height: 30px;
        object-fit: cover;
    }

    .compact-list .list-group-item {
        padding: .55rem .65rem;
    }

    .compact-list-title {
        font-size: .82rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .compact-list-meta {
        font-size: .72rem;
        line-height: 1.25;
    }

    .dashboard-filter-bar {
        border: 1px solid var(--tm-line);
        border-radius: 8px;
        background: var(--tm-soft);
        padding: .6rem .75rem;
    }

    .print-dashboard-header,
    .print-chart-image {
        display: none;
    }

    @media print {
        @page {
            size: A4;
            margin: 12mm;
        }

        body,
        .content-wrapper,
        .card,
        .small-box {
            background: #fff !important;
            color: #111827 !important;
        }

        .main-header,
        .main-sidebar,
        .main-footer,
        .content-header,
        .btn,
        .card-tools,
        .small-box-footer,
        .lower-dashboard-row,
        .chart-loader,
        canvas {
            display: none !important;
        }

        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
        }

        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
        }

        .print-dashboard-header,
        .print-chart-image {
            display: block !important;
        }

        .dashboard-hero,
        .completion-card,
        .metric-card,
        .dashboard-panel {
            border: 1px solid #d1d5db !important;
            box-shadow: none !important;
            break-inside: avoid;
        }

        .metric-card,
        .completion-card {
            color: #111827 !important;
        }

        .metric-card .icon,
        .completion-card .fa-chart-line {
            opacity: .25 !important;
        }

        .chart-card-row {
            display: flex !important;
        }

        .chart-card-row > div {
            width: 50% !important;
            max-width: 50% !important;
            flex: 0 0 50% !important;
        }

        .print-chart-image {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
        }
    }
</style>
@endpush

<div class="container-fluid">
    <div class="print-dashboard-header mb-3">
        <h2 class="mb-1">Task Manager System - Dashboard Report</h2>
        <p class="mb-0">Admin: {{ Auth::user()->name }}</p>
        <p class="mb-0">Report generated on: <span id="printGeneratedAt"></span></p>
    </div>

    <div class="row mb-3">
        <div class="col-lg-8">
            <div class="card dashboard-hero">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <span class="text-uppercase text-muted font-weight-bold small">System Overview</span>
                        <h2 class="mb-2">Welcome back, {{ Auth::user()->name }}</h2>
                        <p class="text-muted mb-0">Monitor users, tasks, deadlines, statuses, and recent updates from one dashboard.</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <button type="button" class="btn btn-outline-secondary" id="printDashboardBtn">
                            <i class="fas fa-print mr-1"></i> Print Dashboard
                        </button>
                        <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> New Task
                        </a>
                        <a href="{{ route('users.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus mr-1"></i> New User
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card metric-blue text-white dashboard-panel completion-card">
                <div class="card-body">
                    <span class="text-uppercase small">Completion Rate</span>
                    <div class="d-flex align-items-end justify-content-between">
                        <h1 class="mb-0">{{ $completionRate }}%</h1>
                        <i class="fas fa-chart-line fa-3x opacity-75"></i>
                    </div>
                    <div class="progress progress-sm mt-3">
                        <div class="progress-bar bg-white" style="width: {{ $completionRate }}%"></div>
                    </div>
                    <small>{{ $stats['completed_tasks_count'] }} of {{ $stats['tasks_count'] }} tasks completed</small>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-filter-bar mb-3">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div class="mb-2 mb-md-0">
                <span class="font-weight-bold"><i class="fas fa-filter mr-1"></i> Dashboard Filter</span>
                <span class="text-muted small ml-2">Showing: {{ $dashboardFilters[$dashboardFilter] ?? 'All Time' }}</span>
            </div>
            <div class="btn-group btn-group-sm flex-wrap" role="group" aria-label="Dashboard quick filters">
                @foreach($dashboardFilters as $filterKey => $filterLabel)
                    <a href="{{ route('admin.dashboard', ['filter' => $filterKey]) }}"
                       class="btn {{ $dashboardFilter === $filterKey ? 'btn-primary' : 'btn-outline-secondary' }}">
                        {{ $filterLabel }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        @foreach([
            ['label' => 'Users', 'count' => $stats['users_count'], 'icon' => 'users', 'class' => 'metric-teal', 'route' => 'users.index'],
            ['label' => 'Admins', 'count' => $stats['admins_count'], 'icon' => 'user-shield', 'class' => 'metric-blue', 'route' => 'users.index'],
            ['label' => 'Tasks', 'count' => $stats['tasks_count'], 'icon' => 'tasks', 'class' => 'metric-slate', 'route' => 'admin.tasks.index'],
            ['label' => 'Overdue', 'count' => $stats['overdue_tasks_count'], 'icon' => 'clock', 'class' => 'metric-red', 'route' => 'admin.tasks.index'],
        ] as $box)
            <div class="col-lg-3 col-md-6">
                <div class="small-box metric-card {{ $box['class'] }}">
                    <div class="inner">
                        <h3>{{ $box['count'] }}</h3>
                        <p>{{ $box['label'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-{{ $box['icon'] }}"></i>
                    </div>
                    <a href="{{ route($box['route']) }}" class="small-box-footer">
                        Open <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row chart-card-row">
        <div class="col-md-8 col-lg-8 mb-3">
            <div class="card dashboard-panel">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-stream mr-2 text-primary"></i>Task Status</h3>
                    <div class="card-tools">
                        <a href="{{ route('statuses.index') }}" class="btn btn-tool" title="Manage statuses">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-box">
                        @unless($hasStatusChartData)
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <p class="mb-0">No data available for this chart.</p>
                            </div>
                        @endunless
                        <div class="chart-loader" data-chart-loader="adminStatusChart">
                            <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <canvas id="adminStatusChart" style="display: none;"></canvas>
                        <img id="adminStatusChartPrint" class="print-chart-image" alt="Task status chart">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 mb-3">
            <div class="card dashboard-panel">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-layer-group mr-2 text-success"></i>Complexity Mix</h3>
                    <div class="card-tools">
                        <a href="{{ route('complexities.index') }}" class="btn btn-tool" title="Manage complexity levels">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-box">
                        @unless($hasComplexityChartData)
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <p class="mb-0">No data available for this chart.</p>
                            </div>
                        @endunless
                        <div class="chart-loader" data-chart-loader="adminComplexityChart">
                            <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
                        </div>
                        <canvas id="adminComplexityChart" style="display: none;"></canvas>
                        <img id="adminComplexityChartPrint" class="print-chart-image" alt="Complexity mix chart">
                    </div>
                    <div class="row mt-3">
                        @foreach($complexityLabels as $index => $label)
                            <div class="col-6 mb-2">
                                <div class="mini-stat">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-muted small">{{ $label }}</span>
                                        <span class="badge badge-light">{{ $complexityChartData[$index] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row lower-dashboard-row">
        <div class="col-lg-6 mb-3">
            <div class="card compact-dashboard-card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-list-check mr-2 text-warning"></i>Latest Tasks</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0 compact-scroll-body">
                    <table class="table table-hover text-nowrap mb-0 compact-table">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Complexity</th>
                                <th>Updated</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        <div class="text-muted text-truncate" style="max-width: 160px;">Created {{ optional($task->created_at)->format('M d') }}</div>
                                    </td>
                                    <td>{{ optional($task->user)->name ?? 'Unassigned' }}</td>
                                    <td><span class="badge badge-{{ $statusClass($task->status_name) }}">{{ ucfirst($task->status_name) }}</span></td>
                                    <td>
                                        <span class="badge badge-{{ $complexityClass($task->complexity_name) }}">
                                            {{ $task->complexity_name }}
                                        </span>
                                    </td>
                                    <td>{{ optional($task->updated_at)->diffForHumans() }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary compact-icon-btn" title="Edit task">
                                            <i class="fas fa-edit"></i>
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

        <div class="col-lg-3 mb-3">
            <div class="card compact-dashboard-card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-users mr-2 text-info"></i>Recent Users</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-info">Manage</a>
                    </div>
                </div>
                <div class="card-body p-0 compact-scroll-body">
                    <ul class="list-group list-group-flush compact-list">
                        @forelse($recentUsers as $user)
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff"
                                         class="img-circle compact-avatar mr-2" alt="{{ $user->name }}">
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <a href="{{ route('users.show', $user) }}" class="compact-list-title text-dark text-truncate">
                                                {{ $user->name }}
                                            </a>
                                            <span class="badge badge-{{ $user->is_admin ? 'primary' : 'secondary' }} ml-1">
                                                {{ $user->is_admin ? 'Admin' : 'User' }}
                                            </span>
                                        </div>
                                        <div class="compact-list-meta text-muted text-truncate">{{ $user->email }}</div>
                                        <div class="compact-list-meta text-muted">
                                            {{ $user->tasks_count }} tasks | Joined {{ $user->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No users found.</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-3 mb-3">
            <div class="card compact-dashboard-card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-history mr-2 text-secondary"></i>Recently Updated</h3>
                </div>
                <div class="card-body p-0 compact-scroll-body">
                    <ul class="list-group list-group-flush compact-list">
                        @forelse($latestUpdatedTasks as $task)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="pr-2">
                                        <div class="compact-list-title text-truncate" style="max-width: 170px;">{{ $task->title }}</div>
                                        <div class="compact-list-meta text-muted">
                                            {{ optional($task->user)->name ?? 'Unassigned' }}
                                        </div>
                                        <div class="compact-list-meta text-muted">Updated {{ $task->updated_at->diffForHumans() }}</div>
                                    </div>
                                    <span class="badge badge-{{ $statusClass($task->status_name) }}">
                                        {{ ucfirst($task->status_name) }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item">
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No recent updates found.</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusChart = document.getElementById('adminStatusChart');
        const complexityChart = document.getElementById('adminComplexityChart');
        const printDashboardBtn = document.getElementById('printDashboardBtn');

        const prepareDashboardPrint = function () {
            document.getElementById('printGeneratedAt').textContent = new Date().toLocaleString();

            [
                ['adminStatusChart', 'adminStatusChartPrint'],
                ['adminComplexityChart', 'adminComplexityChartPrint'],
            ].forEach(function ([canvasId, imageId]) {
                const canvas = document.getElementById(canvasId);
                const image = document.getElementById(imageId);

                if (canvas && image) {
                    image.src = canvas.toDataURL('image/png');
                }
            });
        };

        if (statusChart) {
            new Chart(statusChart, {
                type: 'bar',
                data: {
                    labels: @json($statusLabels),
                    datasets: [{
                        label: 'Tasks',
                        data: @json($statusChartData),
                        backgroundColor: ['#6c757d', '#ffc107', '#28a745', '#17a2b8'],
                        borderRadius: 6
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
            window.hideChartLoader('adminStatusChart');
        }

        if (complexityChart) {
            new Chart(complexityChart, {
                type: 'doughnut',
                data: {
                    labels: @json($complexityLabels),
                    datasets: [{
                        data: @json($complexityChartData),
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'],
                        borderWidth: 0
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '65%'
                }
            });
            window.hideChartLoader('adminComplexityChart');
        }

        printDashboardBtn?.addEventListener('click', function () {
            prepareDashboardPrint();
            window.print();
        });

        window.addEventListener('beforeprint', prepareDashboardPrint);
    });
</script>
@endpush
