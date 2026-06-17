{{-- resources/views/tasks/create.blade.php --}}
@extends('layouts.partials')

@section('title', 'Create Task')
@section('header', 'Create New Task')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Add a New Task</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Task Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Create Task</button>
            </form>
        </div>
    </div>
@endsection
