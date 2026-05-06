<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homebound — Pending Approval</title>
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

        .card {
            background: #fff;
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 48px 44px;
            width: 100%;
            max-width: 440px;
            text-align: center;
        }

        .icon {
            width: 64px;
            height: 64px;
            background: #FAEEDA;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 24px;
        }

        .title {
            font-family: 'Lora', serif;
            font-size: 22px;
            font-weight: 600;
            color: #1a1a18;
            margin-bottom: 10px;
        }

        .desc {
            font-size: 14px;
            color: #666;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FAEEDA;
            color: #854F0B;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 20px;
            margin-bottom: 28px;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #BA7517;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .info-box {
            background: #F5F4F0;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 13px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 28px;
            text-align: left;
        }

        .info-box strong {
            color: #1a1a18;
        }

        .btn-logout {
            width: 100%;
            padding: 11px;
            background: #fff;
            color: #1a1a18;
            border: 0.5px solid rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-logout:hover {
            background: #f5f4f0;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 32px;
        }

        .logo img {
            height: 44px;
            width: auto;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="logo">
            <img src="{{ asset('assets/Homebound.png') }}" alt="Homebound">
        </div>

        <div class="icon">⏳</div>
        <div class="title">Awaiting approval</div>

        <div class="badge">
            <div class="badge-dot"></div>
            Volunteer account — pending
        </div>

        <p class="desc">
            Your volunteer account has been created successfully. A shelter admin needs to approve your account before
            you can access the system.
        </p>

        <div class="info-box">
            <strong>What happens next?</strong><br>
            The shelter admin will review your registration and approve your account. This usually happens within 24
            hours. You'll be able to log in and access the system once approved.
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Log out</button>
        </form>
    </div>
</body>

</html>