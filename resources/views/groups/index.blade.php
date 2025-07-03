@php
use App\Models\User;
@endphp

@extends('layouts.partials')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-dark text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-users-cog me-2"></i>User Group Management
                        </h3>
                        <span class="badge bg-light text-dark fs-6">Admin Portal</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Regular Users Card -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <a href="{{ route('groups.users') }}" class="text-decoration-none">
                                    <div class="card-body text-center p-4">
                                        <div class="icon-container bg-info bg-opacity-10 rounded-circle p-4 mb-3">
                                            <i class="fas fa-users fa-3x text-info"></i>
                                        </div>
                                        <h4 class="card-title text-dark mb-2">Regular Users</h4>
                                        <div class="user-count-display bg-light rounded-pill py-2 px-3 d-inline-block">
                                            <span class="fs-4 fw-bold text-primary">
                                                {{ User::whereHas('roles', fn($q) => $q->where('name', 'user'))->count() }}
                                            </span>
                                            <span class="text-muted">registered users</span>
                                        </div>
                                        <p class="text-muted mt-3 mb-0">
                                            Manage all regular user accounts
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 py-3 text-center">
                                        <span class="btn btn-outline-info rounded-pill px-4">
                                            View Details <i class="fas fa-arrow-right ms-2"></i>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Admin Users Card -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <a href="{{ route('groups.admins') }}" class="text-decoration-none">
                                    <div class="card-body text-center p-4">
                                        <div class="icon-container bg-warning bg-opacity-10 rounded-circle p-4 mb-3">
                                            <i class="fas fa-user-shield fa-3x text-warning"></i>
                                        </div>
                                        <h4 class="card-title text-dark mb-2">Administrators</h4>
                                        <div class="user-count-display bg-light rounded-pill py-2 px-3 d-inline-block">
                                            <span class="fs-4 fw-bold text-primary">
                                                {{ User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count() }}
                                            </span>
                                            <span class="text-muted">admin users</span>
                                        </div>
                                        <p class="text-muted mt-3 mb-0">
                                            Manage administrator accounts and permissions
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 py-3 text-center">
                                        <span class="btn btn-outline-warning rounded-pill px-4">
                                            View Details <i class="fas fa-arrow-right ms-2"></i>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-effect {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .icon-container {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 80px;
        height: 80px;
    }
    .card-header {
        border-bottom: 2px solid rgba(255,255,255,0.1);
    }
</style>
@endsection