@extends('layouts.partials')

@section('title', 'Activity Logs')
@section('header', 'Activity Logs')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history mr-2"></i>Activity Log / Audit Trail</h3>
    </div>
    <div class="card-body">
        <div style="max-height: 400px; overflow-y: auto; overflow-x: auto;">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Date & Time</th>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Performed By</th>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Action</th>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ optional($log->user)->name ?? 'System' }}</td>
                            <td><span class="badge badge-info">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No activity logs found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
