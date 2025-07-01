@extends('layouts.partials')



@section('title', 'My Tasks')
@section('header', 'My Tasks')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Task List</h5>
        </div>
        <div class="card-body">
            @if ($tasks->count())
                <table class="table table-bordered">
                    <tr>
        <th>Title</th>
        <th>Status</th>
        <th>User Name</th>
        <th>User ID</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @foreach ($tasks as $task)
        <tr>
            <td>{{ $task->title }}</td>
            <td>{{ ucfirst($task->status) }}</td>
            <td>{{ $task->user->name ?? 'N/A' }}</td>
            <td>{{ $task->user->id ?? 'N/A' }}</td>
            <td>
                <a href="{{ route('tasks.show', $task) }}" class="btn btn-info btn-sm">View</a>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>
                </table>

               <!-- Clean Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $tasks->links('pagination::bootstrap-5') }}
                </div>
            @else
                <p class="text-muted">No tasks found.</p>
            @endif
        </div>
    </div>
@endsection
