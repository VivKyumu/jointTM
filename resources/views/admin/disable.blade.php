@extends('layouts.partials')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white d-flex align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-user-slash mr-2"></i> Disable User Task
            </h3>
        </div>

        <div class="card-body">
            {{-- ✅ Task Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center bg-white">
                    <thead class="thead-dark">
                        <tr>
                            <th><i class="fas fa-id-badge"></i> User ID</th>
                            <th><i class="fas fa-user"></i> Name</th>
                            <th><i class="fas fa-heading"></i> Title</th>
                            <th><i class="fas fa-align-left"></i> Description</th>
                            <th><i class="fas fa-toggle-on"></i> Status</th>
                            <th><i class="fas fa-ban"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $task->user->id ?? 'N/A' }}</td>
                            <td>{{ $task->user->name ?? 'N/A' }}</td>
                            <td>{{ $task->title }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($task->description, 60) }}</td>
                            <td>
                                @if($task->status === 'disabled')
                                    <span class="badge bg-danger"><i class="fas fa-times-circle mr-1"></i> Disabled</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-check-circle mr-1"></i> Active</span>
                                @endif
                            </td>
                            <td>
                                @if($task->status !== 'disabled')
                                    <form action="{{ route('admin.tasks.disable', $task->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to disable this task?')">
                                            <i class="fas fa-ban"></i> Disable
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        <i class="fas fa-lock"></i> Already Disabled
                                    </button>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Back Button --}}
            <div class="mt-3">
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Task List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
