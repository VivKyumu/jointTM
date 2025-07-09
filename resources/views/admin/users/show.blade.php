@extends('layouts.partials')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>User Details</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">User Information</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            @if($user->is_admin)
                                <span class="badge badge-danger">Admin</span>
                            @else
                                <span class="badge badge-primary">User</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $user->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-footer">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </section>
</div>
@endsection