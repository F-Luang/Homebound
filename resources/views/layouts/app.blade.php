<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <script>
        // Apply theme immediately to prevent flash
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            const fontSize = localStorage.getItem('fontSize') || 'normal';
            document.documentElement.setAttribute('data-theme', theme);
            const sizes = { small: '13px', normal: '15px', large: '17px' };
            document.documentElement.style.fontSize = sizes[fontSize] || '15px';
        })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Homebound') }} — @yield('title', 'Pet Adoption')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=DM+Sans:wght@400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    {{-- ===== MOBILE OVERLAY ===== --}}
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    {{-- ===== SIDEBAR ===== --}}
    @auth
        @php
            $user = auth()->user();
            if ($user->role === 'admin') {
                $unreadCount = \App\Models\Application::where('updated_at', '>=', now()->subDay())->count();
            } else {
                $unreadCount = \App\Models\Application::where('user_id', $user->id)
                    ->where('updated_at', '>=', $user->last_notif_seen_at ?? now()->subYear())
                    ->where('status', '!=', 'pending')
                    ->count();
            }
        @endphp

        <aside class="sidebar" id="sidebar" style="view-transition-name:sidebar;">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="sb-logo">
                <img src="{{ asset('assets/Homebound.png') }}" alt="Homebound"
                    style="height:70px;width:auto;object-fit:contain;">
                <span class="sb-brand"></span>
            </a>

            {{-- Nav links --}}
            <nav class="sb-nav">
                @if($user->role === 'admin')
                    <div class="sb-section">Main</div>

                    <a href="{{ route('dashboard') }}" class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="2" y="2" width="5" height="5" rx="1" />
                            <rect x="9" y="2" width="5" height="5" rx="1" />
                            <rect x="2" y="9" width="5" height="5" rx="1" />
                            <rect x="9" y="9" width="5" height="5" rx="1" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('pets.index') }}" class="sb-link {{ request()->routeIs('pets.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="5.5" cy="5" r="2" />
                            <circle cx="11" cy="4" r="1.5" />
                            <path d="M1.5 13c0-2 1.8-3.5 4-3.5s4 1.5 4 3.5" />
                            <path d="M13.5 13c0-1.5-1-2.8-2.5-3" />
                        </svg>
                        Manage Pets
                    </a>

                    <a href="{{ route('applications.index') }}"
                        class="sb-link {{ request()->routeIs('applications.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="2" y="2" width="12" height="13" rx="1.5" />
                            <path d="M5 6h6M5 9h6M5 12h3" />
                        </svg>
                        Applications
                    </a>
                    <a href="{{ route('match.index') }}" class="sb-link {{ request()->routeIs('match.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="5" cy="8" r="3" />
                            <circle cx="11" cy="8" r="3" />
                            <path d="M8 5v6" />
                        </svg>
                        Smart Match
                    </a>

                    <div class="sb-section">Operations</div>

                    <a href="{{ route('home-visits.index') }}"
                        class="sb-link {{ request()->routeIs('home-visits.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M2 7L8 2l6 5v7a1 1 0 01-1 1H3a1 1 0 01-1-1V7z" />
                            <path d="M6 15V9h4v6" />
                        </svg>
                        Home Visits
                    </a>

                    <a href="{{ route('reports.index') }}" class="sb-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M2 12l3.5-4 3 3L12 6l2 2" />
                            <rect x="2" y="2" width="12" height="12" rx="1.5" />
                        </svg>
                        Reports
                    </a>

                    <a href="{{ route('notifications.index') }}"
                        class="sb-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M8 2a5 5 0 00-5 5v3l-1 2h12l-1-2V7a5 5 0 00-5-5z" />
                            <path d="M6.5 13.5a1.5 1.5 0 003 0" />
                        </svg>
                        Notifications
                        @if($unreadCount > 0)
                            <span class="sb-notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('volunteers.index') }}"
                        class="sb-link {{ request()->routeIs('volunteers.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="6" cy="5" r="2.5" />
                            <path d="M1 14c0-2.5 2-4 5-4" />
                            <path d="M11 10l1.5 1.5L15 9" />
                        </svg>
                        Volunteers
                        @php
                            $pendingVolunteers = \App\Models\User::where('role', 'volunteer')->where('is_approved', false)->count();
                        @endphp
                        @if($pendingVolunteers > 0)
                            <span class="sb-notif-badge">{{ $pendingVolunteers }}</span>
                        @endif
                    </a>

                @elseif($user->role === 'volunteer')
                    {{-- VOLUNTEER NAV --}}
                    <div class="sb-section">Shelter</div>

                    <a href="{{ route('pets.index') }}" class="sb-link {{ request()->routeIs('pets.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="5.5" cy="5" r="2" />
                            <circle cx="11" cy="4" r="1.5" />
                            <path d="M1.5 13c0-2 1.8-3.5 4-3.5s4 1.5 4 3.5" />
                            <path d="M13.5 13c0-1.5-1-2.8-2.5-3" />
                        </svg>
                        Pets
                    </a>

                    <a href="{{ route('applications.index') }}"
                        class="sb-link {{ request()->routeIs('applications.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="2" y="2" width="12" height="13" rx="1.5" />
                            <path d="M5 6h6M5 9h6M5 12h3" />
                        </svg>
                        Applications
                        <span style="font-size:10px;color:#888;margin-left:auto;">Read only</span>
                    </a>
                    <a href="{{ route('match.index') }}" class="sb-link {{ request()->routeIs('match.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="5" cy="8" r="3" />
                            <circle cx="11" cy="8" r="3" />
                            <path d="M8 5v6" />
                        </svg>
                        Smart Match
                    </a>

                    <div class="sb-section">Operations</div>

                    <a href="{{ route('home-visits.index') }}"
                        class="sb-link {{ request()->routeIs('home-visits.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M2 7L8 2l6 5v7a1 1 0 01-1 1H3a1 1 0 01-1-1V7z" />
                            <path d="M6 15V9h4v6" />
                        </svg>
                        Home Visits
                    </a>

                    <a href="{{ route('notifications.index') }}"
                        class="sb-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M8 2a5 5 0 00-5 5v3l-1 2h12l-1-2V7a5 5 0 00-5-5z" />
                            <path d="M6.5 13.5a1.5 1.5 0 003 0" />
                        </svg>
                        Notifications
                        @if($unreadCount > 0)
                            <span class="sb-notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </a>

                @else
                    {{-- ADOPTER NAV --}}
                    <div class="sb-section">Browse</div>

                    <a href="{{ route('pets.index') }}" class="sb-link {{ request()->routeIs('pets.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="5.5" cy="5" r="2" />
                            <circle cx="11" cy="4" r="1.5" />
                            <path d="M1.5 13c0-2 1.8-3.5 4-3.5s4 1.5 4 3.5" />
                            <path d="M13.5 13c0-1.5-1-2.8-2.5-3" />
                        </svg>
                        Pets
                    </a>
                    <a href="{{ route('match.index') }}" class="sb-link {{ request()->routeIs('match.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="5" cy="8" r="3" />
                            <circle cx="11" cy="8" r="3" />
                            <path d="M8 5v6" />
                        </svg>
                        Smart Match
                    </a>

                    <a href="{{ route('applications.index') }}"
                        class="sb-link {{ request()->routeIs('applications.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="2" y="2" width="12" height="13" rx="1.5" />
                            <path d="M5 6h6M5 9h6M5 12h3" />
                        </svg>
                        My Applications
                    </a>

                    <a href="{{ route('notifications.index') }}"
                        class="sb-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
                        onclick="closeSidebarOnMobile()">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M8 2a5 5 0 00-5 5v3l-1 2h12l-1-2V7a5 5 0 00-5-5z" />
                            <path d="M6.5 13.5a1.5 1.5 0 003 0" />
                        </svg>
                        Notifications
                        @if($unreadCount > 0)
                            <span class="sb-notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </a>
                @endif
            </nav>

            {{-- User + logout --}}
            <div class="sb-bottom">
                {{-- Dark mode quick toggle --}}
                <button onclick="toggleDarkMode()"
                    style="width:100%;display:flex;align-items:center;gap:10px;padding:8px;
                                                                                           border-radius:8px;border:none;background:transparent;cursor:pointer;
                                                                                           color:var(--ink-3);font-family:'DM Sans',sans-serif;font-size:12px;
                                                                                           margin-bottom:4px;transition:background 0.15s;"
                    onmouseover="this.style.background='rgba(0,0,0,0.05)'" onmouseout="this.style.background='transparent'">
                    <span id="theme-icon" style="font-size:14px;">🌙</span>
                    <span id="theme-label">Dark mode</span>
                </button>
                <a href="{{ route('profile.edit') }}"
                    style="display:flex;align-items:center;gap:10px;padding:8px;border-radius:8px;margin-bottom:4px;transition:background 0.15s;text-decoration:none;"
                    onmouseover="this.style.background='rgba(0,0,0,0.05)'" onmouseout="this.style.background='transparent'">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="sb-avatar"
                            style="object-fit:cover;border-radius:50%;">
                    @else
                        <div class="sb-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    @endif
                    <div>
                        <div class="sb-uname">{{ $user->name }}</div>
                        <div class="sb-role">{{ ucfirst($user->role) }} · Edit profile</div>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sb-logout">Log out</button>
                </form>
            </div>
        </aside>
    @endauth


    {{-- ===== MAIN CONTENT ===== --}}
    <div class="main-content" style="view-transition-name:main-content;">

        {{-- Mobile top bar (shown only on mobile when logged in) --}}
        @auth
            <div class="mobile-topbar">
                <button class="hamburger-btn" onclick="toggleSidebar()" aria-label="Menu">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <rect y="2" width="18" height="2" rx="1" fill="#444" />
                        <rect y="8" width="18" height="2" rx="1" fill="#444" />
                        <rect y="14" width="18" height="2" rx="1" fill="#444" />
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('assets/Homebound.png') }}" alt="Homebound"
                        style="height:36px;width:auto;object-fit:contain;">
                </a>
            </div>
        @endauth

        @if(!auth()->check())
            {{-- Guest top nav --}}
            <nav class="nav">
                <div class="nav-inner">
                    <a href="{{ route('pets.index') }}" class="nav-logo">
                        <img src="{{ asset('assets/Homebound.png') }}" alt="Homebound"
                            style="height:70px;width:auto;object-fit:contain;">
                        <span
                            style="font-family:'Lora',serif;font-size:18px;font-weight:600;color:#1a1a18;">Homebound</span>
                    </a>
                    <div class="nav-actions" style="margin-left:auto;">
                        <a href="{{ route('login') }}" class="btn btn-sm">Log in</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                    </div>
                </div>
            </nav>
        @endif

        <main>
            @yield('content')
        </main>
    </div>

    {{-- Custom confirm modal --}}
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-box">
            <div class="modal-icon modal-icon-danger" id="modalIcon">⚠️</div>
            <div class="modal-title" id="modalTitle">Are you sure?</div>
            <div class="modal-message" id="modalMessage"></div>
            <div class="modal-actions">
                <button class="btn btn-sm" id="modalCancel">Cancel</button>
                <button class="btn btn-danger btn-sm" id="modalConfirm">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        // ===== SIDEBAR TOGGLE =====
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar) return;
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }
        // ===== DARK MODE & FONT SIZE =====
        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            const icon = document.getElementById('theme-icon');
            const label = document.getElementById('theme-label');
            if (theme === 'dark') {
                if (icon) icon.textContent = '☀️';
                if (label) label.textContent = 'Light mode';
            } else {
                if (icon) icon.textContent = '🌙';
                if (label) label.textContent = 'Dark mode';
            }
        }

        function toggleDarkMode() {
            const current = localStorage.getItem('theme') || 'light';
            applyTheme(current === 'dark' ? 'light' : 'dark');
        }

        function setFontSize(size) {
            const sizes = { small: '13px', normal: '15px', large: '17px' };
            document.documentElement.style.fontSize = sizes[size];
            localStorage.setItem('fontSize', size);
            ['small', 'normal', 'large'].forEach(s => {
                const b = document.getElementById('font-' + s);
                if (b) {
                    b.style.background = s === size ? 'var(--green)' : 'var(--surface)';
                    b.style.color = s === size ? 'white' : 'var(--ink)';
                    b.style.borderColor = s === size ? 'var(--green)' : 'var(--border-2)';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            applyTheme(localStorage.getItem('theme') || 'light');
            setFontSize(localStorage.getItem('fontSize') || 'normal');
        });

        function closeSidebarOnMobile() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                if (sidebar) sidebar.classList.remove('open');
                if (overlay) overlay.classList.remove('open');
                document.body.style.overflow = '';
            }
        }

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                if (sidebar) sidebar.classList.remove('open');
                if (overlay) overlay.classList.remove('open');
                document.body.style.overflow = '';
            }
        });

        // ===== CONFIRM MODAL =====
        const modal = document.getElementById('confirmModal');
        const modalMsg = document.getElementById('modalMessage');
        const modalTitle = document.getElementById('modalTitle');
        const modalConfirm = document.getElementById('modalConfirm');
        const modalCancel = document.getElementById('modalCancel');
        let pendingAction = null;

        function openModal({ title, message, confirmText, confirmClass, onConfirm }) {
            modalTitle.textContent = title || 'Are you sure?';
            modalMsg.textContent = message || '';
            modalConfirm.textContent = confirmText || 'Confirm';
            modalConfirm.className = 'btn btn-sm ' + (confirmClass || 'btn-danger');
            pendingAction = onConfirm;
            modal.classList.add('open');
        }

        modalCancel.addEventListener('click', () => { modal.classList.remove('open'); pendingAction = null; });
        modal.addEventListener('click', (e) => { if (e.target === modal) { modal.classList.remove('open'); pendingAction = null; } });
        modalConfirm.addEventListener('click', () => { modal.classList.remove('open'); if (pendingAction) pendingAction(); });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[data-confirm]').forEach(function (form) {
                const trigger = form.querySelector('[type=submit]') || form;
                trigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    openModal({
                        title: form.dataset.title || 'Are you sure?',
                        message: form.dataset.confirm,
                        confirmText: form.dataset.confirmText || 'Confirm',
                        confirmClass: form.dataset.confirmClass || 'btn-danger',
                        onConfirm: () => form.submit()
                    });
                });
            });

            document.querySelectorAll('a[data-confirm], button[data-confirm]').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    openModal({
                        title: el.dataset.title || 'Are you sure?',
                        message: el.dataset.confirm,
                        confirmText: el.dataset.confirmText || 'Confirm',
                        confirmClass: el.dataset.confirmClass || 'btn-danger',
                        onConfirm: () => { if (el.tagName === 'A') window.location = el.href; else el.closest('form')?.submit(); }
                    });
                });
            });
        });
    </script>
    {{-- ===== TOAST CONTAINER ===== --}}
    <div class="toast-container" id="toast-container"></div>

    <script>
        // ===== TOAST SYSTEM =====
        function showToast(message, type = 'success', title = null) {
            const container = document.getElementById('toast-container');

            const icons = {
                success: '✓',
                error: '✕',
                info: 'ℹ',
            };

            const titles = {
                success: title || 'Success',
                error: title || 'Error',
                info: title || 'Info',
            };

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                    <div class="toast-icon">${icons[type] || '✓'}</div>
                    <div class="toast-body">
                        <div class="toast-title">${titles[type]}</div>
                        <div class="toast-message">${message}</div>
                    </div>
                    <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">×</button>
                    <div class="toast-progress"></div>
                `;

            container.appendChild(toast);

            // Trigger animation
            requestAnimationFrame(() => {
                requestAnimationFrame(() => toast.classList.add('show'));
            });

            // Auto dismiss after 4 seconds
            const timer = setTimeout(() => dismissToast(toast), 4000);
            toast._timer = timer;

            return toast;
        }

        function dismissToast(toast) {
            if (!toast) return;
            clearTimeout(toast._timer);
            toast.classList.add('hide');
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 350);
        }

        // ===== AUTO-SHOW FROM SESSION =====
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                showToast(@json(session('success')), 'success');
            @endif
            @if(session('error'))
                showToast(@json(session('error')), 'error');
            @endif
            @if(session('info'))
                showToast(@json(session('info')), 'info');
            @endif
            });
    </script>

    {{-- ===== NAVIGATION PROGRESS BAR (fallback for non-VT browsers) ===== --}}
    <script>
        (function () {
            // View Transitions API already handles visual feedback natively —
            // only show the progress bar on browsers that don't support it.
            if ('startViewTransition' in document) return;

            var bar = document.createElement('div');
            bar.id = 'nav-progress';
            document.body.appendChild(bar);

            var rafTimer;

            function startProgress() {
                clearTimeout(rafTimer);
                bar.style.transition = 'none';
                bar.style.opacity = '1';
                bar.style.width = '0%';

                // Small delay so the browser paints the reset before we animate
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        bar.style.transition = 'width 250ms ease, opacity 300ms ease';
                        bar.style.width = '25%';
                        // Crawl toward 80% — gives the illusion of progress
                        rafTimer = setTimeout(function () { bar.style.width = '65%'; }, 300);
                        rafTimer = setTimeout(function () { bar.style.width = '80%'; }, 800);
                    });
                });
            }

            function finishProgress() {
                clearTimeout(rafTimer);
                bar.style.width = '100%';
                setTimeout(function () { bar.style.opacity = '0'; }, 200);
                setTimeout(function () { bar.style.width = '0%'; }, 520);
            }

            // Trigger on any same-origin link click (skip anchors, external, blank)
            document.addEventListener('click', function (e) {
                var link = e.target.closest('a[href]');
                if (!link) return;
                var href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript') ||
                    link.target === '_blank' || link.origin !== location.origin) return;
                if (e.ctrlKey || e.metaKey || e.shiftKey) return; // new tab / window
                startProgress();
            });

            // Also show on form submit (page navigations triggered by forms)
            document.addEventListener('submit', function (e) {
                if (!e.target.closest('form[data-confirm]')) startProgress();
            });

            window.addEventListener('pageshow', finishProgress);
        })();
    </script>
</body>

</html>