@extends('layouts.partials')

@section('title', 'Dashboard')
@section('header', 'Welcome, ' . Auth::user()->name . '!')

@section('content')
    <!-- Welcome Card -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body py-4">
            <h4 class="mb-3 fw-semibold text-primary">👋 Hello, {{ Auth::user()->name }}!</h4>
            <p class="text-muted mb-2">This is your dashboard. Use the sidebar to manage your tasks.</p>
            <p class="text-sm text-muted"><i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}</p>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-start border-primary shadow-sm">
                <div class="card-body">
                    <h5><i class="fas fa-tasks me-2 text-primary"></i>My Tasks</h5>
                    <p class="text-muted mb-2">Manage all your assigned tasks.</p>
                    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-primary">View Tasks</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-start border-success shadow-sm">
                <div class="card-body">
                    <h5><i class="fas fa-plus-circle me-2 text-success"></i>Create Task</h5>
                    <p class="text-muted mb-2">Quickly add a new task to your list.</p>
                    <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-outline-success">New Task</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-start border-secondary shadow-sm">
                <div class="card-body">
                    <h5><i class="fas fa-cog me-2 text-secondary"></i>Settings</h5>
                    <p class="text-muted mb-2">Update your profile and preferences.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary">Profile Settings</a>
                </div>
            </div>
        </div>
    </div>
@endsection
