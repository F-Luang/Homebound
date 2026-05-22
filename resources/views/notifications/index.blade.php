@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
    <div class="page">
        <div class="page-header">
            <div class="page-title">Notifications</div>
            <div class="page-sub">
                @if(auth()->user()->role === 'admin')
                    Recent activity across all applications and home visits.
                @else
                    Updates on your adoption applications and scheduled visits.
                @endif
            </div>
        </div>

        <div class="two-col">
            <div class="card">
                <div style="font-size:15px;font-weight:600;font-family:'Lora',serif;margin-bottom:16px;">Applications</div>
                @forelse($applications as $app)
                    <a href="{{ route('applications.show', $app) }}"
                       style="display:block;text-decoration:none;color:inherit;border-radius:8px;transition:background 0.15s;"
                       onmouseover="this.style.background='var(--surface-2,#f5f4f0)'"
                       onmouseout="this.style.background='transparent'">
                        <div class="tl-item">
                            <div class="tl-dot tl-dot-{{ $app->status === 'approved' ? 'green' : ($app->status === 'pending' ? 'amber' : 'blue') }}">
                                {{ strtoupper(substr($app->status, 0, 1)) }}
                            </div>
                            <div>
                                <div class="tl-title">
                                    {{ $app->pet->name ?? 'Unknown pet' }}
                                    @if(auth()->user()->role === 'admin')
                                        <span class="text-muted">· {{ $app->user->name ?? '' }}</span>
                                    @endif
                                </div>
                                <div class="tl-desc">
                                    Status: <span class="badge badge-{{ $app->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                </div>
                                <div class="tl-date">{{ $app->submitted_at?->diffForHumans() ?? 'Just now' }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="text-align:center;padding:32px 0;">
                        <div style="font-size:24px;margin-bottom:8px;">📋</div>
                        <p class="text-muted">No applications yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="card">
                <div style="font-size:15px;font-weight:600;font-family:'Lora',serif;margin-bottom:16px;">Home Visits</div>
                @forelse($visits as $visit)
                    <a href="{{ route('applications.show', $visit->application) }}"
                       style="display:block;text-decoration:none;color:inherit;border-radius:8px;transition:background 0.15s;"
                       onmouseover="this.style.background='var(--surface-2,#f5f4f0)'"
                       onmouseout="this.style.background='transparent'">
                        <div class="tl-item">
                            <div class="tl-dot tl-dot-{{ $visit->status === 'completed' ? 'green' : 'amber' }}">
                                🏠
                            </div>
                            <div>
                                <div class="tl-title">
                                    {{ $visit->application->pet->name ?? 'Unknown pet' }}
                                    @if(auth()->user()->role === 'admin')
                                        <span class="text-muted">· {{ $visit->application->user->name ?? '' }}</span>
                                    @endif
                                </div>
                                <div class="tl-desc">
                                    Visit status: <span class="badge badge-{{ $visit->status }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </div>
                                <div class="tl-date">{{ $visit->scheduled_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="text-align:center;padding:32px 0;">
                        <div style="font-size:24px;margin-bottom:8px;">🏠</div>
                        <p class="text-muted">No home visits scheduled.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection