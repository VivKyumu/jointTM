@extends('layouts.partials')

@section('title', 'Email Logs')
@section('header', 'Email Logs')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-envelope-open-text mr-2"></i>Email Logs
        </h3>
    </div>
    <div class="card-body">
        <div style="max-height: 450px; overflow-y: auto; overflow-x: auto;">
            <table class="table table-bordered table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Date & Time</th>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Recipient</th>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Type</th>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Subject</th>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Related Task</th>
                        <th style="position: sticky; top: 0; z-index: 1; background-color: inherit;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($emailLogs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                            <td>{{ $log->recipient_email }}</td>
                            <td>{{ $log->mail_type }}</td>
                            <td>{{ $log->subject }}</td>
                            <td>{{ $log->task->title ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $log->status === 'failed' ? 'badge-danger' : 'badge-success' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                                @if($log->error_message)
                                    <small class="d-block text-danger">{{ $log->error_message }}</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No email logs found.</p>
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
