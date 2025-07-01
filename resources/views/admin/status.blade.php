@extends('layouts.partials')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-tasks"></i> Task Status
            </h3>
        </div>

        {{-- <div class="card-body"> --}}
            {{-- ✅ Flash message --}}
            {{-- @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif --}}

            {{-- ✅ Task Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th><i class="fas fa-id-badge"></i> User ID</th>
                            <th><i class="fas fa-user"></i> User Name</th>
                            <th><i class="fas fa-heading"></i> Title</th>
                            <th><i class="fas fa-align-left"></i> Description</th>
                            <th><i class="fas fa-info-circle"></i> Status</th>
                            <th><i class="fas fa-edit"></i> Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tasks as $task)
                            <tr>
                                <td>{{ $task->user->id ?? 'N/A' }}</td>
                                <td>{{ $task->user->name ?? 'N/A' }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($task->description, 60) }}</td>
                                <td>
                                    <span class="badge 
                                        @if ($task->status === 'completed') bg-success
                                        @elseif ($task->status === 'in progress') bg-warning
                                        @elseif ($task->status === 'on hold') bg-info
                                        @else bg-secondary @endif">
                                        <i class="fas fa-circle mr-1"></i> {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                                {{-- ✅ Inline form with dropdown --}}
                                <td>
                                    <form action="{{ route('admin.tasks.status.update', $task->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <div class="input-group input-group-sm">
                                            <select name="status" class="form-select" onchange="this.form.submit()">
                                                <option disabled selected>Change</option>
                                                <option value="pending"      @selected($task->status == 'pending')>Pending</option>
                                                <option value="in progress"  @selected($task->status == 'in progress')>In Progress</option>
                                                <option value="on hold"      @selected($task->status == 'on hold')>On Hold</option>
                                                <option value="completed"    @selected($task->status == 'completed')>Completed</option>
                                            </select>
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="fas fa-sync-alt"></i>
                                            </span>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">No tasks available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div> {{-- Close .table-responsive --}}

{</div> {{-- Close .table-responsive --}}

@if ($tasks->hasPages())
    <nav aria-label="Task pagination">
        <ul class="pagination justify-content-center mt-3">
            {{-- Previous Page Link --}}
            @if ($tasks->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $tasks->previousPageUrl() }}" rel="prev">&laquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($tasks->getUrlRange(1, $tasks->lastPage()) as $page => $url)
                <li class="page-item {{ $tasks->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next Page Link --}}
            @if ($tasks->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $tasks->nextPageUrl() }}" rel="next">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

            </div>
        </div>
    </div>
</div>
@endsection
