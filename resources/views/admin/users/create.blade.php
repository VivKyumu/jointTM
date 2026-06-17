@extends('layouts.partials')

@section('title', 'Create New User')
@section('header', 'Create New User')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-plus mr-2 text-primary"></i>Create New User
                    </h3>
                </div>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <p class="text-muted mb-4">Register a new user who can access assigned tasks.</p>

                        <div class="form-group">
                            <label for="name"><i class="fas fa-user mr-1 text-secondary"></i>Name</label>
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope mr-1 text-secondary"></i>Email</label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="role"><i class="fas fa-user-tag mr-1 text-secondary"></i>Role</label>
                            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="staff" {{ old('role', 'staff') === 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password"><i class="fas fa-lock mr-1 text-secondary"></i>Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required autocomplete="new-password">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="password" title="Show password" aria-label="Show password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="password_confirmation"><i class="fas fa-lock mr-1 text-secondary"></i>Confirm Password</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" required autocomplete="new-password">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="password_confirmation" title="Show password" aria-label="Show password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Users
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.target);
                const icon = button.querySelector('i');
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                icon.classList.toggle('fa-eye', !isHidden);
                icon.classList.toggle('fa-eye-slash', isHidden);
                button.setAttribute('title', isHidden ? 'Hide password' : 'Show password');
                button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            });
        });

        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(function () {
                $(successAlert).fadeOut(300, function () {
                    successAlert.remove();
                });
            }, 1000);
        }
    });
</script>
@endpush
