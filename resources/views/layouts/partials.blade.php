<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Task Manager' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6/css/all.min.css">

    {{-- AdminLTE CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    {{-- Toastr CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    {{-- Custom Styles --}}
    <style>
        .main-footer {
            background: #fff;
            border-top: 1px solid #dee2e6;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #3c8dbc !important;
            color: #fff !important;
        }
        .admin-badge {
            font-size: 0.7em;
            padding: 3px 6px;
            margin-top: 4px;
        }
        /* Admin Dashboard specific styles */
        .admin-dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .admin-table-container {
            overflow-x: auto;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .admin-table {
            width: 100%;
            margin-bottom: 0;
            background-color: #fff;
            border-collapse: separate;
            border-spacing: 0;
        }
        .admin-table th, 
        .admin-table td {
            padding: 12px 15px;
            vertical-align: middle;
            text-align: center;
            border-top: 1px solid #dee2e6;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .admin-table thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            z-index: 10;
        }
        /* Specific column widths */
        .admin-table th:nth-child(1),
        .admin-table td:nth-child(1) {
            width: 30%;
        }
        .admin-table th:nth-child(2),
        .admin-table td:nth-child(2) {
            width: 20%;
        }
        .admin-card {
            margin-bottom: 20px;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            border: 1px solid rgba(0,0,0,0.125);
            border-radius: 0.25rem;
        }
        .admin-card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,0.125);
            padding: 0.75rem 1.25rem;
            border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
        }
        .admin-card-body {
            padding: 1.25rem;
        }
        .admin-card-title {
            margin-bottom: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }
        /* Select2 custom styles */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            padding: 5px;
        }
        .admin-table .form-control {
            padding: 0.375rem 0.75rem;
            height: auto;
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .admin-table th, 
            .admin-table td {
                white-space: normal;
                padding: 8px 10px;
            }
            .admin-table-container {
                border: none;
            }
            .admin-table th:nth-child(1),
            .admin-table td:nth-child(1) {
                width: 40%;
            }
            .admin-table th:nth-child(2),
            .admin-table td:nth-child(2) {
                width: 30%;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    {{-- NAVBAR --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        {{-- User Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}"
                         class="img-circle elevation-2" width="30" alt="User Image">
                    <span class="ml-2">{{ Auth::user()->name ?? 'Guest' }}</span>
                    @auth
                        @if(auth()->user()->is_admin)
                            <span class="badge badge-danger ml-2">Admin</span>
                        @endif
                    @endauth
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">
                        {{ Auth::user()->name ?? 'User' }}<br>
                        <small>{{ Auth::user()->email ?? '' }}</small>
                        @auth
                            @if(auth()->user()->is_admin)
                                <span class="badge badge-danger admin-badge d-block mt-1">Administrator</span>
                            @endif
                        @endauth
                    </span>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- SIDEBAR --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('dashboard') }}" class="brand-link text-center">
            <i class="fas fa-check-circle me-2"></i>
            <span class="brand-text font-weight-light">Task Manager</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->is('tasks') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks text-warning"></i>
                            <p>My Tasks</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tasks.create') }}" class="nav-link {{ request()->is('tasks/create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-plus-circle text-success"></i>
                            <p>Create Task</p>
                        </a>
                    </li>

                    {{-- Admin Menu --}}
                    @auth
                        @if(Auth::user() && Auth::user()->is_admin)
                        <li class="nav-header text-uppercase text-muted mt-2"><small>Administration</small></li>
                        
                        {{-- Settings Dropdown --}}
                        <li class="nav-item has-treeview {{ request()->is('admin/dashboard*') || request()->is('admin/users*') || request()->is('admin/statuses*') || request()->is('admin/complexities*') || request()->is('admin/groups*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ request()->is('admin/dashboard*') || request()->is('admin/users*') || request()->is('admin/statuses*') || request()->is('admin/complexities*') || request()->is('admin/groups*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cog text-primary"></i>
                                <p>
                                    Admin Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{-- Admin Dashboard --}}
                                <li class="nav-item">
                                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                                        <i class="fas fa-tachometer-alt nav-icon"></i>
                                        <p>Admin Dashboard</p>
                                    </a>
                                </li>
                                
                                {{-- User Management --}}
                                <li class="nav-item has-treeview {{ request()->is('admin/users*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                        <i class="fas fa-users nav-icon"></i>
                                        <p>
                                            User Management
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users') ? 'active' : '' }}">
                                                <i class="fas fa-list nav-icon"></i>
                                                <p>User Listing</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->is('admin/users/create') ? 'active' : '' }}">
                                                <i class="fas fa-plus-circle nav-icon"></i>
                                                <p>Add User</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                {{-- Status Management --}}
                                <li class="nav-item has-treeview {{ request()->is('admin/statuses*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('admin/statuses*') ? 'active' : '' }}">
                                        <i class="fas fa-flag nav-icon"></i>
                                        <p>
                                            Status Management
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.statuses.index') }}" class="nav-link {{ request()->is('admin/statuses') ? 'active' : '' }}">
                                                <i class="fas fa-list nav-icon"></i>
                                                <p>Status Listing</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.statuses.create') }}" class="nav-link {{ request()->is('admin/statuses/create') ? 'active' : '' }}">
                                                <i class="fas fa-plus-circle nav-icon"></i>
                                                <p>Add Status</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                {{-- Task Classification --}}
                                <li class="nav-item has-treeview {{ request()->is('admin/complexities*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('admin/complexities*') ? 'active' : '' }}">
                                        <i class="fas fa-layer-group nav-icon"></i>
                                        <p>
                                            Task Classification
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.complexities.index') }}" class="nav-link {{ request()->is('admin/complexities') ? 'active' : '' }}">
                                                <i class="fas fa-list nav-icon"></i>
                                                <p>Complexity Levels</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.complexities.create') }}" class="nav-link {{ request()->is('admin/complexities/create') ? 'active' : '' }}">
                                                <i class="fas fa-plus-circle nav-icon"></i>
                                                <p>Add Complexity</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                
                                {{-- Group Management --}}
                                <li class="nav-item has-treeview {{ request()->is('admin/groups*') ? 'menu-open' : '' }}">
                                    <a href="#" class="nav-link {{ request()->is('admin/groups*') ? 'active' : '' }}">
                                        <i class="fas fa-users-cog nav-icon"></i>
                                        <p>
                                            Group Management
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('admin.groups.index') }}" class="nav-link {{ request()->is('admin/groups') ? 'active' : '' }}">
                                                <i class="fas fa-list nav-icon"></i>
                                                <p>Group Listing</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.groups.create') }}" class="nav-link {{ request()->is('admin/groups/create') ? 'active' : '' }}">
                                                <i class="fas fa-plus-circle nav-icon"></i>
                                                <p>Add Group</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        {{-- System Settings --}}
                        <li class="nav-item">
                            <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->is('admin/settings') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-sliders-h text-secondary"></i>
                                <p>System Settings</p>
                            </a>
                        </li>
                        @endif
                    @endauth

                    {{-- Regular User Settings --}}
                    <li class="nav-item">
                        <a href="{{ route('profile.edit') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-cog text-info"></i>
                            <p>Settings</p>
                        </a>
                    </li>

                    {{-- Logout --}}
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-left w-100">
                                <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                                <p>Logout</p>
                            </button> 
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- CONTENT WRAPPER --}}
    <div class="content-wrapper p-3">
        @if(isset($header))
            <div class="content-header">
                <h1 class="m-0 text-capitalize">{{ $header }}</h1>
            </div>
        @endif

        <section class="content">
            @if(request()->is('admin*'))
                <div class="admin-dashboard-container">
                    @yield('content')
                </div>
            @else
                @yield('content')
            @endif
        </section>
    </div>

    {{-- FOOTER --}}
    <footer class="main-footer text-sm text-center">
        <strong>&copy; {{ date('Y') }} Task Manager</strong> · ALOOO ALOOO KYUMU
    </footer>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- Toastr JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</body>
</html>