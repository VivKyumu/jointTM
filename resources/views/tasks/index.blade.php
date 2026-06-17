@extends('layouts.partials')

@section('title', 'My Tasks')
@section('header', 'My Tasks')

@section('content')
@php
    $statusClass = fn ($status) => match (strtolower($status ?? '')) {
        'completed' => 'status-completed',
        'in progress' => 'status-in-progress',
        'on hold' => 'status-on-hold',
        'pending' => 'status-pending',
        default => 'status-pending',
    };
    $complexityClass = fn ($complexity) => match (strtolower($complexity ?? '')) {
        'very simple' => 'complexity-very-simple',
        'simple' => 'complexity-simple',
        'medium' => 'complexity-medium',
        'complex' => 'complexity-complex',
        'very complex' => 'complexity-very-complex',
        default => 'status-pending',
    };
@endphp
<div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Task List</h5>
         @auth
                <div class="text-end mt-4">
                    <button class="btn btn-dark" style="background-color: #14532d;" data-toggle="modal" data-target="#createTaskModal">
                        <i class="fas fa-plus-circle me-1"></i> Create Task
                    </button>
                </div>
            @endauth
    </div>
    <div class="card-body">
        @if ($tasks->count())
            <table class="table table-bordered table-striped align-middle text-nowrap"id="taskTable">
                <thead class="bg-light text-dark">
                    <tr>
                        <th>Title</th>
                        <th style="max-width: 200px;">Description</th>
                        <th>Status</th>
                        <th>User Name</th>
                        <th>User ID</th>
                        <th>Due Date</th>
                        <th>Complexity</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="taskList">
                    @foreach ($tasks as $task)
                        <tr id="task-row-{{ $task->id }}">
                            <td>{{ $task->title }}</td>
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $task->description }}">
                                {{ $task->description }}
                            </td>
                            <td class="task-status"><span class="badge badge-{{ $statusClass($task->status) }}">{{ ucfirst($task->status) }}</span></td>
                            <td>{{ $task->user->name ?? 'N/A' }}</td>
                            <td>{{ $task->user->id ?? 'N/A' }}</td>
                            <td>
                                @if($task->due_at)
                                    {{ $task->due_at->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td><span class="badge badge-{{ $complexityClass(optional($task->complexity)->name) }}">{{ $task->complexity->name ?? 'N/A' }}</span></td>
                            <td>{{ $task->created_at->format('M d, Y') }}</td>
                            <td>{{ $task->updated_at->format('M d, Y') }}</td>
                            <td style="min-width: 160px;">
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

           

            <div class="d-flex justify-content-center mt-3">
                {{ $tasks->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox fa-2x mb-2"></i>
                <p class="mb-0">No tasks found.</p>
            </div>
        @endif
    </div>
</div>

<style>
    .badge {
        padding: 0.4em 0.75em;
        font-size: 0.85rem;
        border-radius: 0.5rem;
    }

    .table td, .table th {
        vertical-align: middle !important;
    }
</style>

@auth
    @include('tasks._create_modal', ['complexities' => $complexities])
@endauth
<script>
$(document).ready(function () {
    $('#createTaskForm').submit(function (e) {
        e.preventDefault();

        let form = $(this);

        $.ajax({
            url: "{{ route('tasks.store') }}",
            method: "POST",
            data: form.serialize(),
            success: function (task) {
                // Build task row
                const newRow = `
                    <tr>
                        <td>${task.title}</td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${task.description}">
                            ${task.description}
                        </td>
                        <td>${task.status.charAt(0).toUpperCase() + task.status.slice(1)}</td>
                        <td>${task.user_name}</td>
                        <td>${task.user_id}</td>
                        <td>${task.due_at ?? '<span class="text-muted">Not set</span>'}</td>
                        <td>${task.complexity_name ?? 'N/A'}</td>
                        <td>${task.created_at}</td>
                        <td>${task.updated_at}</td>
                        <td style="min-width: 160px;">
                            <div class="d-flex gap-1">
                                <a href="/tasks/${task.id}" class="btn btn-info btn-sm w-100">View</a>
                                <a href="/tasks/${task.id}/edit" class="btn btn-warning btn-sm w-100">Edit</a>
                                <form action="/tasks/${task.id}" method="POST" class="d-inline delete-task-form" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-secondary btn-sm w-100">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `;

                // Append to task list
                $('table tbody').prepend(newRow);

                // Show success toast
                $('#taskSuccessMsg').removeClass('d-none').text('Task created successfully!');

                // Reset form
                form[0].reset();

                // Hide modal and success message
                setTimeout(() => {
                    $('#createTaskModal').modal('hide');
                    $('#taskSuccessMsg').addClass('d-none');
                }, 1000);
            },
            error: function (xhr) {
                let msg = xhr.responseJSON?.message || 'Something went wrong.';
                alert(msg);
            }
        });
    });
});
</script>
@endsection
