
@extends('layouts.partials')

@section('title', 'View Task')
@section('header', 'Task Details')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title"><i class="fas fa-tasks me-2"></i>Task Information</h3>
    </div>

    <div class="card-body">
        <form>
            <!-- Task Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Task Title</label>
                <input type="text" id="title" class="form-control" value="{{ $task->title }}" disabled>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" class="form-control" rows="4" disabled>{{ $task->description }}</textarea>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" id="status" class="form-control" value="{{ ucfirst($task->status) }}" disabled>
            </div>

            <!-- Assigned User Name -->
            <div class="mb-3">
                <label class="form-label">Assigned User</label>
                <input type="text" class="form-control" value="{{ $task->user->name ?? 'N/A' }}" disabled>
            </div>

            <!-- Assigned User ID -->
            <div class="mb-3">
                <label class="form-label">User ID</label>
                <input type="text" class="form-control" value="{{ $task->user_id ?? 'N/A' }}" disabled>
            </div>

            <a href="{{ route('tasks.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back to Tasks</a>
        </form>
    </div>
</div>
@endsection
