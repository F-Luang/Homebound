<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homebound — Create account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
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
            border: 0.5px solid rgba(0,0,0,0.1);
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
            height: 80px;
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
            margin-bottom: 6px;
            text-align: center;
        }
        .auth-subtitle {
            font-size: 13px;
            color: #888;
            text-align: center;
            margin-bottom: 24px;
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
            border: 0.5px solid rgba(0,0,0,0.2);
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
            box-shadow: 0 0 0 3px rgba(29,158,117,0.1);
        }
        .form-error {
            font-size: 11px;
            color: #993C1D;
            margin-top: 3px;
        }
        .role-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 16px;
        }
        .role-option { position: relative; }
        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .role-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 12px 8px;
            border: 0.5px solid rgba(0,0,0,0.15);
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            color: #555;
            transition: all 0.15s;
            text-align: center;
        }
        .role-label:hover { border-color: #1D9E75; background: #F5FDF9; }
        .role-option input[type="radio"]:checked + .role-label {
            border-color: #1D9E75;
            background: #E1F5EE;
            color: #0F6E56;
        }
        .role-icon { font-size: 18px; }
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
            margin-top: 8px;
        }
        .btn-primary:hover { background: #0F6E56; }
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
        .auth-footer a:hover { text-decoration: underline; }
        .form-hint {
            font-size: 11px;
            color: #888;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <img src="{{ asset('assets/Homebound.png') }}" alt="Homebound">
            <div class="auth-tagline">Finding forever homes, one paw at a time.</div>
        </div>

        <div class="auth-title">Create your account</div>
        <div class="auth-subtitle">Join Homebound and find your perfect companion.</div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Full name</label>
                <input class="form-input" type="text" id="name" name="name"
                    value="{{ old('name') }}" required autofocus autocomplete="name"
                    placeholder="e.g. Juan dela Cruz">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email address</label>
                <input class="form-input" type="email" id="email" name="email"
                    value="{{ old('email') }}" required autocomplete="username"
                    placeholder="you@example.com">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" id="password" name="password"
                    required autocomplete="new-password" placeholder="Min. 8 characters">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm password</label>
                <input class="form-input" type="password" id="password_confirmation"
                    name="password_confirmation" required autocomplete="new-password"
                    placeholder="Re-enter your password">
            </div>

            <div class="form-group">
                <label class="form-label">I am registering as</label>
                <div class="role-grid">
                    <div class="role-option">
                        <input type="radio" id="role-adopter" name="role" value="adopter"
                            {{ old('role', 'adopter') === 'adopter' ? 'checked' : '' }}>
                        <label class="role-label" for="role-adopter">
                            <span class="role-icon">🏠</span>
                            Adopter
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" id="role-volunteer" name="role" value="volunteer"
                            {{ old('role') === 'volunteer' ? 'checked' : '' }}>
                        <label class="role-label" for="role-volunteer">
                            <span class="role-icon">🤝</span>
                            Volunteer
                        </label>
                    </div>
                </div>
                <div class="form-hint">Admin accounts are created by shelter staff only.</div>
                @error('role') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn-primary">Create account</button>
        </form>

        <div class="auth-footer">
            Already have an account?
            <a href="{{ route('login') }}">Log in</a>
        </div>
    </div>
</body>
</html>