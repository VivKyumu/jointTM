@extends('layouts.partials')

@section('title', 'Edit Task')
@section('header', 'Edit Task')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Task Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="form-control" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" required>{{ old('description', $task->description) }}</textarea>
            </div>

            <!-- Complexity -->
            <div class="mb-3">
                <label for="complexity_id" class="form-label">Complexity</label>
                <select name="complexity_id" id="complexity_id" class="form-control" required>
                    @foreach ($complexities as $complexity)
                        <option value="{{ $complexity->id }}" data-duration="{{ $complexity->duration }}"
                            {{ $task->complexity_id == $complexity->id ? 'selected' : '' }}>
                            {{ $complexity->name }} ({{ $complexity->duration }} days)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Due Date -->
            <div class="mb-3">
                <label for="due_at" class="form-label">Due Date</label>
                <input type="date" name="due_at" id="due_at" class="form-control"
                    value="{{ old('due_at', optional($task->due_at)->format('Y-m-d')) }}">
                @if (!$task->due_at)
                    <small class="text-muted">Currently: Not set</small>
                @endif
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status_id" class="form-label">Status</label>
                <select name="status_id" id="statusDropdown" class="form-select" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" {{ $task->status_id == $status->id ? 'selected' : '' }}>
                            {{ ucfirst($status->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update Task</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const complexitySelect = document.getElementById('complexity_id');
        const dueAtInput = document.getElementById('due_at');

        // Only update due date if it's empty or not set
        const isDueDateEmpty = !dueAtInput.value;

        complexitySelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const durationDays = selectedOption.getAttribute('data-duration');

            if (durationDays && isDueDateEmpty) {
                const today = new Date();
                today.setDate(today.getDate() + parseInt(durationDays));

                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');

                dueAtInput.value = `${yyyy}-${mm}-${dd}`;
            }
        });
    });
</script>
@endpush
