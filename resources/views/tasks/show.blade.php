
@extends('layouts.partials')

@section('title', 'View Task')
@section('header', 'Task Details')

@section('content')
@if (session('success'))
    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow">
    <div class="card-header "btn btn-secondary" text-white">
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

            <a href="{{ auth()->user()->isAdmin() || auth()->user()->isManager() ? route('admin.tasks.index') : route('tasks.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back to Tasks</a>
        </form>
    </div>
</div>

<div class="card card-primary card-outline shadow-sm mt-3">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-comments mr-1"></i> Comments / Progress Notes
        </h3>
    </div>
    <div class="card-body">
        <div style="max-height: 300px; overflow-y: auto;">
            @forelse ($task->comments as $comment)
                <div class="border-bottom pb-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ $comment->user->name ?? 'Unknown User' }}</strong>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0">{{ $comment->comment }}</p>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p class="mb-0">No comments found.</p>
                </div>
            @endforelse
        </div>

        @if (auth()->user()->isAdmin() || auth()->id() === $task->user_id)
            <form action="{{ route('tasks.comments.store', $task) }}" method="POST" class="mt-3">
                @csrf
                <div class="form-group">
                    <label for="comment"><i class="fas fa-pen mr-1"></i>Add Progress Note</label>
                    <textarea id="comment" name="comment" rows="3" class="form-control @error('comment') is-invalid @enderror" required>{{ old('comment') }}</textarea>
                    @error('comment')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-1"></i> Submit Comment
                </button>
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(function () {
                $(successAlert).fadeOut(300, function () {
                    successAlert.remove();
                });
            }, 1000);
        }
    });
</script>
@endpush
