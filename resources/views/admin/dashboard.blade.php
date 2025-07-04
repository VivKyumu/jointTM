@extends('layouts.partials')

@section('content')
<!-- Dashboard Header -->
<section class="content-header bg-white py-3 mb-4 shadow-sm">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard
                </h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <!-- Stats Cards -->
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['users_count'] }}</h3>
                        <p>Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['groups_count'] }}</h3>
                        <p>Groups</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <a href="{{ route('admin.groups.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['tasks_count'] }}</h3>
                        <p>Tasks</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <a href="{{ route('tasks.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['statuses_count'] }}</h3>
                        <p>Statuses</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-flag"></i>
                    </div>
                    <a href="{{ route('admin.statuses.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Management Section -->
    <div class="container-fluid mt-4">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks mr-2"></i>Task Management
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="bg-lightblue">
                            <tr>
                                <th width="15%">User</th>
                                <th width="10%">Role</th>
                                <th width="20%">Task</th>
                                <th width="15%">Status</th>
                                <th width="15%">Complexity</th>
                                <th width="15%">Group</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr data-task-id="{{ $task->id }}">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name) }}&background=random" 
                                             class="img-circle mr-2" width="30" alt="User Image">
                                        <span>{{ $task->user->name }}</span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <select class="form-control form-control-sm role-select" data-user-id="{{ $task->user->id }}">
                                        <option value="0" {{ !$task->user->is_admin ? 'selected' : '' }}>User</option>
                                        <option value="1" {{ $task->user->is_admin ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </td>
                                <td class="align-middle text-truncate" style="max-width: 200px;" title="{{ $task->title }}">
                                    {{ $task->title }}
                                </td>
                                <td class="align-middle">
                                    <select class="form-control form-control-sm status-select" data-task-id="{{ $task->id }}">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ $task->status_id == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="align-middle">
                                    <select class="form-control form-control-sm complexity-select" data-task-id="{{ $task->id }}">
                                        @foreach($complexities as $complexity)
                                            <option value="{{ $complexity->id }}" {{ $task->complexity_id == $complexity->id ? 'selected' : '' }}>
                                                {{ $complexity->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="align-middle">
                                    <select class="form-control form-control-sm group-select" data-user-id="{{ $task->user->id }}" multiple>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ $task->user->groups->contains($group->id) ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group">
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger delete-task" data-task-id="{{ $task->id }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
    .small-box {
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        transition: transform 0.2s;
    }
    .small-box:hover {
        transform: translateY(-5px);
    }
    .small-box .icon {
        font-size: 70px;
        top: 15px;
    }
    .card-primary.card-outline {
        border-top: 3px solid #007bff;
    }
    .bg-lightblue {
        background-color: #e8f4fd;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 32px;
        padding: 2px 5px;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        margin: 2px;
        padding: 0 6px;
    }
</style>

<script>
$(document).ready(function() {
    // Initialize select2 for groups
    $('.group-select').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: "Select groups",
        allowClear: true,
        dropdownCssClass: 'select2-sm',
        minimumResultsForSearch: 5
    });

    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Debugging function
    function handleAjaxError(xhr, type) {
        console.error(type + ' Error:', xhr.responseJSON);
        let errorMsg = xhr.responseJSON?.message || 'Error processing request';
        if (xhr.status === 403) {
            errorMsg = 'You are not authorized to perform this action';
        } else if (xhr.status === 404) {
            errorMsg = 'Endpoint not found - check your routes';
        }
        toastr.error(errorMsg);
    }

    // Update user role - corrected endpoint
    $('.role-select').change(function() {
        const userId = $(this).data('user-id');
        const isAdmin = $(this).val() === '1';
        
        $.ajax({
            url: '/admin/users/' + userId + '/update-role',
            method: 'POST',
            data: {
                _method: 'PUT',
                is_admin: isAdmin
            },
            success: function(response) {
                toastr.success(response.message || 'User role updated successfully');
            },
            error: function(xhr) {
                handleAjaxError(xhr, 'Role Update');
            }
        });
    });

    // Update task status - corrected endpoint
    $('.status-select').change(function() {
        const taskId = $(this).data('task-id');
        const statusId = $(this).val();
        
        $.ajax({
            url: '/admin/tasks/' + taskId + '/update-status',
            method: 'POST',
            data: {
                _method: 'PUT',
                status_id: statusId
            },
            success: function(response) {
                toastr.success(response.message || 'Task status updated successfully');
            },
            error: function(xhr) {
                handleAjaxError(xhr, 'Status Update');
            }
        });
    });

    // Update task complexity - corrected endpoint
    $('.complexity-select').change(function() {
        const taskId = $(this).data('task-id');
        const complexityId = $(this).val();
        
        $.ajax({
            url: '/admin/tasks/' + taskId + '/update-complexity',
            method: 'POST',
            data: {
                _method: 'PUT',
                complexity_id: complexityId
            },
            success: function(response) {
                toastr.success(response.message || 'Task complexity updated successfully');
            },
            error: function(xhr) {
                handleAjaxError(xhr, 'Complexity Update');
            }
        });
    });

    // Update user groups
    $('.group-select').change(function() {
        const userId = $(this).data('user-id');
        const groupIds = $(this).val() || [];
        
        $.ajax({
            url: '/admin/users/' + userId + '/update-groups',
            method: 'POST',
            data: {
                _method: 'PUT',
                group_ids: groupIds
            },
            success: function(response) {
                toastr.success(response.message || 'User groups updated successfully');
            },
            error: function(xhr) {
                handleAjaxError(xhr, 'Groups Update');
            }
        });
    });

    // Delete task
    $('.delete-task').click(function() {
        const taskId = $(this).data('task-id');
        
        if (confirm('Are you sure you want to delete this task?')) {
            $.ajax({
                url: '/tasks/' + taskId,
                method: 'POST',
                data: {
                    _method: 'DELETE'
                },
                success: function(response) {
                    toastr.success('Task deleted successfully');
                    $('tr[data-task-id="' + taskId + '"]').fadeOut(300, function() {
                        $(this).remove();
                    });
                },
                error: function(xhr) {
                    handleAjaxError(xhr, 'Task Deletion');
                }
            });
        }
    });
});
</script>
@endsection