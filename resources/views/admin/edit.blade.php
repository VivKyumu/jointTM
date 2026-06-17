@extends('layouts.partials')

@section('title', 'Edit Admin')

@section('content')
<div class="container">
    <h2>Edit Task</h2>
    <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- User Selection Dropdown --}}
        <div class="form-group">
            <label for="user_id">Assign to User</label>
            <select class="form-control" name="user_id" id="user_id" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="title">Task Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $task->title }}" required>
        </div>

        <div class="form-group">
            <label for="description">Task Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ $task->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="disabled" {{ $task->status == 'disabled' ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>

        <div class="form-group">
            <label for="complexity_id" class="form-label">Complexity</label>
            <select name="complexity_id" id="complexity_id" class="form-control" required>
                <option value="" disabled {{ is_null($task->complexity_id) ? 'selected' : '' }}>Select Complexity</option>
                @foreach ($complexities as $complexity)
                    <option value="{{ $complexity->id }}" {{ old('complexity_id', $task->complexity_id) == $complexity->id ? 'selected' : '' }}>
                        {{ $complexity->name }}
                    </option>
                @endforeach
            </select>
            @error('complexity_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Task</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
