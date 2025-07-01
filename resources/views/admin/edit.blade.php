@extends('layouts.partials')

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

        <button type="submit" class="btn btn-success">Update Task</button>
    </form>
</div>
@endsection
