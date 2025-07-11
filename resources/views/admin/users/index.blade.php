@extends('layouts.partials')

@section('content')
<div class="content-wrapper">
    <section class="content-header text-center">
        <h1>User Management</h1>
    </section>

    <section class="content">
      <div class="row">
    <div class="col-12">

                <div class="box shadow-sm rounded">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h3 class="box-title m-0">User Listing</h3>
                        <div class="box-tools">
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="fa fa-user-plus"></i> Add User
                            </a>
                        </div>
                    </div>

                    <div class="container-fluid">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center align-middle">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->is_admin)
                                                <span class="badge bg-danger">Admin</span>
                                            @else
                                                <span class="badge bg-primary">User</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-info">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                    <i class="fa fa-user-slash"></i> Deactivate
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Clean Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            <nav aria-label="Page navigation">
                                {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Optional Inline Styling --}}
<style>
    .box {
        background-color: #fff;
        border-radius: 0.75rem;
    }

    .box-header {
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .box-body {
        padding: 1rem;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .table thead th {
        font-weight: 600;
    }

    .pagination {
        margin-bottom: 0;
    }
</style>
@endsection
