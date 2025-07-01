<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Task Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: url('https://images.unsplash.com/photo-1542744173-8e7e53415bb0') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #f1f1f1;
    }

    .login-container, .register-container {
        margin-top: 100px;
    }

    .login-box, .register-box {
        background: rgba(0, 0, 0, 0.6); /* Dark glass effect */
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }

    .form-control {
        background-color: rgba(255, 255, 255, 0.08);
        border: none;
        color: #ffffff;
    }

    .form-control::placeholder {
        color: #cccccc;
    }

    .form-control:focus {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        box-shadow: none;
        border-color: #0d6efd;
    }

    .form-check-label,
    a {
        color: #ccc;
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .alert {
        background-color: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
    }
</style>

</head>
<body>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="login-box">
                <h3 class="text-center mb-4">Register an Account</h3>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autofocus>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input id="email" type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
