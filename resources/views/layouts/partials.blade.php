<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @php($pageTitle = trim($__env->yieldContent('title', $title ?? 'Task Manager')))
    <title>{{ str_contains($pageTitle, 'Task Manager') ? $pageTitle : $pageTitle . ' | Task Manager' }}</title>
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
        :root {
            --status-pending: #6c757d;
            --status-in-progress: #ffc107;
            --status-completed: #28a745;
            --status-on-hold: #17a2b8;
            --status-overdue: #dc3545;
            --complexity-very-simple: #007bff;
            --complexity-simple: #28a745;
            --complexity-medium: #ffc107;
            --complexity-complex: #dc3545;
            --complexity-very-complex: #6f42c1;
        }
        .badge-status-pending, .bg-status-pending { background-color: var(--status-pending) !important; color: #fff !important; }
        .badge-status-in-progress, .bg-status-in-progress { background-color: var(--status-in-progress) !important; color: #212529 !important; }
        .badge-status-completed, .bg-status-completed { background-color: var(--status-completed) !important; color: #fff !important; }
        .badge-status-on-hold, .bg-status-on-hold { background-color: var(--status-on-hold) !important; color: #fff !important; }
        .badge-status-overdue, .bg-status-overdue { background-color: var(--status-overdue) !important; color: #fff !important; }
        .badge-complexity-very-simple, .bg-complexity-very-simple { background-color: var(--complexity-very-simple) !important; color: #fff !important; }
        .badge-complexity-simple, .bg-complexity-simple { background-color: var(--complexity-simple) !important; color: #fff !important; }
        .badge-complexity-medium, .bg-complexity-medium { background-color: var(--complexity-medium) !important; color: #212529 !important; }
        .badge-complexity-complex, .bg-complexity-complex { background-color: var(--complexity-complex) !important; color: #fff !important; }
        .badge-complexity-very-complex, .bg-complexity-very-complex { background-color: var(--complexity-very-complex) !important; color: #fff !important; }
        .chart-loader {
            min-height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .chart-box .chart-loader {
            min-height: 100%;
        }
        .dark-mode {
            background-color: #1f232a;
            color: #f0f0f0;
        }
        .navbar-dark-mode-toggle {
            cursor: pointer;
        }
        .dark-mode .content-wrapper,
        .dark-mode .modal-content,
        .dark-mode .card,
        .dark-mode .table,
        .dark-mode .form-control,
        .dark-mode .custom-select,
        .dark-mode .dropdown-menu {
            background-color: #2b3038;
            color: #f0f0f0;
        }
        .dark-mode .main-header,
        .dark-mode .main-footer {
            background-color: #242932;
            color: #f0f0f0;
            border-color: #3a404a;
        }
        .dark-mode .main-sidebar {
            background-color: #1f232a;
        }
        .dark-mode .card-header,
        .dark-mode .table thead th,
        .dark-mode .table td,
        .dark-mode .table th {
            border-color: #3a404a;
        }
        .dark-mode .form-control,
        .dark-mode .custom-select {
            border-color: #4a515c;
        }
        .dark-mode .text-muted {
            color: #cbd5e1 !important;
        }
        .dark-mode .text-dark,
        .dark-mode .table,
        .dark-mode .table td,
        .dark-mode .table th,
        .dark-mode .card-title,
        .dark-mode .card-body,
        .dark-mode .list-group-item,
        .dark-mode .dropdown-item {
            color: #f8fafc !important;
        }
        .dark-mode .list-group-item,
        .dark-mode .mini-stat,
        .dark-mode .compact-table th {
            background-color: #343a40 !important;
            border-color: #4b5563 !important;
        }
        .dark-mode .badge-light {
            background-color: #4b5563;
            color: #f8fafc;
        }
        .dark-mode a:not(.btn):not(.nav-link):not(.dropdown-item) {
            color: #93c5fd;
        }
        body.dark-mode .card,
        body.dark-mode .modal-content,
        body.dark-mode .dropdown-menu,
        body.dark-mode .list-group-item,
        body.dark-mode .mini-stat {
            background-color: #1e1e2e !important;
            color: #e0e0e0 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        body.dark-mode .card-header,
        body.dark-mode .modal-header,
        body.dark-mode .table thead th,
        body.dark-mode .compact-table th {
            background-color: #2a2a3e !important;
            color: #e0e0e0 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        body.dark-mode .table,
        body.dark-mode .table td,
        body.dark-mode .table th {
            color: #e0e0e0 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        body.dark-mode .table tbody tr,
        body.dark-mode .bg-white {
            background-color: #1e1e2e !important;
        }
        body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
            background-color: #252535 !important;
        }
        body.dark-mode .table-hover tbody tr:hover {
            background-color: #2f2f46 !important;
            color: #ffffff !important;
        }
        body.dark-mode .form-control,
        body.dark-mode .form-select,
        body.dark-mode .custom-select,
        body.dark-mode input,
        body.dark-mode textarea,
        body.dark-mode select {
            background-color: #2a2a3e !important;
            color: #e0e0e0 !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
        body.dark-mode .form-control::placeholder,
        body.dark-mode input::placeholder,
        body.dark-mode textarea::placeholder {
            color: #9ca3af !important;
        }
        body.dark-mode .modal-footer,
        body.dark-mode .card-footer,
        body.dark-mode .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        body.dark-mode .alert {
            border-color: rgba(255, 255, 255, 0.12) !important;
        }
        body.dark-mode .alert-info {
            background-color: rgba(23, 162, 184, 0.16) !important;
            color: #d9f8ff !important;
        }
        body.dark-mode .alert-success {
            background-color: rgba(40, 167, 69, 0.16) !important;
            color: #dcfce7 !important;
        }
        body.dark-mode .alert-danger {
            background-color: rgba(220, 53, 69, 0.16) !important;
            color: #fee2e2 !important;
        }
        body.dark-mode .chart-box,
        body.dark-mode canvas {
            background-color: transparent !important;
        }
        body.dark-mode .btn-default,
        body.dark-mode .btn-outline-secondary {
            background-color: #2a2a3e !important;
            color: #e0e0e0 !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    {{-- Top navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        {{-- Left navbar links --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        {{-- Right navbar icons --}}
        <ul class="navbar-nav ml-auto">
            {{-- Dark mode toggle --}}
            <li class="nav-item">
                <a class="nav-link" href="#" id="darkModeToggle" role="button">
                    <i class="fas fa-moon"></i>
                </a>
            </li>

            {{-- User avatar dropdown --}}
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : (Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)) }}"
                         class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : (Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)) }}"
                             class="img-circle elevation-2" alt="User Image">
                        <p>
                            {{ Auth::user()->name }}
                            <small>Member since {{ Auth::user()->created_at->format('F Y') }}</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">
                            <i class="fas fa-user mr-1"></i> My Profile
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">
                            <i class="fas fa-cog mr-1"></i> Settings
                        </a>
                        <a href="#" class="btn btn-default btn-flat float-right"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-unlock mr-1"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    {{-- SIDEBAR --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.tasks.dashboard') }}" class="brand-link text-center">
            <i class="fas fa-check-circle me-2"></i>
            <span class="brand-text font-weight-light">Task Manager</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.tasks.dashboard') }}" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie text-info"></i>
                            <p>Task Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tasks.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-tasks text-warning"></i>
                            <p>My Tasks</p>
                        </a>
                    </li>

                    @auth
                        @if (Auth::user()->isAdmin())
                            <li class="nav-header text-uppercase text-muted mt-2"><small>Administration</small></li>

                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                    <i class="fas fa-tachometer-alt nav-icon"></i>
                                    <p>Admin Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cog text-primary"></i>
                                    <p>
                                        Admin Settings
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.tasks.index') }}" class="nav-link">
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
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}" class="nav-link">
                                            <i class="fas fa-users nav-icon"></i>
                                            <p>User Listing</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('complexities.index') }}" class="nav-link">
                                            <i class="fas fa-layer-group nav-icon"></i>
                                            <p>Complexity Levels</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.analytics') }}" class="nav-link">
                                            <i class="fas fa-chart-line nav-icon"></i>
                                            <p>Analytics</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.activity-logs') }}" class="nav-link">
                                            <i class="fas fa-history nav-icon"></i>
                                            <p>Activity Logs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.email-logs') }}" class="nav-link">
                                            <i class="fas fa-envelope-open-text nav-icon"></i>
                                            <p>Email Logs</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if (Auth::user()->isManager())
                            <li class="nav-header text-uppercase text-muted mt-2"><small>Management</small></li>

                            <li class="nav-item">
                                <a href="{{ route('admin.tasks.dashboard') }}" class="nav-link">
                                    <i class="nav-icon fas fa-chart-pie text-info"></i>
                                    <p>Task Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.tasks.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-list text-primary"></i>
                                    <p>All Tasks</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.analytics') }}" class="nav-link">
                                    <i class="nav-icon fas fa-chart-line text-success"></i>
                                    <p>Analytics</p>
                                </a>
                            </li>
                        @endif
                    @endauth

                    <li class="nav-item">
                        <a href="{{ route('profile.edit') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-cog text-info"></i>
                            <p>Settings</p>
                        </a>
                    </li>

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
    <div class="content-wrapper p-0">
        @if(isset($header))
            <div class="content-header mb-3">
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
        <strong>&copy; {{ date('Y') }} Task Manager</strong> | Built by Vivian Mbachi
    </footer>
</div> {{-- End wrapper --}}

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- Chart dark mode helper --}}
<script>
    window.hideChartLoader = function (chartId) {
        const canvas = document.getElementById(chartId);
        const loader = document.querySelector(`[data-chart-loader="${chartId}"]`);

        if (loader) {
            loader.style.display = 'none';
        }

        if (canvas) {
            canvas.style.display = 'block';
        }
    };

    window.applyChartTheme = function () {
        if (!window.Chart) {
            return;
        }

        const isDark = document.body.classList.contains('dark-mode');
        const textColor = isDark ? '#f0f0f0' : '#666666';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        const tooltipBg = isDark ? '#2a2a3e' : 'rgba(0, 0, 0, 0.8)';

        Chart.defaults.color = textColor;
        Chart.defaults.borderColor = gridColor;
        Chart.defaults.plugins.tooltip.backgroundColor = tooltipBg;
        Chart.defaults.plugins.tooltip.titleColor = '#f0f0f0';
        Chart.defaults.plugins.tooltip.bodyColor = '#f0f0f0';

        Object.values(Chart.instances || {}).forEach((chart) => {
            if (!chart) {
                return;
            }

            const options = chart.config.options || {};

            options.plugins = options.plugins || {};
            options.plugins.legend = options.plugins.legend || {};
            options.plugins.legend.labels = options.plugins.legend.labels || {};
            options.plugins.legend.labels.color = textColor;
            options.plugins.tooltip = options.plugins.tooltip || {};
            options.plugins.tooltip.backgroundColor = tooltipBg;
            options.plugins.tooltip.titleColor = '#f0f0f0';
            options.plugins.tooltip.bodyColor = '#f0f0f0';

            Object.values(options.scales || {}).forEach((scale) => {
                scale.ticks = scale.ticks || {};
                scale.grid = scale.grid || {};
                scale.ticks.color = textColor;
                scale.grid.color = gridColor;

                if (scale.title && scale.title.text) {
                    scale.title.color = textColor;
                }
            });

            chart.update('none');
        });
        
    };

    window.addEventListener('load', () => window.applyChartTheme());
</script>

{{-- Dark mode script --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('darkModeToggle');
        const toggleIcon = toggle ? toggle.querySelector('i') : null;
        const navbar = document.querySelector('.main-header');
        const body = document.body;
        const darkModeClass = 'dark-mode';

        const applyTheme = (theme) => {
            const isDark = theme === 'dark';
            body.classList.toggle(darkModeClass, isDark);
            navbar?.classList.toggle('navbar-dark', isDark);
            navbar?.classList.toggle('navbar-light', !isDark);
            navbar?.classList.toggle('navbar-white', !isDark);
            toggleIcon?.classList.toggle('fa-sun', isDark);
            toggleIcon?.classList.toggle('fa-moon', !isDark);
            window.applyChartTheme();
        };

        applyTheme(localStorage.getItem('theme') === 'dark' ? 'dark' : 'light');

        toggle?.addEventListener('click', function (event) {
            event.preventDefault();
            const theme = body.classList.contains(darkModeClass) ? 'light' : 'dark';
            localStorage.setItem('theme', theme);
            applyTheme(theme);
        });
    });
</script>

{{-- Create Task Ajax --}}
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
<script>
    window.applyChartTheme?.();
    setTimeout(() => window.applyChartTheme?.(), 100);
    setTimeout(() => window.applyChartTheme?.(), 500);
</script>
</body>
</html>
