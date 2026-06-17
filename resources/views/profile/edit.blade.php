@extends('layouts.partials')

@section('title', 'My Profile | Task Manager')
@section('header', 'My Profile')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success" id="profile-success-alert">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-2"></i>Please check the form and try again.
                </div>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-circle mr-2"></i>Profile Information</h3>
                </div>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="text-center mb-4">
                            @php($profilePhoto = auth()->user()->photo ?: auth()->user()->avatar)
                            @if($profilePhoto)
                                <img src="{{ asset('storage/' . $profilePhoto) }}"
                                     alt="Profile Photo"
                                     style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff;">
                            @else
                                <div style="width:120px; height:120px; border-radius:50%; background:#6c757d; display:flex; align-items:center; justify-content:center; font-size:2rem; color:#fff; font-weight:bold; border: 3px solid #007bff; margin: 0 auto;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="mt-2">
                                <span class="badge badge-{{ $user->is_admin ? 'primary' : 'secondary' }}">
                                    {{ $user->is_admin ? 'Admin' : 'User' }}
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="avatar"><i class="fas fa-camera mr-1"></i>Profile Photo</label>
                            <input type="file" name="avatar" id="avatar" class="form-control-file @error('avatar') is-invalid @enderror" accept="image/*">
                            @error('avatar')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name"><i class="fas fa-user mr-1"></i>Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope mr-1"></i>Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="current_password"><i class="fas fa-lock mr-1"></i>Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Required only when changing password">
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password"><i class="fas fa-key mr-1"></i>New Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave blank to keep current password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation"><i class="fas fa-key mr-1"></i>Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ $user->is_admin ? route('admin.dashboard') : route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>Save Profile
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
    setTimeout(function () {
        const alert = document.getElementById('profile-success-alert');
        if (alert) {
            alert.style.transition = 'opacity .3s ease';
            alert.style.opacity = '0';
            setTimeout(function () {
                alert.style.display = 'none';
            }, 300);
        }
    }, 1000);
</script>
@endpush
