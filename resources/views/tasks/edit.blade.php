@extends('layouts.partials')

@section('title', 'Edit Task')
@section('header', 'Edit Task')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('tasks.update', $task) }}">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Task Title</label>
                    <input type="text" name="title" id="title" value="{{ $task->title }}" class="form-control" required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control">{{ $task->description }}</textarea>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="in progress"  @selected($task->status == 'in progress')>In Progress</option>
                        <option value="on hold"      @selected($task->status == 'on hold')>On Hold</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <button type="submit" class="btn btn-success">Update Task</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
