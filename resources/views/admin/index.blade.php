@extends('layouts.partials')

@section('content')
<div class="container mt-4">
    <h2 class="text-dark">
        <i class="fas fa-tasks text-primary me-2"></i> All Tasks
    </h2>

    {{-- 🔍 Search Form --}}
    <form method="GET" action="{{ route('admin.tasks.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by title or description" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
    </form>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif


    <table class="table table-striped table-hover rounded shadow-sm bg-white align-middle">
        <thead class="thead-light">
            <tr>
                <th><i class="fas fa-heading"></i> Title</th>
                <th><i class="fas fa-align-left"></i> Description</th>
                <th><i class="fas fa-id-badge"></i> User ID</th>
                <th><i class="fas fa-user"></i> User Name</th>
                <th><i class="fas fa-user-tag"></i> Role</th>
                <th><i class="fas fa-cogs"></i> Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($task->description, 50) }}</td>
                    <td>{{ $task->user->id ?? 'N/A' }}</td>
                    <td>{{ $task->user->name ?? 'N/A' }}</td>

                    <td>
                        <span class="badge 
                            {{ $task->user->isAdmin() ? 'bg-primary' : 'bg-secondary' }}">
                            <i class="fas {{ $task->user->isAdmin() ? 'fa-shield-alt' : 'fa-user' }} me-1"></i>
                            {{ $task->user->isAdmin() ? 'Admin' : 'User' }}
                        </span>
                    </td>

                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            {{-- ✏️ Edit --}}
                            <a href="{{ route('admin.tasks.edit', $task) }}" class="btn btn-outline-info" data-toggle="tooltip" title="Edit Task">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- 🚫 Disable (optional) --}}
                            <form action="{{ route('admin.tasks.disable', $task->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary" data-toggle="tooltip" title="Disable Task">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>

                            {{-- 🗑️ Delete --}}
                            <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" data-toggle="tooltip" title="Delete Task">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $tasks->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Tooltip
        $('[data-toggle="tooltip"]').tooltip();

        // Auto-dismiss success alert
        setTimeout(function () {
            $("#success-alert").fadeOut("slow");
        }, 3000); // 3 seconds
    });
</script>

@endpush

@push('styles')
<style>
    .table td, .table th {
        vertical-align: middle;
    }

    .btn-group .btn {
        margin-right: 5px;
    }

    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: white;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.45em 0.6em;
    }

    .input-group .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
</style>
@endpush
