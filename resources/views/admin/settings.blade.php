@extends('layouts.partials')

@section('title', 'Admin Settings')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>System Settings</h1>
    </section>

    <section class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">General Settings</h3>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="box-body">

                    {{-- Site Name --}}
                    <div class="form-group">
                        <label for="site_name">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" 
                            value="{{ old('site_name', config('app.name')) }}" required>
                    </div>

                    {{-- Default User Role --}}
                    <div class="form-group">
                        <label for="default_user_role">Default User Role</label>
                        <select class="form-control" id="default_user_role" name="default_user_role" required>
                            <option value="user" {{ old('default_user_role', 'user') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('default_user_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    {{-- Allow User Registration --}}
                    <div class="form-group">
                        <label for="allow_registration">Allow User Registration</label>
                        <select class="form-control" id="allow_registration" name="allow_registration" required>
                            <option value="1" {{ old('allow_registration', 1) == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('allow_registration') == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    {{-- Site Mode --}}
                    <div class="form-group">
                        <label for="site_mode">Site Mode</label>
                        <select class="form-control" id="site_mode" name="site_mode" required>
                            <option value="live" {{ old('site_mode', 'live') == 'live' ? 'selected' : '' }}>Live</option>
                            <option value="maintenance" {{ old('site_mode') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>

                    {{-- Timezone --}}
                    <div class="form-group">
                        <label for="timezone">Timezone</label>
                        <select name="timezone" id="timezone" class="form-control">
                            @foreach(timezone_identifiers_list() as $tz)
                                <option value="{{ $tz }}" {{ old('timezone', config('app.timezone')) == $tz ? 'selected' : '' }}>
                                    {{ $tz }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Support Email --}}
                    <div class="form-group">
                        <label for="support_email">Support Email</label>
                        <input type="email" class="form-control" id="support_email" name="support_email" 
                            value="{{ old('support_email', config('mail.from.address')) }}">
                    </div>

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
