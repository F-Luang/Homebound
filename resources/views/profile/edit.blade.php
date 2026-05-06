@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
    <div class="page" style="max-width:720px;">
        <div class="page-header">
            <div class="page-title">My profile</div>
            <div class="page-sub">Update your account information and password.</div>
        </div>

        {{-- ===== PROFILE INFO ===== --}}
        <div class="card" style="margin-bottom:16px;">
            <div
                style="font-size:12px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:20px;">
                Account information
            </div>

            {{-- Avatar --}}
            <div
                style="display:flex;align-items:center;gap:16px;margin-bottom:24px;padding-bottom:24px;border-bottom:0.5px solid rgba(0,0,0,0.06);">
                <div
                    style="width:64px;height:64px;border-radius:50%;background:var(--green-light);color:var(--green-dark);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:600;flex-shrink:0;font-family:'Lora',serif;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-family:'Lora',serif;font-size:18px;font-weight:600;">{{ auth()->user()->name }}</div>
                    <div style="font-size:13px;color:#888;">{{ auth()->user()->email }}</div>
                    <div style="margin-top:4px;">
                        <span
                            class="badge {{ auth()->user()->role === 'admin' ? 'badge-approved' : (auth()->user()->role === 'volunteer' ? 'badge-review' : 'badge-available') }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('patch')

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="name">Full name</label>
                        <input class="form-input" type="text" id="name" name="name"
                            value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email address</label>
                        <input class="form-input" type="email" id="email" name="email"
                            value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Role</label>
                    <input class="form-input" type="text" value="{{ ucfirst(auth()->user()->role) }}" disabled
                        style="opacity:0.6;cursor:not-allowed;">
                    <div style="font-size:11px;color:#888;margin-top:3px;">
                        Role cannot be changed. Contact an admin if needed.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
        </div>

        {{-- ===== CHANGE PASSWORD ===== --}}
        <div class="card" style="margin-bottom:16px;">
            <div
                style="font-size:12px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:20px;">
                Change password
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf @method('put')

                <div class="form-group">
                    <label class="form-label" for="current_password">Current password</label>
                    <input class="form-input" type="password" id="current_password" name="current_password"
                        autocomplete="current-password">
                    @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">New password</label>
                        <input class="form-input" type="password" id="password" name="password" autocomplete="new-password">
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm new password</label>
                        <input class="form-input" type="password" id="password_confirmation" name="password_confirmation"
                            autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update password</button>
            </form>
        </div>

        {{-- ===== DANGER ZONE ===== --}}
        <div class="card" style="border-color:rgba(153,60,29,0.2);">
            <div
                style="font-size:12px;font-weight:500;color:#993C1D;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                Danger zone
            </div>
            <div style="font-size:13px;color:#666;margin-bottom:16px;line-height:1.6;">
                Permanently delete your account and all associated data. This action cannot be undone.
            </div>
            <button onclick="document.getElementById('delete-modal').style.display='flex'" class="btn btn-danger btn-sm">
                Delete account
            </button>
        </div>
    </div>

    {{-- Delete account modal --}}
    <div id="delete-modal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;padding:24px;">
        <div style="background:white;border-radius:16px;padding:32px;max-width:400px;width:100%;">
            <div style="font-size:28px;text-align:center;margin-bottom:16px;">⚠️</div>
            <div style="font-family:'Lora',serif;font-size:18px;font-weight:600;text-align:center;margin-bottom:8px;">
                Delete your account?
            </div>
            <div style="font-size:13px;color:#666;text-align:center;margin-bottom:24px;line-height:1.6;">
                This will permanently delete your account and all your application data. This cannot be undone.
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('delete')
                <div class="form-group">
                    <label class="form-label">Enter your password to confirm</label>
                    <input class="form-input" type="password" name="password" placeholder="Your current password" required>
                    @error('password', 'userDeletion')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex gap-8">
                    <button type="button" onclick="document.getElementById('delete-modal').style.display='none'" class="btn"
                        style="flex:1;">Cancel</button>
                    <button type="submit" class="btn btn-danger" style="flex:1;">Delete account</button>
                </div>
            </form>
        </div>
        {{-- ===== ACCESSIBILITY ===== --}}
        <div class="card" style="margin-bottom:16px;">
            <div
                style="font-size:12px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:20px;">
                Accessibility
            </div>

            {{-- Dark mode toggle --}}
            <div
                style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:0.5px solid rgba(0,0,0,0.06);">
                <div>
                    <div style="font-size:14px;font-weight:500;color:var(--ink);margin-bottom:3px;">
                        Dark mode
                    </div>
                    <div style="font-size:12px;color:var(--ink-3);">
                        Switch between light and dark interface.
                    </div>
                </div>
                <button id="dark-mode-toggle" onclick="toggleDarkMode()" style="width:52px;height:28px;border-radius:14px;border:none;cursor:pointer;
                                               position:relative;transition:background 0.3s;
                                               background:var(--green);" aria-label="Toggle dark mode">
                    <div id="toggle-thumb"
                        style="position:absolute;top:3px;width:22px;height:22px;border-radius:50%;
                                                background:white;transition:left 0.3s;box-shadow:0 1px 4px rgba(0,0,0,0.2);">
                    </div>
                </button>
            </div>

            {{-- Font size --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;">
                <div>
                    <div style="font-size:14px;font-weight:500;color:var(--ink);margin-bottom:3px;">
                        Text size
                    </div>
                    <div style="font-size:12px;color:var(--ink-3);">
                        Adjust the interface text size.
                    </div>
                </div>
                <div style="display:flex;gap:6px;">
                    <button onclick="setFontSize('small')" id="font-small" style="padding:5px 12px;border-radius:6px;border:0.5px solid var(--border-2);
                                                   background:var(--surface);color:var(--ink);font-size:11px;cursor:pointer;
                                                   font-family:'DM Sans',sans-serif;transition:all 0.15s;">
                        A
                    </button>
                    <button onclick="setFontSize('normal')" id="font-normal" style="padding:5px 12px;border-radius:6px;border:0.5px solid var(--border-2);
                                                   background:var(--surface);color:var(--ink);font-size:13px;cursor:pointer;
                                                   font-family:'DM Sans',sans-serif;transition:all 0.15s;">
                        A
                    </button>
                    <button onclick="setFontSize('large')" id="font-large" style="padding:5px 12px;border-radius:6px;border:0.5px solid var(--border-2);
                                                   background:var(--surface);color:var(--ink);font-size:15px;cursor:pointer;
                                                   font-family:'DM Sans',sans-serif;transition:all 0.15s;">
                        A
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // ===== DARK MODE =====
        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            const thumb = document.getElementById('toggle-thumb');
            const btn = document.getElementById('dark-mode-toggle');
            if (theme === 'dark') {
                if (thumb) thumb.style.left = '27px';
                if (btn) btn.style.background = '#2DD4A0';
            } else {
                if (thumb) thumb.style.left = '3px';
                if (btn) btn.style.background = '#1D9E75';
            }
        }

        function toggleDarkMode() {
            const current = localStorage.getItem('theme') || 'light';
            applyTheme(current === 'dark' ? 'light' : 'dark');
        }

        // ===== FONT SIZE =====
        function setFontSize(size) {
            const sizes = { small: '13px', normal: '15px', large: '17px' };
            document.documentElement.style.fontSize = sizes[size];
            localStorage.setItem('fontSize', size);
            ['small', 'normal', 'large'].forEach(s => {
                const btn = document.getElementById('font-' + s);
                if (btn) {
                    btn.style.background = s === size ? 'var(--green)' : 'var(--surface)';
                    btn.style.color = s === size ? 'white' : 'var(--ink)';
                    btn.style.borderColor = s === size ? 'var(--green)' : 'var(--border-2)';
                }
            });
        }

        // Apply saved preferences on load
        document.addEventListener('DOMContentLoaded', function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const savedFontSize = localStorage.getItem('fontSize') || 'normal';
            applyTheme(savedTheme);
            setFontSize(savedFontSize);
        });
    </script>
@endsection