<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Task Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #121212;
            color: #f1f1f1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #1f1f1f;
        }

        .navbar-brand {
            color: #ffffff;
        }

        .navbar-brand:hover {
            color: #0d6efd;
        }

        .btn-custom {
            min-width: 150px;
        }

        .btn-outline-primary {
            color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: #fff;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .hero {
            padding: 100px 0;
            text-align: center;
        }

        .lead {
            color: #cccccc;
        }

        footer {
            background-color: #1f1f1f;
            color: #999;
        }

        a {
            color: #0d6efd;
        }

        a:hover {
            color: #0b5ed7;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">TaskManager</a>
        <div class="d-flex">
            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2 btn-custom">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-custom">Register</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1 class="display-4 fw-bold text-white">Welcome to Task Manager</h1>
        <p class="lead">Organize your tasks. Boost your productivity.</p>
        <a href="{{ route('login') }}" class="btn btn-success btn-lg mt-3">Get Started</a>
    </div>
</section>

<!-- Footer -->
<footer class="text-center py-3 mt-5">
    &copy; {{ date('Y') }} Task Manager. All rights reserved.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
