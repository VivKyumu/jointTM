@extends('layouts.partials')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Task Complexity Levels</h1>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Complexity Levels</h3>
                @if(auth()->check() && auth()->user()->is_admin)
                <div class="box-tools">
                    <a href="{{ route('admin.complexities.create') }}" class="btn btn-primary">Add Level</a>
                </div>
                @endif
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Name</th>
                            <th>Color</th>
                            @if(auth()->check() && auth()->user()->is_admin)
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($complexities as $complexity)
                        <tr>
                            <td>{{ $complexity->level }}</td>
                            <td>{{ $complexity->name }}</td>
                            <td><span class="badge" style="background-color: {{ $complexity->color }}">{{ $complexity->color }}</span></td>
                            @if(auth()->check() && auth()->user()->is_admin)
                            <td>
                                <a href="{{ route('admin.complexities.edit', $complexity->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('admin.complexities.destroy', $complexity->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection