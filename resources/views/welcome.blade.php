<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Homebound — Find Your Forever Friend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --green: #1D9E75;
            --green-light: #E1F5EE;
            --green-dark: #0F6E56;
            --amber: #BA7517;
            --amber-light: #FAEEDA;
            --coral: #993C1D;
            --coral-light: #FAECE7;
            --ink: #1a1a18;
            --muted: #6b6b66;
            --border: rgba(0, 0, 0, 0.08);
            --bg: #F5F4F0;
            --white: #ffffff;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--ink);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* ===== NAV ===== */
        .nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(245, 244, 240, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 0.5px solid var(--border);
            height: 60px;
            display: flex;
            align-items: center;
        }

        .nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-logo img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .nav-logo-text {
            font-family: 'Lora', serif;
            font-size: 17px;
            font-weight: 600;
            color: var(--ink);
        }

        .nav-links {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .nav-link {
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            transition: all 0.15s;
        }

        .nav-link:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--ink);
        }

        .nav-btn {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            background: var(--green);
            color: white;
            transition: background 0.15s;
            margin-left: 4px;
        }

        .nav-btn:hover {
            background: var(--green-dark);
        }

        .nav-btn-outline {
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            border: 0.5px solid rgba(0, 0, 0, 0.15);
            background: white;
            color: var(--ink);
            transition: background 0.15s;
        }

        .nav-btn-outline:hover {
            background: var(--green-light);
            color: var(--green-dark);
        }

        /* ===== HERO ===== */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 100px 24px 60px;
            position: relative;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            background: radial-gradient(ellipse 60% 60% at 70% 40%, #d4f0e6 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 20% 70%, #faeeda44 0%, transparent 60%);
        }

        .hero-inner {
            max-width: 1100px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--green-light);
            color: var(--green-dark);
            font-size: 12px;
            font-weight: 500;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 20px;
            border: 0.5px solid rgba(29, 158, 117, 0.2);
        }

        .hero-eyebrow-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--green);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.8);
            }
        }

        .hero-title {
            font-family: 'Lora', serif;
            font-size: clamp(36px, 5vw, 56px);
            font-weight: 600;
            line-height: 1.15;
            margin-bottom: 20px;
            color: var(--ink);
        }

        .hero-title em {
            font-style: italic;
            color: var(--green);
        }

        .hero-desc {
            font-size: 16px;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 440px;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 24px;
            border-radius: 10px;
            background: var(--green);
            color: white;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-primary:hover {
            background: var(--green-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(29, 158, 117, 0.3);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 24px;
            border-radius: 10px;
            background: white;
            color: var(--ink);
            font-size: 14px;
            font-weight: 500;
            border: 0.5px solid rgba(0, 0, 0, 0.15);
            transition: all 0.2s;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-secondary:hover {
            background: var(--green-light);
            color: var(--green-dark);
            border-color: var(--green);
        }

        .hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 40px;
        }

        .hero-stat-num {
            font-family: 'Lora', serif;
            font-size: 28px;
            font-weight: 600;
            color: var(--ink);
        }

        .hero-stat-label {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* Hero visual */
        .hero-visual {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto auto;
            gap: 12px;
        }

        .hero-card {
            background: white;
            border-radius: 16px;
            border: 0.5px solid var(--border);
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s;
        }

        .hero-card:hover {
            transform: translateY(-3px);
        }

        .hero-card-img {
            width: 100%;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 52px;
            overflow: hidden;
        }

        .hero-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-card-body {
            padding: 12px 14px;
        }

        .hero-card-name {
            font-family: 'Lora', serif;
            font-size: 15px;
            font-weight: 600;
        }

        .hero-card-breed {
            font-size: 11px;
            color: var(--muted);
            margin: 2px 0 8px;
        }

        .hero-card-tags {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .tag {
            font-size: 10px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .tag-green {
            background: var(--green-light);
            color: var(--green-dark);
        }

        .tag-amber {
            background: var(--amber-light);
            color: #854F0B;
        }

        .tag-coral {
            background: var(--coral-light);
            color: var(--coral);
        }

        .featured-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: var(--green);
            color: white;
            font-size: 10px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
            margin-bottom: 4px;
        }

        /* ===== HOW IT WORKS ===== */
        .section {
            padding: 80px 24px;
        }

        .section-inner {
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-label {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--green);
            margin-bottom: 10px;
        }

        .section-title {
            font-family: 'Lora', serif;
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 14px;
        }

        .section-sub {
            font-size: 15px;
            color: var(--muted);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 48px;
        }

        /* Steps */
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .step-card {
            background: white;
            border: 0.5px solid var(--border);
            border-radius: 16px;
            padding: 28px 24px;
            position: relative;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .step-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .step-num {
            font-family: 'Lora', serif;
            font-size: 48px;
            font-weight: 600;
            color: var(--green-light);
            line-height: 1;
            margin-bottom: 16px;
        }

        .step-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--green-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .step-title {
            font-family: 'Lora', serif;
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .step-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ===== ROLES ===== */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .role-card {
            border-radius: 16px;
            padding: 28px 24px;
            border: 0.5px solid var(--border);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .role-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .role-card.adopter {
            background: linear-gradient(135deg, #f0fdf8, #e6f9f0);
            border-color: rgba(29, 158, 117, 0.15);
        }

        .role-card.volunteer {
            background: linear-gradient(135deg, #fffdf5, #fef8e6);
            border-color: rgba(186, 117, 23, 0.15);
        }

        .role-card.admin {
            background: linear-gradient(135deg, #f5f5ff, #eeeeff);
            border-color: rgba(60, 52, 137, 0.15);
        }

        .role-icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 18px;
        }

        .adopter .role-icon-wrap {
            background: rgba(29, 158, 117, 0.12);
        }

        .volunteer .role-icon-wrap {
            background: rgba(186, 117, 23, 0.12);
        }

        .admin .role-icon-wrap {
            background: rgba(60, 52, 137, 0.12);
        }

        .role-title {
            font-family: 'Lora', serif;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .role-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .role-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .role-features li {
            font-size: 12px;
            color: var(--muted);
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .role-features li::before {
            content: '✓';
            color: var(--green);
            font-weight: 600;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ===== FEATURES ===== */
        .features-bg {
            background: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .feature-item {
            display: flex;
            gap: 16px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--green-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .feature-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .feature-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ===== WORKFLOW ===== */
        .workflow-steps {
            display: flex;
            align-items: flex-start;
            gap: 0;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .wf-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 100px;
            position: relative;
        }

        .wf-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            left: 50%;
            width: 100%;
            height: 1.5px;
            background: linear-gradient(to right, var(--green), #a8e6cc);
        }

        .wf-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--green-dark);
            position: relative;
            z-index: 1;
            background: white;
            box-shadow: 0 0 0 4px var(--green-light);
        }

        .wf-label {
            font-size: 11px;
            font-weight: 500;
            text-align: center;
            color: var(--muted);
            max-width: 80px;
            line-height: 1.3;
        }

        /* ===== CTA ===== */
        .cta-section {
            padding: 80px 24px;
            background: linear-gradient(135deg, #0F6E56 0%, #1D9E75 60%, #2bb88a 100%);
            text-align: center;
            color: white;
        }

        .cta-inner {
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-title {
            font-family: 'Lora', serif;
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 600;
            margin-bottom: 14px;
            line-height: 1.2;
        }

        .cta-desc {
            font-size: 15px;
            opacity: 0.85;
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .cta-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-white {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            border-radius: 10px;
            background: white;
            color: var(--green-dark);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-white:hover {
            background: var(--green-light);
            transform: translateY(-1px);
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--ink);
            color: rgba(255, 255, 255, 0.6);
            padding: 32px 24px;
            text-align: center;
            font-size: 13px;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
        }

        .footer a:hover {
            color: white;
        }

        /* ===== FAQ ===== */
        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 700px;
        }

        .faq-item {
            background: white;
            border: 0.5px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .faq-q {
            padding: 16px 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            transition: background 0.15s;
        }

        .faq-q:hover {
            background: var(--bg);
        }

        .faq-chevron {
            font-size: 12px;
            color: var(--muted);
            transition: transform 0.2s;
            flex-shrink: 0;
        }

        .faq-a {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            font-size: 13px;
            color: var(--muted);
            line-height: 1.7;
            transition: max-height 0.3s ease, padding 0.3s;
        }

        .faq-item.open .faq-a {
            max-height: 200px;
            padding: 0 20px 16px;
        }

        .faq-item.open .faq-chevron {
            transform: rotate(180deg);
        }

        /* ===== ANIMATIONS ===== */
        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero-inner {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .hero-visual {
                display: none;
            }

            .roles-grid {
                grid-template-columns: 1fr;
            }

            .nav-links .nav-link {
                display: none;
            }

            .hero-stats {
                gap: 20px;
            }
        }
    </style>
</head>

<body>

    {{-- ===== NAV ===== --}}
    <nav class="nav">
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <img src="{{ asset('assets/Homebound3.png') }}" alt="Homebound">
                <img src="{{ asset('assets/Homebound2.png') }}" alt="Homebound">
            </a>
            <div class="nav-links">
                <a href="#how-it-works" class="nav-link">How it works</a>
                <a href="#roles" class="nav-link">Who it's for</a>
                <a href="#faq" class="nav-link">FAQ</a>
                <a href="{{ route('login') }}" class="nav-btn-outline">Log in</a>
                <a href="{{ route('register') }}" class="nav-btn">Get started</a>
            </div>
        </div>
    </nav>

    {{-- ===== HERO ===== --}}
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-inner">
            <div>
                <div class="hero-eyebrow">
                    <div class="hero-eyebrow-dot"></div>
                    Pets available for adoption now
                </div>
                <h1 class="hero-title">
                    Find your <em>forever</em><br>companion here
                </h1>
                <p class="hero-desc">
                    Homebound connects loving families with shelter animals. Browse pets, apply online, and track your
                    adoption journey — all in one place.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('pets.index') }}" class="btn-primary">Browse pets →</a>
                    <a href="#how-it-works" class="btn-secondary">How it works</a>
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="hero-stat-num">100%</div>
                        <div class="hero-stat-label">Online process</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">3</div>
                        <div class="hero-stat-label">User roles</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">6</div>
                        <div class="hero-stat-label">Workflow stages</div>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                @if(isset($featuredPets) && $featuredPets->count() > 0)
                    @foreach($featuredPets as $pet)
                        @php
                            $primary = $pet->images->firstWhere('is_primary', true) ?? $pet->images->first();
                            $imgUrl = $primary ? (str_starts_with($primary->path, 'http') ? $primary->path : asset('storage/' . $primary->path)) : null;
                            $bg = ['dog' => '#E1F5EE', 'cat' => '#FBEAF0', 'rabbit' => '#E6F1FB', 'bird' => '#EAF3DE', 'hamster' => '#FFF3E0', 'other' => '#F5F4F0'][$pet->species] ?? '#F5F4F0';
                            $emoji = ['dog' => '🐕', 'cat' => '🐈', 'rabbit' => '🐇', 'bird' => '🐦', 'hamster' => '🐹', 'other' => '🐾'][$pet->species] ?? '🐾';
                        @endphp
                        <a href="{{ route('pets.show', $pet) }}" style="display:block;">
                            <div class="hero-card" style="height:100%;">
                                <div class="hero-card-img" style="background:{{ $bg }};">
                                    @if($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $pet->name }}">
                                    @else
                                        {{ $emoji }}
                                    @endif
                                </div>
                                <div class="hero-card-body">
                                    <div class="hero-card-name">{{ $pet->name }}</div>
                                    <div class="hero-card-breed">
                                        {{ $pet->breed ?? ucfirst($pet->species) }} · {{ floor($pet->age_months / 12) }} yrs
                                    </div>
                                    <div class="hero-card-tags">
                                        @if($pet->good_with_kids) <span class="tag tag-green">Kid-friendly</span> @endif
                                        @if($pet->hypoallergenic) <span class="tag tag-amber">Hypoallergenic</span> @endif
                                        @if($pet->is_senior) <span class="tag tag-coral">Senior</span> @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    {{-- Fallback placeholders --}}
                    @foreach(['🐕', '🐈', '🐇', '🐦'] as $emoji)
                        <div class="hero-card">
                            <div class="hero-card-img" style="background:#E1F5EE;font-size:52px;">{{ $emoji }}</div>
                            <div class="hero-card-body">
                                <div class="hero-card-name">Coming soon</div>
                                <div class="hero-card-breed">Check back later</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    {{-- ===== HOW IT WORKS ===== --}}
    <section class="section" id="how-it-works">
        <div class="section-inner">
            <div class="section-label">Step by step</div>
            <h2 class="section-title">How adoption works</h2>
            <p class="section-sub">From browsing to bringing your new friend home — here's the full journey.</p>

            <div class="steps-grid fade-up">
                <div class="step-card">
                    <div class="step-icon">🔍</div>
                    <div class="step-num">01</div>
                    <div class="step-title">Browse pets</div>
                    <div class="step-desc">Explore all available animals. Filter by species, size, and activity level.
                        Use Smart Match to find pets that fit your lifestyle.</div>
                </div>
                <div class="step-card">
                    <div class="step-icon">📋</div>
                    <div class="step-num">02</div>
                    <div class="step-title">Submit an application</div>
                    <div class="step-desc">Found the one? Submit an adoption application directly on the pet's profile.
                        Tell us about your home and lifestyle.</div>
                </div>
                <div class="step-card">
                    <div class="step-icon">🤝</div>
                    <div class="step-num">03</div>
                    <div class="step-title">Meet & greet</div>
                    <div class="step-desc">Once your application is reviewed, a shelter volunteer will schedule a meet &
                        greet session between you and the pet.</div>
                </div>
                <div class="step-card">
                    <div class="step-icon">🏠</div>
                    <div class="step-num">04</div>
                    <div class="step-title">Home visit</div>
                    <div class="step-desc">A shelter staff member visits your home to ensure it's a safe and suitable
                        environment for the animal.</div>
                </div>
                <div class="step-card">
                    <div class="step-icon">✅</div>
                    <div class="step-num">05</div>
                    <div class="step-title">Get approved</div>
                    <div class="step-desc">After a successful home visit, your application is approved. You'll be
                        notified immediately through the system.</div>
                </div>
                <div class="step-card">
                    <div class="step-icon">🎉</div>
                    <div class="step-num">06</div>
                    <div class="step-title">Welcome home!</div>
                    <div class="step-desc">Sign the adoption contract and bring your new companion home. The adoption is
                        marked complete in the system.</div>
                </div>
            </div>

            <div
                style="margin-top:48px;padding:28px;background:white;border-radius:16px;border:0.5px solid var(--border);">
                <div
                    style="font-size:12px;font-weight:500;color:var(--muted);margin-bottom:20px;text-transform:uppercase;letter-spacing:0.06em;">
                    Application workflow stages</div>
                <div class="workflow-steps">
                    @foreach(['Pending', 'Under Review', 'Meet & Greet', 'Home Check', 'Approved', 'Completed'] as $i => $stage)
                        <div class="wf-step">
                            <div class="wf-dot">{{ $i + 1 }}</div>
                            <div class="wf-label">{{ $stage }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===== ROLES ===== --}}
    <section class="section features-bg" id="roles">
        <div class="section-inner">
            <div class="section-label">Who it's for</div>
            <h2 class="section-title">Three roles, one platform</h2>
            <p class="section-sub">Homebound serves everyone involved in the adoption process — each with their own
                tailored experience.</p>

            <div class="roles-grid fade-up">
                <div class="role-card adopter">
                    <div class="role-icon-wrap">🏠</div>
                    <div class="role-title">Adopter</div>
                    <div class="role-desc">Individuals and families looking to give a pet a forever home.</div>
                    <ul class="role-features">
                        <li>Browse all available pets</li>
                        <li>Use Smart Match to find your fit</li>
                        <li>Submit adoption applications</li>
                        <li>Track application status in real time</li>
                        <li>Receive notifications on updates</li>
                    </ul>
                </div>
                <div class="role-card volunteer">
                    <div class="role-icon-wrap">🤝</div>
                    <div class="role-title">Volunteer</div>
                    <div class="role-desc">Shelter helpers who assist with scheduling and animal care updates.</div>
                    <ul class="role-features">
                        <li>View all applications (read-only)</li>
                        <li>Schedule meet & greet sessions</li>
                        <li>Mark appointments as completed</li>
                        <li>Update pet stories and notes</li>
                        <li>Coordinate home visit schedules</li>
                    </ul>
                </div>
                <div class="role-card admin">
                    <div class="role-icon-wrap">⚙️</div>
                    <div class="role-title">Admin</div>
                    <div class="role-desc">Shelter staff with full control over pets, applications, and records.</div>
                    <ul class="role-features">
                        <li>Add, edit and manage pet listings</li>
                        <li>Advance applications through stages</li>
                        <li>Manage medical records & timelines</li>
                        <li>Approve or reject applications</li>
                        <li>Access reports and dashboard stats</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FEATURES ===== --}}
    <section class="section" id="features">
        <div class="section-inner">
            <div class="section-label">Features</div>
            <h2 class="section-title">Everything you need</h2>
            <p class="section-sub">Built to make pet adoption simpler, faster, and more transparent for everyone
                involved.</p>

            <div class="features-grid fade-up">
                <div class="feature-item">
                    <div class="feature-icon">🔮</div>
                    <div>
                        <div class="feature-title">Smart Match engine</div>
                        <div class="feature-desc">Set your lifestyle preferences — activity level, size, species — and
                            get a ranked list of pets scored by compatibility.</div>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <div>
                        <div class="feature-title">Real-time application tracking</div>
                        <div class="feature-desc">Every application moves through a clear 6-stage workflow. Adopters see
                            their status update instantly.</div>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🏥</div>
                    <div>
                        <div class="feature-title">Medical timeline</div>
                        <div class="feature-desc">Each pet has a full medical history — vaccinations, check-ups,
                            medications — logged and visible to shelter staff.</div>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🖼️</div>
                    <div>
                        <div class="feature-title">Photo gallery</div>
                        <div class="feature-desc">Each pet profile supports up to 5 photos with a clickable lightbox
                            gallery so adopters can see every angle.</div>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔔</div>
                    <div>
                        <div class="feature-title">Notifications</div>
                        <div class="feature-desc">Admins and adopters receive in-app notifications when applications are
                            updated or appointments are scheduled.</div>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔒</div>
                    <div>
                        <div class="feature-title">Role-based access</div>
                        <div class="feature-desc">Three distinct roles — Admin, Volunteer, Adopter — each with precisely
                            scoped permissions and views.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FAQ ===== --}}
    <section class="section features-bg" id="faq">
        <div class="section-inner">
            <div class="section-label">FAQ</div>
            <h2 class="section-title">Common questions</h2>
            <p class="section-sub">Everything you need to know before getting started.</p>

            <div class="faq-list fade-up">
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">Do I need an account to browse pets?<span
                            class="faq-chevron">▼</span></div>
                    <div class="faq-a">No — anyone can browse the pet listings and view individual pet profiles without
                        logging in. You only need to create an account when you want to submit an adoption application.
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">How long does the adoption process take?<span
                            class="faq-chevron">▼</span></div>
                    <div class="faq-a">The timeline varies depending on the pet and your situation. After submitting
                        your application, the shelter team will review it and reach out to schedule a meet & greet. The
                        full process typically takes 1 to 3 weeks.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">What is Smart Match?<span class="faq-chevron">▼</span>
                    </div>
                    <div class="faq-a">Smart Match is our matching engine. You set your preferences — preferred species,
                        activity level, size, whether you have kids at home — and the system scores every available pet
                        and ranks them by compatibility.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">Can I apply for more than one pet?<span
                            class="faq-chevron">▼</span></div>
                    <div class="faq-a">Yes, you can apply for multiple pets. However, once one of your applications is
                        approved, the others will be automatically closed to ensure fair access for all adopters.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">How do I become a volunteer?<span
                            class="faq-chevron">▼</span></div>
                    <div class="faq-a">Register for an account and select "Volunteer" as your role during sign-up.
                        Volunteer accounts have access to the scheduling system and can view all applications in
                        read-only mode.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">What happens after my application is approved?<span
                            class="faq-chevron">▼</span></div>
                    <div class="faq-a">After approval, the shelter team will contact you to arrange signing the adoption
                        contract and coordinate the handover of your new companion. The application will be marked as
                        "Completed" once everything is finalized.</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA ===== --}}
    <section class="cta-section">
        <div class="cta-inner">
            <h2 class="cta-title">Ready to find your forever friend?</h2>
            <p class="cta-desc">Browse our available pets and start your adoption journey today. A companion is waiting
                for you.</p>
            <div class="cta-actions">
                <a href="{{ route('pets.index') }}" class="btn-white">Browse pets →</a>
                <a href="{{ route('register') }}" class="btn-ghost">Create an account</a>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="footer">
        <div style="margin-bottom:8px;">
            <strong style="color:rgba(255,255,255,0.9);">Homebound</strong> — Pet Adoption Management System
        </div>
        <div>Built with Laravel · Connecting pets with loving families</div>
    </footer>

    <script>
        function toggleFaq(el) {
            const item = el.closest('.faq-item');
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
            if (!isOpen) item.classList.add('open');
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>

</html>