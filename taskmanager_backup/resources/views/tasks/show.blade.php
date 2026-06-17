@extends('layouts.partials')

@section('title', 'View Task')
@section('header', 'Task Details')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4>{{ $task->title }}</h4>
        <p>{{ $task->description }}</p>
        <p><strong>Status:</strong> {{ ucfirst($task->status) }}</p>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
