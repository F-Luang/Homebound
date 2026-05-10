<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homebound — Log in</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=DM+Sans:wght@400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F5F4F0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-card {
            background: #fff;
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 40px 44px;
            width: 100%;
            max-width: 420px;
        }

        .auth-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 32px;
            gap: 12px;
        }

        .auth-logo img {
            height: 100px;
            width: auto;
            object-fit: contain;
        }

        .auth-tagline {
            font-size: 13px;
            color: #888;
            text-align: center;
        }

        .auth-title {
            font-family: 'Lora', serif;
            font-size: 20px;
            font-weight: 600;
            color: #1a1a18;
            margin-bottom: 24px;
            text-align: center;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 16px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 500;
            color: #555;
        }

        .form-input {
            padding: 10px 12px;
            border-radius: 8px;
            border: 0.5px solid rgba(0, 0, 0, 0.2);
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            background: #fff;
            color: #1a1a18;
            width: 100%;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-input:focus {
            outline: none;
            border-color: #1D9E75;
            box-shadow: 0 0 0 3px rgba(29, 158, 117, 0.1);
        }

        .form-error {
            font-size: 11px;
            color: #993C1D;
            margin-top: 3px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #555;
            margin-bottom: 20px;
        }

        input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #1D9E75;
        }

        .btn-primary {
            width: 100%;
            padding: 11px;
            background: #1D9E75;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.15s;
            margin-bottom: 16px;
        }

        .btn-primary:hover {
            background: #0F6E56;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            color: #ccc;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 0.5px;
            background: rgba(0, 0, 0, 0.1);
        }

        .btn-google {
            width: 100%;
            padding: 11px;
            background: #fff;
            color: #1a1a18;
            border: 0.5px solid rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-google:hover {
            background: #f8f8f8;
            border-color: rgba(0, 0, 0, 0.3);
        }

        .google-icon {
            width: 18px;
            height: 18px;
        }

        .auth-footer {
            text-align: center;
            font-size: 13px;
            color: #888;
        }

        .auth-footer a {
            color: #1D9E75;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .forgot-link {
            display: block;
            text-align: right;
            font-size: 12px;
            color: #1D9E75;
            text-decoration: none;
            margin-top: -10px;
            margin-bottom: 20px;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .alert-error {
            background: #FAECE7;
            border: 0.5px solid #F0997B;
            color: #993C1D;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="auth-logo">
            <img src="{{ asset('assets/Homebound.png') }}" alt="Homebound">
            <div class="auth-tagline">Finding forever homes, one paw at a time.</div>
        </div>

        <div class="auth-title">Welcome back</div>

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email address</label>
                <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" required
                    autofocus autocomplete="username" placeholder="you@example.com">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" id="password" name="password" required
                    autocomplete="current-password" placeholder="••••••••">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot your password?</a>
            @endif

            <div class="form-check">
                <input type="checkbox" id="remember_me" name="remember">
                <label for="remember_me">Remember me</label>
            </div>

            <button type="submit" class="btn-primary">Log in</button>
        </form>

        <div class="divider">or</div>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg class="google-icon" viewBox="0 0 24 24">
                <path fill="#4285F4"
                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="#34A853"
                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05"
                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                <path fill="#EA4335"
                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
            </svg>
            Continue with Google
        </a>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>
    </div>
</body>

</html>