@extends('layouts.partials')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>Status Management</h1>
            </div>
            @if(auth()->user()->is_admin)
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.statuses.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add New Status
                </a>
            </div>
            @endif
        </div>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">All Statuses</h3>
                <div class="box-tools">
                    <form action="{{ route('admin.statuses.index') }}" method="GET" class="form-inline">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" name="search" class="form-control" 
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
                @if($statuses->isEmpty())
                    <div class="alert alert-info">
                        No statuses found. @if(auth()->user()->is_admin)Would you like to 
                        <a href="{{ route('admin.statuses.create') }}">create one</a>?@endif
                    </div>
                @else
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th>Name</th>
                                <th>Color</th>
                                <th>Preview</th>
                                @if(auth()->user()->is_admin)
                                <th width="15%">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statuses as $status)
                            <tr>
                                <td>{{ $status->id }}</td>
                                <td>{{ $status->name }}</td>
                                <td>{{ $status->color }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $status->color }}">
                                        {{ $status->name }}
                                    </span>
                                </td>
                                @if(auth()->user()->is_admin)
                                <td class="text-center">
                                    <a href="{{ route('admin.statuses.edit', $status->id) }}" 
                                       class="btn btn-sm btn-info" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.statuses.destroy', $status->id) }}" 
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
                    {{ $statuses->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection