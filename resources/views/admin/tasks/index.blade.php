@extends('layouts.partials')

@section('content')
<div class="content-wrapper">
    <section class="content-header text-center">
        <h1>Task Complexities</h1> {{-- ✅ Updated Page Title --}}
    </section>

    <section class="content">
        <div class="row justify-content-center">
            <div class="col-12 col-md-11 col-lg-10">
                <div class="box shadow-sm rounded mx-auto">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h3 class="box-title m-0">All Task Complexities</h3> {{-- ✅ Updated Box Title --}}
                    </div>

                    <div class="box-body">
                        <div class="table-responsive">
                           <table class="table table-bordered table-hover table-striped w-100">

                                <thead class="bg-primary text-white">

                                    <tr>
                                        {{-- ✅ Updated Table Headers --}}
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
    <span class="badge bg-primary">Admin</span>
@else
    <span class="badge bg-primary">User</span>
@endif


                                        </td>
                                        <td>{{ $task->title }}</td>

                                        {{-- ✅ Show current complexity as a colored badge --}}
                                        <td>
                                            <span class="badge text-white"
                                                style="background-color: {{ optional($task->complexity)->color ?? '#6c757d' }}">
                                                {{ optional($task->complexity)->name ?? 'Not Set' }}
                                            </span>
                                        </td>

                                        {{-- ✅ Dropdown to change complexity --}}
                                        {{-- ✅ Dynamic Dropdown with name + color name --}}
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
                    </div> <!-- /.box-body -->
                </div> <!-- /.box -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </section>
</div>

<!-- Pagination Links -->
<div class="mt-3 d-flex justify-content-center">
    {{ $tasks->links('pagination::bootstrap-4') }}
</div>


{{-- ✅ Optional Styling --}}
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

    .box-body {
        padding: 1rem;
    }

    .table th, .table td {
        vertical-align: middle;
        .table-striped tbody tr:nth-of-type(odd) {
    background-color: #f0f8ff; /* light blue */
}

    }
</style>
@endsection
