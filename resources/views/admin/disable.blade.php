@extends('layouts.partials')

@section('title', 'Disable User')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-white d-flex align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-user-slash mr-2"></i> Disable User Account
            </h3>
        </div>

        <div class="card-body">

            
            {{-- ✅ User Info Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center bg-white">
                    <thead class="thead-dark">
                        <tr>
                            <th><i class="fas fa-id-badge"></i> User ID</th>
                            <th><i class="fas fa-user"></i> Name</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-user-tag"></i> Role</th>
                            <th><i class="fas fa-toggle-off"></i> Status</th>
                            <th><i class="fas fa-ban"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $task->user->id ?? 'N/A' }}</td>
                            <td>{{ $task->user->name ?? 'N/A' }}</td>
                            <td>{{ $task->user->email ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $task->user->isAdmin() ? 'bg-primary' : 'bg-secondary' }}">
                                    <i class="fas {{ $task->user->isAdmin() ? 'fa-shield-alt' : 'fa-user' }} me-1"></i>
                                    {{ $task->user->isAdmin() ? 'Admin' : 'User' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i> Inactive
                                </span>
                            </td>
                            <td>
                                {{-- Still show disable button (if needed for future logic) --}}
                                <button class="btn btn-sm btn-danger" disabled>
                                    <i class="fas fa-ban"></i> Disabled
                                </button>
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
