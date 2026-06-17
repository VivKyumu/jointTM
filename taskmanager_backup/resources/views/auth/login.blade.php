<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-header i {
            font-size: 48px;
            color: #4e73df;
            margin-bottom: 10px;
        }

        .login-header h2 {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .btn-login {
            background-color: #4e73df;
            color: white;
            border-radius: 8px;
        }

        .btn-login:hover {
            background-color: #375ac2;
            color: white;
        }

        .password-toggle {
            border-color: #ced4da;
            min-width: 48px;
        }
    </style>
</head>
<body>

    <div class="card login-card p-4">
        <div class="login-header">
            <i class="fas fa-tasks"></i>
            <h2>Task Manager</h2>
            <p class="text-muted">Sign in to continue</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="password">Password</label>
                <div class="input-group">
                    <input id="password" type="password" name="password" class="form-control" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary password-toggle" type="button" id="togglePassword"
                                aria-label="Show password" title="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group form-check mt-3">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>

            <button type="submit" class="btn btn-login btn-block mt-3">
                <i class="fas fa-sign-in-alt"></i> Log in
            </button>

            @if (Route::has('password.request'))
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
            @endif
        </form>
    </div>

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
