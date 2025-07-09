@extends('layouts.partials')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>Group Details</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.groups.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Group Information</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">ID</th>
                        <td>{{ $group->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $group->name }}</td>
                    </tr>
                    <tr>
                        <th>Users Count</th>
                                <td><span class="badge bg-blue">{{ $group->users_count }}</span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        <a href="{{ route('admin.groups.edit', $group->id) }}" class="btn btn-primary">Edit</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Group Members</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('admin.groups.addUser', $group->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <select name="user_id" class="form-control" required>
                                    <option value="">Select User</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-success">Add User</button>
                                </span>
                            </div>
                        </form>
                        
                        <hr>
                        
                        <ul class="list-group">
                            @foreach($group->users as $user)
                            <li class="list-group-item">
                                {{ $user->name }} ({{ $user->email }})
                                <form action="{{ route('admin.groups.removeUser', [$group->id, $user->id]) }}" 
                                    method="POST" style="display:inline; float:right;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-danger" 
                                        onclick="return confirm('Remove this user?')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection