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
            <img src="{{ asset('assets/Homebound.png') }}" alt="Homebound"
                style="height:100px;width:auto;object-fit:contain;">
            <div class="auth-tagline">Finding forever homes, one paw at a time.</div>
        </div>

        <div class="auth-title">Welcome back</div>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
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

        <div class="auth-footer">
            Don't have an account?
            <a href="{{ route('register') }}">Create one</a>
        </div>
    </div>
</body>

</html>