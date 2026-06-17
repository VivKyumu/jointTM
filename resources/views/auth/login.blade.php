<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Task Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6/css/all.min.css">

    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .22), transparent 32%),
                linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-shell {
            min-height: 100vh;
        }

        .auth-panel {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 28px 70px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            max-width: 1120px;
            margin: 0 auto;
        }

        .auth-side {
            background: linear-gradient(160deg, rgba(15, 118, 110, .96), rgba(37, 99, 235, .94));
            color: #ffffff;
            min-height: 520px;
        }

        .auth-side .icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.16);
            font-size: 2rem;
        }

        .brand-mark {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 14px 28px rgba(37, 99, 235, .25);
            margin: 0 auto 18px;
            font-size: 1.8rem;
        }

        .role-option {
            position: relative;
        }

        .role-option input {
            position: absolute;
            opacity: 0;
        }

        .role-option label {
            border: 1px solid #d6dce6;
            border-radius: 8px;
            padding: 14px;
            cursor: pointer;
            width: 100%;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .role-option input:checked + label {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.16);
            background: #f5f8ff;
        }

        .form-control {
            min-height: 46px;
            border-color: #d8dee9;
        }

        .btn-primary {
            min-height: 46px;
            background: #2563eb;
            border-color: #2563eb;
            font-weight: 700;
            box-shadow: 0 10px 18px rgba(37, 99, 235, .22);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .password-toggle {
            border-color: #ced4da;
            min-width: 52px;
        }

        .input-group-text {
            background: #f8fafc;
            color: #2563eb;
            border-color: #d8dee9;
            min-width: 46px;
            justify-content: center;
        }

        .auth-footer {
            border-top: 1px solid #eef2f7;
            color: #64748b;
        }
    </style>
</head>
<body>
<main class="container auth-shell d-flex align-items-center py-5">
    <div class="auth-panel row g-0 w-100">
        <section class="col-lg-5 auth-side d-none d-lg-flex flex-column justify-content-between p-5">
            <div>
                <div class="icon-wrap mb-4">
                    <i class="fas fa-list-check"></i>
                </div>
                <h1 class="h2 fw-bold mb-3">Task Manager</h1>
                <p class="lead mb-0">Organize work, assign tasks, monitor progress, and keep every update visible.</p>
            </div>
            <div class="small opacity-75">
                Admins manage the whole system. Users focus on their own tasks and deadlines.
            </div>
        </section>

        <section class="col-lg-7 p-4 p-md-5">
            <div class="mx-auto" style="max-width: 520px;">
                <div class="text-center mb-4">
                    <div class="brand-mark">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <h2 class="fw-bold mb-1">Task Manager System</h2>
                    <p class="text-muted mb-0">Manage tasks. Track progress. Drive productivity.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-info">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Login as</label>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="role-option">
                                    <input type="radio" name="login_as" id="login_as_admin" value="admin" @checked(old('login_as', 'admin') === 'admin')>
                                    <label for="login_as_admin">
                                        <span class="d-flex align-items-center gap-2 fw-semibold">
                                            <i class="fas fa-user-shield text-primary"></i> Admin
                                        </span>
                                        <small class="text-muted d-block mt-1">Manage users, tasks, settings, and reports.</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="role-option">
                                    <input type="radio" name="login_as" id="login_as_user" value="user" @checked(old('login_as') === 'user')>
                                    <label for="login_as_user">
                                        <span class="d-flex align-items-center gap-2 fw-semibold">
                                            <i class="fas fa-user text-success"></i> User
                                        </span>
                                        <small class="text-muted d-block mt-1">View and manage your assigned tasks.</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('login_as')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus autocomplete="username">
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required autocomplete="current-password">
                            <button class="btn btn-outline-secondary password-toggle" type="button" id="togglePassword"
                                    aria-label="Show password" title="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none fw-semibold">
                                <i class="fas fa-key me-1"></i> Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-right-to-bracket me-1"></i> Log in
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('register') }}" class="text-decoration-none">Create a new user account</a>
                </div>

                <div class="auth-footer text-center small mt-4 pt-3">
                    &copy; {{ date('Y') }} Task Manager | Built by Vivian Mbachi
                </div>
            </div>
        </section>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const toggleIcon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';
            toggleIcon.classList.toggle('fa-eye', !isHidden);
            toggleIcon.classList.toggle('fa-eye-slash', isHidden);
            togglePassword.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            togglePassword.setAttribute('title', isHidden ? 'Hide password' : 'Show password');
        });
    });
</script>
</body>
</html>
