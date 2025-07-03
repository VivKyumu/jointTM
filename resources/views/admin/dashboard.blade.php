@extends('layouts.partials')

@section('content')
<div class="container">
    <h2 class="mb-2">
        Hello Admin {{ Auth::user()->name }}, Welcome to your dashboard
    </h2>
    <p class="text-muted mb-4">Here's a quick overview of your task stats and users.</p>

    <!-- Task Statistics -->
    <div class="row text-center mb-4">
        @php
            $stats = [
                ['title' => 'Total Tasks', 'count' => $totalTasks, 'bg' => 'primary'],
                ['title' => 'Completed Tasks', 'count' => $completedTasks, 'bg' => 'success'],
                ['title' => 'Pending Tasks', 'count' => $pendingTasks, 'bg' => 'warning'],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="col-md-4 mb-2">
                <div class="card bg-{{ $stat['bg'] }} text-white shadow">
                    <div class="card-body">
                        <h6 class="mb-0">{{ $stat['title'] }}</h6>
                        <h3>{{ $stat['count'] }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- User List -->
<div class="card mb-4">
    <div class="card-header">Registered Users</div>
    <div class="card-body">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Task Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->tasks_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No users found with more than 1 task.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($users->hasPages())
    <nav class="mt-3">
        <ul class="pagination justify-content-center">
            {{-- Previous Page --}}
            <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">«</a>
            </li>

            {{-- Page Links --}}
            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next Page --}}
            <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">»</a>
            </li>
        </ul>
    </nav>
@endif


    <!-- Quick Actions -->
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-primary">View All Tasks</a>
    </div>

    <!-- Chart Section -->
    <div class="row mb-4 mt-3">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">Tasks Completed Over Time</div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="taskChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">Task Status Breakdown</div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Line Chart
    const ctxTask = document.getElementById('taskChart').getContext('2d');
    new Chart(ctxTask, {
        type: 'line',
        data: {
            labels: @json($taskDates ?? []),
            datasets: [{
                label: 'Tasks Completed',
                data: @json($taskCounts ?? []),
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderColor: '#007bff',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });

    // Doughnut Chart
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'In Progress', 'Completed'],
            datasets: [{
                data: @json($statusDistribution ?? [0, 0, 0]),
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745'],
                borderColor: ['#ffc107', '#17a2b8', '#28a745'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
