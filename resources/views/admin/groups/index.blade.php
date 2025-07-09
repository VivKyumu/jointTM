@extends('layouts.partials')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>User Groups Management</h1>
            </div>
            @if(auth()->user()->is_admin)
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.groups.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Create New Group
                </a>
            </div>
            @endif
        </div>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">All User Groups</h3>
                <div class="box-tools">
                    <form action="{{ route('admin.groups.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="search" class="form-control pull-right" 
                                   placeholder="Search..." value="{{ request('search') }}">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="box-body">
                @if($groups->isEmpty())
                    <div class="alert alert-info">
                        No groups found. @if(auth()->user()->is_admin)Would you like to <a href="{{ route('admin.groups.create') }}">create one</a>?@endif
                    </div>
                @else
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th>Group Name</th>
                                <th width="15%">Users Count</th>
                                @if(auth()->user()->is_admin)
                                <th width="20%">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                            <tr>
                                <td>{{ $group->id }}</td>
                                <td>{{ $group->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-blue">{{ $group->users_count }}</span>
                                </td>
                                @if(auth()->user()->is_admin)
                                <td class="text-center">
                                    <a href="{{ route('admin.groups.edit', $group->id) }}" 
                                       class="btn btn-sm btn-info" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.groups.destroy', $group->id) }}" 
                                          method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                title="Delete" onclick="return confirm('Are you sure?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="box-footer clearfix">
                <div class="float-right">
                    {{ $groups->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection