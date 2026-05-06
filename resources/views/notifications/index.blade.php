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
                    <div class="tl-item">
                        <div
                            class="tl-dot tl-dot-{{ $app->status === 'approved' ? 'green' : ($app->status === 'pending' ? 'amber' : 'blue') }}">
                            {{ strtoupper(substr($app->status, 0, 1)) }}
                        </div>
                        <div>
                            <div class="tl-title">
                                {{ $app->pet->name ?? 'Unknown pet' }}
                                @if(auth()->user()->role === 'admin')
                                    <span class="text-muted">· {{ $app->user->name ?? '' }}</span>
                                @endif
                            </div>
                            <div class="tl-desc">Status: <span
                                    class="badge badge-{{ $app->status }}">{{ ucfirst(str_replace('-', ' ', $app->status)) }}</span>
                            </div>
                            <div class="tl-date">{{ $app->submitted_at?->diffForHumans() ?? 'Just now' }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No applications yet.</p>
                @endforelse
            </div>

            <div class="card">
                <div style="font-size:15px;font-weight:600;font-family:'Lora',serif;margin-bottom:16px;">Home Visits</div>
                @forelse($visits as $visit)
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
                            <div class="tl-desc">Visit status: <span
                                    class="badge badge-{{ $visit->status }}">{{ ucfirst($visit->status) }}</span>
                            </div>
                            <div class="tl-date">{{ $visit->scheduled_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No home visits scheduled.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection