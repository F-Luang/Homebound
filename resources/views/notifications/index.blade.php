@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
    <div class="page">
        <div class="page-header">
            <div class="page-title">Notifications</div>
            <div class="page-sub">
                @if(auth()->user()->role === 'admin')
                    Recent activity across applications, visits, check-ins, and surrenders.
                @else
                    Updates on your adoption applications, visits, and check-ins.
                @endif
            </div>
        </div>

        {{-- ===== ROW 1: Applications + Home Visits ===== --}}
        <div class="two-col" style="margin-bottom:16px;">

            {{-- Applications --}}
            <div class="card">
                <div style="font-size:15px;font-weight:600;font-family:'Lora',serif;margin-bottom:16px;">Applications</div>
                @forelse($applications as $app)
                    <a href="{{ route('applications.show', $app) }}"
                       style="display:block;text-decoration:none;color:inherit;border-radius:8px;transition:background 0.15s;margin:-4px;padding:4px;"
                       onmouseover="this.style.background='var(--surface-2,#f5f4f0)'"
                       onmouseout="this.style.background='transparent'">
                        <div class="tl-item">
                            <div class="tl-dot tl-dot-{{ $app->status === 'approved' || $app->status === 'completed' ? 'green' : ($app->status === 'rejected' || $app->status === 'cancelled' ? 'coral' : 'amber') }}">
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
                                    <span class="badge badge-{{ str_replace('_','-',$app->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                </div>
                                <div class="tl-date">{{ $app->submitted_at?->diffForHumans() ?? 'Just now' }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="text-align:center;padding:32px 0;color:var(--ink-3);">
                        <div style="font-size:24px;margin-bottom:8px;">📋</div>
                        <p class="text-muted">No applications yet.</p>
                    </div>
                @endforelse
            </div>

            {{-- Home Visits --}}
            <div class="card">
                <div style="font-size:15px;font-weight:600;font-family:'Lora',serif;margin-bottom:16px;">Home Visits</div>
                @forelse($visits as $visit)
                    <a href="{{ route('applications.show', $visit->application) }}"
                       style="display:block;text-decoration:none;color:inherit;border-radius:8px;transition:background 0.15s;margin:-4px;padding:4px;"
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
                                    <span class="badge badge-{{ $visit->status === 'completed' ? 'completed' : 'pending' }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                    · {{ $visit->scheduled_at->format('M j, Y') }}
                                </div>
                                <div class="tl-date">{{ $visit->scheduled_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="text-align:center;padding:32px 0;color:var(--ink-3);">
                        <div style="font-size:24px;margin-bottom:8px;">🏠</div>
                        <p class="text-muted">No home visits scheduled.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ===== ROW 2: Check-ins + Surrenders ===== --}}
        <div class="two-col">

            {{-- Post-adoption Check-ins --}}
            <div class="card">
                <div style="font-size:15px;font-weight:600;font-family:'Lora',serif;margin-bottom:16px;">Post-adoption Check-ins</div>
                @forelse($checkins as $checkin)
                    @php $overdue = $checkin->isOverdue(); @endphp
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('checkins.index') }}"
                           style="display:block;text-decoration:none;color:inherit;border-radius:8px;transition:background 0.15s;margin:-4px;padding:4px;"
                           onmouseover="this.style.background='var(--surface-2,#f5f4f0)'"
                           onmouseout="this.style.background='transparent'">
                    @endif
                        <div class="tl-item">
                            <div class="tl-dot tl-dot-{{ $overdue ? 'coral' : 'blue' }}">
                                {{ $overdue ? '!' : '✓' }}
                            </div>
                            <div>
                                <div class="tl-title">
                                    {{ $checkin->label }}
                                    @if(auth()->user()->isAdmin())
                                        <span class="text-muted">· {{ $checkin->application->pet->name ?? '' }}</span>
                                    @else
                                        <span class="text-muted">· {{ $checkin->application->pet->name ?? '' }}</span>
                                    @endif
                                </div>
                                <div class="tl-desc">
                                    @if(auth()->user()->isAdmin())
                                        Adopter: {{ $checkin->application->user->name ?? '—' }} ·
                                    @endif
                                    Due {{ $checkin->due_at->format('M j, Y') }}
                                </div>
                                <div class="tl-date" style="{{ $overdue ? 'color:var(--coral);font-weight:500;' : '' }}">
                                    {{ $overdue ? 'Overdue — ' : '' }}{{ $checkin->due_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @if(auth()->user()->isAdmin())
                        </a>
                    @endif
                @empty
                    <div style="text-align:center;padding:32px 0;color:var(--ink-3);">
                        <div style="font-size:24px;margin-bottom:8px;">✅</div>
                        <p class="text-muted">No pending check-ins.</p>
                    </div>
                @endforelse
            </div>

            {{-- Surrenders (admin only) --}}
            @if(auth()->user()->isAdmin())
                <div class="card">
                    <div style="font-size:15px;font-weight:600;font-family:'Lora',serif;margin-bottom:16px;">Surrender Requests</div>
                    @forelse($surrenders as $s)
                        <a href="{{ route('surrenders.index') }}"
                           style="display:block;text-decoration:none;color:inherit;border-radius:8px;transition:background 0.15s;margin:-4px;padding:4px;"
                           onmouseover="this.style.background='var(--surface-2,#f5f4f0)'"
                           onmouseout="this.style.background='transparent'">
                            <div class="tl-item">
                                <div class="tl-dot tl-dot-{{ $s->urgency === 'high' ? 'coral' : ($s->urgency === 'medium' ? 'amber' : 'blue') }}">
                                    {{ strtoupper(substr($s->urgency, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="tl-title">
                                        {{ $s->pet_name }}
                                        <span class="text-muted">· {{ $s->submitter_name }}</span>
                                    </div>
                                    <div class="tl-desc">
                                        {{ ucfirst($s->species) }}
                                        · <span class="badge badge-{{ $s->status === 'accepted' ? 'completed' : ($s->status === 'declined' ? 'rejected' : ($s->status === 'contacted' ? 'approved' : 'pending')) }}">
                                            {{ ucfirst($s->status) }}
                                        </span>
                                        · {{ $s->urgencyLabel() }}
                                    </div>
                                    <div class="tl-date">{{ $s->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div style="text-align:center;padding:32px 0;color:var(--ink-3);">
                            <div style="font-size:24px;margin-bottom:8px;">📭</div>
                            <p class="text-muted">No surrender requests.</p>
                        </div>
                    @endforelse
                </div>
            @else
                {{-- Adopter: placeholder so the grid stays balanced --}}
                <div></div>
            @endif

        </div>
    </div>
@endsection
