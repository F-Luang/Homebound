<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Homebound') }} — @yield('title', 'Pet Adoption')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=DM+Sans:wght@400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --green: #1D9E75;
            --green-light: #E1F5EE;
            --green-dark: #0F6E56;
            --amber: #BA7517;
            --amber-light: #FAEEDA;
            --blue-light: #E6F1FB;
            --blue: #185FA5;
            --coral-light: #FAECE7;
            --coral: #993C1D;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F5F4F0;
            color: #1a1a18;
            margin: 0;
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .nav {
            background: #fff;
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 32px;
            height: 72px;
        }

        .nav-logo {
            font-family: 'Lora', serif;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-logo-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--green);
        }

        .nav-links {
            display: flex;
            gap: 4px;
            flex: 1;
        }

        .nav-link {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            transition: background 0.1s, color 0.1s;
        }

        .nav-link:hover {
            background: #f0f0ec;
            color: #1a1a18;
        }

        .nav-link.active {
            background: var(--green-light);
            color: var(--green-dark);
        }

        .nav-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-left: auto;
        }

        .nav-user {
            font-size: 13px;
            color: #666;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            border: 0.5px solid rgba(0, 0, 0, 0.15);
            background: #fff;
            color: #1a1a18;
            cursor: pointer;
            transition: background 0.1s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn:hover {
            background: #f5f4f0;
        }

        .btn-primary {
            background: var(--green);
            color: #fff;
            border-color: var(--green-dark);
        }

        .btn-primary:hover {
            background: var(--green-dark);
        }

        .btn-danger {
            background: #fff;
            color: var(--coral);
            border-color: var(--coral);
        }

        .btn-danger:hover {
            background: var(--coral-light);
        }

        .btn-sm {
            padding: 4px 12px;
            font-size: 12px;
        }

        .page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        .page-header {
            margin-bottom: 28px;
        }

        .page-title {
            font-family: 'Lora', serif;
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .page-sub {
            font-size: 14px;
            color: #666;
        }

        .flash {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .flash-success {
            background: var(--green-light);
            color: var(--green-dark);
            border: 0.5px solid #5DCAA5;
        }

        .flash-error {
            background: var(--coral-light);
            color: var(--coral);
            border: 0.5px solid #F0997B;
        }

        .card {
            background: #fff;
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 20px 24px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            text-align: left;
            font-size: 11px;
            font-weight: 500;
            color: #888;
            padding: 0 14px 10px;
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.08);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        td {
            padding: 11px 14px;
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.06);
            vertical-align: middle;
            color: #1a1a18;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fafaf8;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 500;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .badge::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .badge-pending {
            background: var(--amber-light);
            color: #854F0B;
        }

        .badge-review {
            background: var(--blue-light);
            color: var(--blue);
        }

        .badge-under-review {
            background: var(--blue-light);
            color: var(--blue);
        }

        .badge-meet-greet {
            background: #EEEDFE;
            color: #3C3489;
        }

        .badge-home-check {
            background: var(--amber-light);
            color: #854F0B;
        }

        .badge-approved {
            background: #EAF3DE;
            color: #3B6D11;
        }

        .badge-rejected {
            background: var(--coral-light);
            color: var(--coral);
        }

        .badge-completed {
            background: #EAF3DE;
            color: #3B6D11;
        }

        .badge-available {
            background: var(--green-light);
            color: var(--green-dark);
        }

        .badge-adopted {
            background: #EEEDFE;
            color: #3C3489;
        }

        .badge-pending-adoption {
            background: var(--amber-light);
            color: #854F0B;
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
            padding: 9px 12px;
            border-radius: 8px;
            border: 0.5px solid rgba(0, 0, 0, 0.2);
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            background: #fff;
            color: #1a1a18;
            width: 100%;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(29, 158, 117, 0.1);
        }

        .form-error {
            font-size: 11px;
            color: var(--coral);
            margin-top: 3px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        select.form-input {
            appearance: none;
            cursor: pointer;
        }

        textarea.form-input {
            resize: vertical;
            min-height: 90px;
        }

        input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--green);
        }

        .steps {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            flex: 1;
            position: relative;
        }

        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 13px;
            left: 50%;
            width: 100%;
            height: 1px;
            background: rgba(0, 0, 0, 0.1);
        }

        .step.done::after {
            background: var(--green);
        }

        .step-circle {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 1.5px solid rgba(0, 0, 0, 0.15);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 500;
            color: #888;
            position: relative;
            z-index: 1;
        }

        .step.done .step-circle {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
        }

        .step.current .step-circle {
            border-color: var(--green);
            color: var(--green);
        }

        .step-label {
            font-size: 10px;
            color: #888;
            text-align: center;
            max-width: 70px;
        }

        .step.current .step-label {
            color: var(--green);
            font-weight: 500;
        }

        .pet-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .tag {
            display: inline-block;
            font-size: 11px;
            font-weight: 500;
            padding: 2px 9px;
            border-radius: 20px;
        }

        .tag-teal {
            background: var(--green-light);
            color: var(--green-dark);
        }

        .tag-amber {
            background: var(--amber-light);
            color: #854F0B;
        }

        .tag-blue {
            background: var(--blue-light);
            color: var(--blue);
        }

        .tag-coral {
            background: var(--coral-light);
            color: var(--coral);
        }

        .timeline {
            display: flex;
            flex-direction: column;
        }

        .tl-item {
            display: flex;
            gap: 14px;
            padding-bottom: 20px;
            position: relative;
        }

        .tl-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 24px;
            width: 1px;
            bottom: 0;
            background: rgba(0, 0, 0, 0.08);
        }

        .tl-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 500;
            margin-top: 2px;
        }

        .tl-dot-green {
            background: var(--green-light);
            color: var(--green);
        }

        .tl-dot-blue {
            background: var(--blue-light);
            color: var(--blue);
        }

        .tl-dot-amber {
            background: var(--amber-light);
            color: var(--amber);
        }

        .tl-date {
            font-size: 11px;
            color: #888;
        }

        .tl-title {
            font-size: 13px;
            font-weight: 500;
        }

        .tl-desc {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }

        .divider {
            border: none;
            border-top: 0.5px solid rgba(0, 0, 0, 0.08);
            margin: 20px 0;
        }

        .text-muted {
            color: #888;
            font-size: 13px;
        }

        .mt-16 {
            margin-top: 16px;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .gap-8 {
            gap: 8px;
        }

        .gap-12 {
            gap: 12px;
        }

        .two-col {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 20px;
        }

        .three-col {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }
    </style>
</head>

<body>
    <nav class="nav">
        <div class="nav-inner">
            <a href="{{ route('dashboard') }}" class="nav-logo">
                <img src="{{ asset('assets/Homebound.png') }}" alt="Homebound"
                    style="height:70px;width:auto;object-fit:contain;">
                <span style="font-family:'Lora',serif;font-size:18px;font-weight:600;color:#1a1a18;">Homebound</span>
            </a>
            <div class="nav-links">
                <a href="{{ route('pets.index') }}"
                    class="nav-link {{ request()->routeIs('pets.*') ? 'active' : '' }}">Pets</a>
                @auth
                    <a href="{{ route('applications.index') }}"
                        class="nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }}">Applications</a>
                    @if(auth()->user()->isAdmin() || auth()->user()->isVolunteer())
                        <a href="{{ route('meet-greets.index') }}"
                            class="nav-link {{ request()->routeIs('meet-greets.*') ? 'active' : '' }}">Scheduling</a>
                    @endif
                @endauth
            </div>
            <div class="nav-actions">
                @auth
                    <span class="nav-user">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="page" style="padding-bottom:0;">
                <div class="flash flash-success">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="page" style="padding-bottom:0;">
                <div class="flash flash-error">{{ session('error') }}</div>
            </div>
        @endif
        @yield('content')
    </main>
</body>

</html>