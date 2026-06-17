@extends('layouts.partials')

@section('title', 'Task Dashboard')

@section('content')
<div class="container">
    <h2 class="mb-2">
        Hello  {{ Auth::user()->name }}, Welcome to your dashboard
    </h2>
    <p class="text-muted mb-4">Here's a quick overview of your personal task stats.</p>

    <!-- Task Statistics -->
    <div class="row text-center mb-4">
        @php
            $stats = [
                ['title' => 'Your In Progress Tasks', 'count' => $userInProgressTasks, 'bg' => 'status-in-progress'],
                ['title' => 'Your Completed Tasks', 'count' => $userCompletedTasks, 'bg' => 'status-completed'],
                ['title' => 'Your Pending Tasks', 'count' => $userPendingTasks, 'bg' => 'status-pending'],
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

    <!-- Quick Actions -->
    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary">View All My Tasks</a>
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
    // Line Chart: Tasks Completed Over Time
    const ctxTask = document.getElementById('taskChart').getContext('2d');
    new Chart(ctxTask, {
        type: 'line',
        data: {
            labels: @json($taskDates ?? []),
            datasets: [{
                label: 'Tasks Completed',
                data: @json($taskCounts ?? []),
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderColor: '#28a745',
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

    // Doughnut Chart: Task Status Breakdown
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'In Progress', 'Completed'],
            datasets: [{
                data: @json($statusDistribution ?? [0, 0, 0]),
                backgroundColor: ['#6c757d', '#ffc107', '#28a745'],
                borderColor: ['#6c757d', '#ffc107', '#28a745'],
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
