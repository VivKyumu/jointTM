@extends('layouts.partials')

@section('title', 'User Management')

@section('content')
<section class="content-header text-center">
    <h1>User Management</h1>
</section>

<section class="content">
    {{-- Success message --}}
    @if (session('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="box shadow-sm rounded">
        <div class="box-header d-flex justify-content-between align-items-center">
            <h3 class="box-title m-0">User Listing</h3>
            <a href="{{ route('users.create') }}" class="btn btn-secondary">
                <i class="fa fa-user-plus"></i> Add User
            </a>
        </div>

        <div class="table-responsive p-3">
            <table class="table table-bordered table-striped table-hover text-center align-middle">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Access</th> 
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="{{ !$user->is_active ? 'table-danger opacity-75' : '' }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->isAdmin() ? 'bg-primary' : ($user->isManager() ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                {{ $user->isAdmin() ? 'Admin' : ($user->isManager() ? 'Manager' : 'Staff') }}
                            </span>
                        </td>
                        <td>
                            @if ($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Disabled</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-info mb-1">
                                <i class="fa fa-edit"></i> Edit
                            </a>

                            @if ($user->is_active)
                                <form action="{{ route('users.disable', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-secondary"
                                        onclick="return confirm('Are you sure you want to disable this user?')">
                                        Disable
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('users.enable', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('Re-enable this user?')">
                                        Enable
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</section>

{{-- Success message script --}}
@if (session('success'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                setTimeout(() => {
                    $(successAlert).fadeOut(300, function () {
                        successAlert.remove();
                    });
                }, 1000);
            }
        });
    </script>
@endif

{{-- Styling --}}
<style>
    .content-header {
        margin-top: 20px;
    }

    .box {
        background-color: #fff;
        border-radius: 0.75rem;
        padding: 1rem;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .pagination {
        margin-bottom: 0;
    }

    .opacity-75 {
        opacity: 0.75;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            margin-left: 0 !important;
            padding: 10px;
        }
    }
</style>
@endsection
