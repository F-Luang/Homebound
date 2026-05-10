<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homebound — Verify your email</title>
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
            text-align: center;
        }

        .icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .auth-title {
            font-family: 'Lora', serif;
            font-size: 20px;
            font-weight: 600;
            color: #1a1a18;
            margin-bottom: 8px;
        }

        .auth-sub {
            font-size: 13px;
            color: #888;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .code-inputs {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-bottom: 24px;
        }

        .code-digit {
            width: 48px;
            height: 56px;
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            border: 1.5px solid rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            color: #1a1a18;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .code-digit:focus {
            outline: none;
            border-color: #1D9E75;
            box-shadow: 0 0 0 3px rgba(29, 158, 117, 0.1);
        }

        /* Hidden actual input */
        #code {
            display: none;
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

        .form-error {
            font-size: 12px;
            color: #993C1D;
            background: #FAECE7;
            border: 0.5px solid #F0997B;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }

        .alert-success {
            font-size: 12px;
            color: #0F6E56;
            background: #E1F5EE;
            border: 0.5px solid #1D9E75;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }

        .resend-form {
            margin-top: 4px;
        }

        .btn-link {
            background: none;
            border: none;
            color: #1D9E75;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .timer {
            font-size: 12px;
            color: #aaa;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="icon">📬</div>
        <div class="auth-title">Check your email</div>
        <div class="auth-sub">
            We sent a 6-digit verification code to<br>
            <strong>{{ Auth::user()->email }}</strong><br>
            It expires in 15 minutes.
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="form-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('verification.code.verify') }}">
            @csrf
            <input type="hidden" name="code" id="code">

            <div class="code-inputs">
                @for($i = 0; $i < 6; $i++)
                    <input class="code-digit" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                        id="digit-{{ $i }}" autocomplete="off">
                @endfor
            </div>

            <button type="submit" class="btn-primary">Verify email</button>
        </form>

        <div style="font-size:13px;color:#888;">
            Didn't receive it?
            <form method="POST" action="{{ route('verification.code.resend') }}" class="resend-form"
                style="display:inline;">
                @csrf
                <button type="submit" class="btn-link">Resend code</button>
            </form>
        </div>

        <div class="timer" id="timer"></div>
    </div>

    <script>
        // Auto-advance between digit inputs
        const digits = document.querySelectorAll('.code-digit');
        const hiddenInput = document.getElementById('code');

        digits.forEach((input, i) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/\D/g, '').slice(-1);
                if (input.value && i < digits.length - 1) digits[i + 1].focus();
                syncCode();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && i > 0) digits[i - 1].focus();
                if (e.key === 'ArrowLeft' && i > 0) digits[i - 1].focus();
                if (e.key === 'ArrowRight' && i < digits.length - 1) digits[i + 1].focus();
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                paste.split('').forEach((char, j) => { if (digits[j]) digits[j].value = char; });
                syncCode();
                if (digits[paste.length - 1]) digits[paste.length - 1].focus();
            });
        });

        function syncCode() {
            hiddenInput.value = Array.from(digits).map(d => d.value).join('');
        }

        // 15-minute countdown timer
        let seconds = 15 * 60;
        const timerEl = document.getElementById('timer');
        const interval = setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                clearInterval(interval);
                timerEl.textContent = 'Code expired. Please request a new one.';
                timerEl.style.color = '#993C1D';
                return;
            }
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            timerEl.textContent = `Code expires in ${m}:${s.toString().padStart(2, '0')}`;
        }, 1000);

        digits[0].focus();
    </script>
</body>

</html>