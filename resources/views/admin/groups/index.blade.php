@extends('layouts.partials')

@section('title', 'Groups')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Group Management</h1>
            @if(auth()->user()->is_admin)
            <a href="{{ route('groups.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Create New Group
            </a>
            @endif
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title">All Groups</h3>
            </div>
            <div class="card-body">
                @if($groups->isEmpty())
                    <div class="alert alert-info">
                        No groups found. 
                        @if(auth()->user()->is_admin)
                        Would you like to <a href="{{ route('groups.create') }}">create one</a>?
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th rowspan="2" style="width: 5%" class="align-middle">ID</th>
                                    <th rowspan="2" class="align-middle">Group Name</th>
                                    <th colspan="3" class="text-center">Group Members</th>
                                    @if(auth()->user()->is_admin)
                                    <th rowspan="2" class="text-center align-middle" style="width: 20%">Actions</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th class="text-center">Admins</th>
                                    <th class="text-center">Users</th>
                                    <th class="text-center">Guests</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groups as $group)
                                <tr>
                                    <td>{{ $group->id }}</td>
                                    <td>{{ $group->name }}</td>

                                    {{-- Count of Admins --}}
                                    <td class="text-center">
                                        <span class="badge bg-danger">
                                            {{ $group->users->filter(fn($u) => $u->is_admin)->count() }}
                                        </span>
                                    </td>

                                    {{-- Count of Users --}}
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            {{ $group->users->filter(fn($u) => $u->roles->contains('name', 'user'))->count() }}
                                        </span>
                                    </td>

                                    {{-- Count of Guests --}}
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            {{ $group->users->filter(fn($u) => $u->roles->contains('name', 'guest'))->count() }}
                                        </span>
                                    </td>

                                    @if(auth()->user()->is_admin)
                                    <td class="text-center">
                                        <a href="{{ route('groups.edit', $group->id) }}" 
                                           class="btn btn-sm btn-info" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('groups.destroy', $group->id) }}" 
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
                    </div>
                @endif
            </div>
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $groups->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
