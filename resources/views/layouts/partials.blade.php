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

        <ul class="navbar-nav ml-auto">
            @php
    $notifications = Auth::user()->unreadNotifications;
@endphp

<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        @if($notifications->count())
            <span class="badge badge-warning navbar-badge">{{ $notifications->count() }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">{{ $notifications->count() }} Notifications</span>
        <div class="dropdown-divider"></div>

        @foreach ($notifications as $notification)
    <a href="{{ route('tasks.show', $notification->data['task_id'] ?? 0) }}" class="dropdown-item">
        <i class="fas fa-tasks mr-2"></i>
        {{ $notification->data['message'] ?? 'No message available' }}
        <span class="float-right text-muted text-sm">
            {{ $notification->created_at->diffForHumans() }}
        </span>
    </a>
    <div class="dropdown-divider"></div>
@endforeach

        @if($notifications->count())
            <a href="{{ route('notifications.markAllAsRead') }}" class="dropdown-item dropdown-footer">
                Mark all as read
            </a>
        @else
            <span class="dropdown-item text-muted">No new notifications</span>
        @endif
    </div>
</li>


            {{-- User Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}"
                         class="img-circle elevation-2" width="30" alt="User Image">
                    <span class="ml-2">{{ Auth::user()->name ?? 'Guest' }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">
                        {{ Auth::user()->name ?? 'User' }}<br>
                        <small>{{ Auth::user()->email ?? '' }}</small>
                    </span>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user me-2"></i> Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
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
                    <a href="#" class="nav-link" data-toggle="modal" data-target="#createTaskModal">
                        <i class="nav-icon fas fa-plus-circle text-success"></i>
                        <p>Create Task</p>
                    </a>
                </li>

                {{-- Admin Panel Dropdown --}}
                @auth
                    @if (Auth::user() && Auth::user()->isAdmin()) 
                        <li class="nav-item has-treeview {{ request()->is('admin/*') ? 'menu-open' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield text-lightblue"></i>
                                <p>
                                    Admin Dropdown
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.tasks.index') }}" class="nav-link {{ request()->routeIs('admin.tasks.index') ? 'active' : '' }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>All Tasks</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.tasks.status', 1) }}" class="nav-link">
                                        <i class="fas fa-info-circle nav-icon"></i>
                                        <p>Task Status</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endauth

                {{-- Profile --}}
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-cog text-info"></i>
                        <p>Profile</p>
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
            @yield('content')
        </section>
    </div>

    {{-- FOOTER --}}
    <footer class="main-footer text-sm text-center">
        <strong>&copy; {{ date('Y') }} Task Manager</strong> · Built by Vivian Mbachi
    </footer>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- ✅ Chart.js should go here -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Task Modal --}}
    @include('tasks._create_modal')
    <script>
<script>
$(document).ready(function () {
    $('#createTaskForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                $('#taskSuccessMsg').removeClass('d-none').fadeIn();

                setTimeout(() => {
                    $('#createTaskModal').modal('hide');
                    form[0].reset();
                    $('#taskSuccessMsg').addClass('d-none');
                }, 1500);
            },
            error: function (xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong.'));
            }
        });
    });
});
</script>

@stack('scripts')

</body>
</html>
