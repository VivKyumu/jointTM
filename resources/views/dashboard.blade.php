
@extends('layouts.partials')

@section('title', 'Dashboard')
@section('header', 'Welcome, ' . Auth::user()->name . '!')

@section('content')
<!-- Toast for task creation -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    <div id="dashboardToast" class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto"><i class="fas fa-check-circle me-2"></i>Success</strong>
            <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            Task updated successfully!
        </div>
    </div>
</div>

<!-- Welcome Card -->
<div class="card shadow-lg mb-4 bg-light border-0 animate__animated animate__fadeInDown">
    <div class="card-body py-4">
        <h4 class="mb-3 fw-bold text-primary">👋 Hello, {{ Auth::user()->name }}!</h4>
        <p class="text-muted mb-2">This is your dashboard. Use the sidebar to manage your tasks effectively.</p>
        <p class="text-muted"><i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}</p>
    </div>
</div>



<!-- Dashboard Widgets -->
<div class="row g-3">
    <!-- My Tasks -->
    <div class="col-md-4">
        <div class="card border-left border-primary shadow h-100 py-2 animate__animated animate__fadeInUp">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">My Tasks</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">You have <strong>{{ $taskCount ?? 0 }}</strong> tasks</div>
                <div class="mt-2">
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary btn-sm">View Tasks</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Task -->
    <div class="col-md-4">
        <div class="card border-left border-success shadow h-100 py-2 animate__animated animate__fadeInUp delay-1s">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Create Task</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Start something new</div>
                <div class="mt-2">
                    <button class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#createTaskModal">
                        + New Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Settings -->
    <div class="col-md-4">
        <div class="card border-left border-info shadow h-100 py-2 animate__animated animate__fadeInUp delay-2s">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Settings</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Customize your profile</div>
                <div class="mt-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-info btn-sm">Profile Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- 
<!-- Charts Row -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm animate__animated animate__fadeInLeft">
            <div class="card-header">
                <h6 class="m-0 text-primary"><i class="fas fa-chart-line me-2"></i>Task Completion Overview</h6>
            </div>
            <div class="card-body">
                <canvas id="taskChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm animate__animated animate__fadeInRight">
            <div class="card-header">
                <h6 class="m-0 text-success"><i class="fas fa-chart-pie me-2"></i>Status Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>


@endsection
