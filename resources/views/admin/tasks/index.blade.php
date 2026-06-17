@extends('layouts.partials')

@section('title', 'Task Complexities')

@section('content')
@php
    $complexityClass = fn ($complexity) => match (strtolower($complexity ?? '')) {
        'very simple' => 'complexity-very-simple',
        'simple' => 'complexity-simple',
        'medium' => 'complexity-medium',
        'complex' => 'complexity-complex',
        'very complex' => 'complexity-very-complex',
        default => 'status-pending',
    };
@endphp
<section class="content-header text-center">
    <h1>Task Complexities</h1>
</section>

<section class="content">
    <div class="box shadow-sm rounded mx-auto" style="max-width: 1100px;">
        <div class="box-header with-border d-flex justify-content-between align-items-center">
            <h3 class="box-title m-0">All Task Complexities</h3>
        </div>

        <table class="table table-bordered table-hover table-striped w-100">
            <thead class="bg-secondary text-white">
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Task Title</th>
                    <th>Complexity</th>
                    <th>Change Complexity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->user->id }}</td>
                    <td>{{ $task->user->name }}</td>
                    <td>
                        @if($task->user->is_admin)
                            <span class="badge bg-secondary">Admin</span>
                        @else
                            <span class="badge bg-secondary">User</span>
                        @endif
                    </td>
                    <td>{{ $task->title }}</td>
                    <td>
                        <span class="badge badge-{{ $complexityClass(optional($task->complexity)->name) }}">
                            {{ optional($task->complexity)->name ?? 'Not Set' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ url('admin/tasks/' . $task->id . '/complexity') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="complexity_id" onchange="this.form.submit()" class="form-control form-control-sm">
                                <option disabled selected>Change</option>
                                @foreach($complexities as $complexity)
                                    <option value="{{ $complexity->id }}" @selected($task->complexity_id == $complexity->id)>
                                        {{ $complexity->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $tasks->links('pagination::bootstrap-4') }}
    </div>
</section>

<style>
    .badge {
        padding: 0.5em 1em;
        font-size: 0.9rem;
        border-radius: 0.5rem;
    }
    .box {
        background: #fff;
        border-radius: 0.75rem;
    }
    .box-header {
        padding: 1rem;
        border-bottom: 1px solid #ddd;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }
</style>
@endsection
