@extends('layouts.partials')

@section('content')
<div class="mx-auto" style="max-width: 1200px; padding-top: 1rem;">
    <!-- Page Title -->
    <div class="text-center mb-4">
        <h1 class="mb-3 text-dark">
            <i class="fas fa-tachometer-alt mr-2"></i> Admin Dashboard
        </h1>
    </div>

    <!-- Dashboard Metrics -->
    <div class="row">
        @php
            $boxes = [
                ['count' => $stats['users_count'], 'label' => 'Users', 'icon' => 'fas fa-users', 'color' => 'info', 'route' => 'users.index'],
               
                ['count' => $stats['tasks_count'], 'label' => 'Tasks', 'icon' => 'fas fa-tasks', 'color' => 'warning', 'route' => 'tasks.index'],
                ['count' => $stats['statuses_count'], 'label' => 'Statuses', 'icon' => 'fas fa-flag', 'color' => 'danger', 'route' => 'statuses.index'],
            ];
        @endphp
        @foreach($boxes as $box)
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="small-box bg-{{ $box['color'] }}">
                    <div class="inner">
                        <h3>{{ $box['count'] }}</h3>
                        <p>{{ $box['label'] }}</p>
                    </div>
                    <div class="icon"><i class="{{ $box['icon'] }}"></i></div>
                    <a href="{{ route($box['route']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Task Management -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-tasks mr-2"></i>Task Management</h3>
            <div class="card-tools">
                <button class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-lightblue">
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Task</th>
                            <th>Status</th>
                            <th>Complexity</th>
                            
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr data-task-id="{{ $task->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name) }}&background=random" class="img-circle mr-2" width="30" alt="User Image">
                                        <span>{{ $task->user->name }}</span>
                                    </div>
                                </td>

                                {{-- ✅ Show role badge instead of dropdown --}}
                                <td>
                                    @if($task->user->is_admin)
                                        <span class="badge badge-danger">Admin</span>
                                    @else
                                        <span class="badge badge-secondary">User</span>
                                    @endif
                                </td>

                                <td class="text-truncate" style="max-width: 200px;" title="{{ $task->title }}">{{ $task->title }}</td>

                               {{-- ✅ Show task's status from status table directly --}}
<td>
    {{-- ✅ Status badge with dynamic colors like in the status view --}}
    <span class="badge 
        @if ($task->status === 'completed') bg-success
        @elseif ($task->status === 'in progress') bg-warning
        @elseif ($task->status === 'on hold') bg-info
        @elseif ($task->status === 'pending') bg-secondary
        @else bg-dark
        @endif">
        <i class="fas fa-circle mr-1"></i> {{ ucfirst($task->status) }}
        </span>
</td>
                               <td>
    <span class="badge text-white"
          style="background-color: {{ optional($task->complexity)->color ?? '#6c757d' }}">
        {{ optional($task->complexity)->name ?? 'N/AS' }}
    </span>
</td>

                               

                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-outline-danger delete-task" data-task-id="{{ $task->id }}" title="Delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No tasks available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer clearfix">
            <div class="float-right">
                {{ $tasks->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
