@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="page">

    <div class="page-header" style="margin-bottom:20px;">
        <div class="page-title">Notifications</div>
        <div class="page-sub">
            @if(auth()->user()->isAdmin())
                Track applications, visits, check-ins, and surrender requests.
            @else
                Stay updated on your adoption applications and check-ins.
            @endif
        </div>
    </div>

    {{-- ===== TAB BAR ===== --}}
    <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:20px;border-bottom:1.5px solid #eee;padding-bottom:0;">

        @php
        $tabs = [
            'applications' => ['label' => 'Applications',           'icon' => '📋'],
            'visits'       => ['label' => 'Home Visits',            'icon' => '🏠'],
            'checkins'     => ['label' => 'Post-adoption Check-ins','icon' => '✅'],
        ];
        if(auth()->user()->isAdmin()) {
            $tabs['surrenders'] = ['label' => 'Surrender Requests', 'icon' => '📩'];
        }
        @endphp

        @foreach($tabs as $key => $meta)
            @php $isActive = $tab === $key; @endphp
            <a href="{{ route('notifications.index', ['tab' => $key]) }}"
               style="display:inline-flex;align-items:center;gap:7px;
                      padding:9px 16px;
                      border-radius:8px 8px 0 0;
                      font-size:13px;font-weight:500;
                      text-decoration:none;
                      border:1.5px solid {{ $isActive ? '#1D9E75' : 'transparent' }};
                      border-bottom:{{ $isActive ? '1.5px solid white' : '1.5px solid transparent' }};
                      margin-bottom:-1.5px;
                      color:{{ $isActive ? '#1D9E75' : '#666' }};
                      background:{{ $isActive ? 'white' : 'transparent' }};
                      transition:color 0.15s,background 0.15s;"
               onmouseover="if('{{ $isActive }}'!=='1') this.style.color='#1a1a18'"
               onmouseout="if('{{ $isActive }}'!=='1') this.style.color='#666'">
                {{ $meta['icon'] }}
                {{ $meta['label'] }}
                @if($counts[$key] > 0)
                    <span style="background:{{ $isActive ? '#1D9E75' : '#e5e5e5' }};
                                 color:{{ $isActive ? 'white' : '#666' }};
                                 font-size:10px;font-weight:700;
                                 padding:1px 7px;border-radius:10px;min-width:20px;text-align:center;">
                        {{ $counts[$key] > 99 ? '99+' : $counts[$key] }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- ===== TAB PANELS ===== --}}

    {{-- Applications --}}
    @if($tab === 'applications')
        <div class="card">
            @forelse($applications as $app)
                <a href="{{ route('applications.show', $app) }}"
                   style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:0.5px solid #f0f0f0;text-decoration:none;color:inherit;border-radius:6px;transition:background 0.12s;margin:0 -8px;padding-left:8px;padding-right:8px;"
                   onmouseover="this.style.background='#f9fdf9'"
                   onmouseout="this.style.background='transparent'">

                    {{-- Avatar --}}
                    <div style="width:38px;height:38px;border-radius:50%;background:#E1F5EE;color:#0F6E56;
                                display:flex;align-items:center;justify-content:center;
                                font-size:14px;font-weight:700;flex-shrink:0;">
                        {{ strtoupper(substr($app->user->name ?? $app->pet->name ?? '?', 0, 1)) }}
                    </div>

                    {{-- Body --}}
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $app->pet->name ?? 'Unknown pet' }}
                            @if(auth()->user()->isAdmin())
                                <span style="color:#888;font-weight:400;"> · {{ $app->user->name ?? '' }}</span>
                            @endif
                        </div>
                        <div style="font-size:12px;color:#888;margin-top:2px;">
                            {{ $app->submitted_at?->diffForHumans() ?? 'Just now' }}
                        </div>
                    </div>

                    {{-- Badge --}}
                    <span class="badge badge-{{ str_replace('_','-',$app->status) }}" style="flex-shrink:0;">
                        {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                    </span>

                    <span style="color:#ccc;font-size:16px;flex-shrink:0;">›</span>
                </a>
            @empty
                <div style="text-align:center;padding:48px 0;color:#aaa;">
                    <div style="font-size:32px;margin-bottom:10px;">📋</div>
                    <div style="font-size:14px;">No applications yet.</div>
                </div>
            @endforelse
        </div>

        @if($applications->hasPages())
            <div style="margin-top:16px;">{{ $applications->links() }}</div>
        @endif
    @endif

    {{-- Home Visits --}}
    @if($tab === 'visits')
        <div class="card">
            @forelse($visits as $visit)
                <a href="{{ route('applications.show', $visit->application) }}"
                   style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:0.5px solid #f0f0f0;text-decoration:none;color:inherit;border-radius:6px;transition:background 0.12s;margin:0 -8px;padding-left:8px;padding-right:8px;"
                   onmouseover="this.style.background='#f9fdf9'"
                   onmouseout="this.style.background='transparent'">

                    <div style="width:38px;height:38px;border-radius:50%;background:#FFF3E0;color:#B35F00;
                                display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                        🏠
                    </div>

                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $visit->application->pet->name ?? 'Unknown pet' }}
                            @if(auth()->user()->isAdmin())
                                <span style="color:#888;font-weight:400;"> · {{ $visit->application->user->name ?? '' }}</span>
                            @endif
                        </div>
                        <div style="font-size:12px;color:#888;margin-top:2px;">
                            {{ $visit->scheduled_at->format('M j, Y · g:i A') }}
                            · {{ $visit->scheduled_at->diffForHumans() }}
                        </div>
                    </div>

                    <span class="badge badge-{{ $visit->status === 'completed' ? 'completed' : ($visit->status === 'cancelled' ? 'rejected' : 'pending') }}" style="flex-shrink:0;">
                        {{ ucfirst($visit->status) }}
                    </span>

                    <span style="color:#ccc;font-size:16px;flex-shrink:0;">›</span>
                </a>
            @empty
                <div style="text-align:center;padding:48px 0;color:#aaa;">
                    <div style="font-size:32px;margin-bottom:10px;">🏠</div>
                    <div style="font-size:14px;">No home visits scheduled.</div>
                </div>
            @endforelse
        </div>

        @if($visits->hasPages())
            <div style="margin-top:16px;">{{ $visits->links() }}</div>
        @endif
    @endif

    {{-- Post-adoption Check-ins --}}
    @if($tab === 'checkins')
        <div class="card">
            @forelse($checkins as $checkin)
                @php $overdue = $checkin->due_at->isPast() && $checkin->status !== 'completed'; @endphp
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('checkins.index') }}"
                       style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:0.5px solid #f0f0f0;text-decoration:none;color:inherit;border-radius:6px;transition:background 0.12s;margin:0 -8px;padding-left:8px;padding-right:8px;"
                       onmouseover="this.style.background='#f9fdf9'"
                       onmouseout="this.style.background='transparent'">
                @else
                    <div style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:0.5px solid #f0f0f0;">
                @endif

                    <div style="width:38px;height:38px;border-radius:50%;
                                background:{{ $overdue ? '#FAECE7' : '#E1F5EE' }};
                                color:{{ $overdue ? '#993C1D' : '#0F6E56' }};
                                display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                        {{ $overdue ? '⚠️' : '✅' }}
                    </div>

                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:500;">
                            {{ $checkin->label }}
                            <span style="color:#888;font-weight:400;"> · {{ $checkin->application->pet->name ?? '' }}</span>
                        </div>
                        @if(auth()->user()->isAdmin())
                            <div style="font-size:12px;color:#888;margin-top:2px;">
                                Adopter: {{ $checkin->application->user->name ?? '—' }}
                            </div>
                        @endif
                        <div style="font-size:12px;margin-top:2px;color:{{ $overdue ? '#993C1D' : '#888' }};font-weight:{{ $overdue ? '500' : '400' }};">
                            {{ $overdue ? 'Overdue · ' : 'Due ' }}{{ $checkin->due_at->format('M j, Y') }}
                            · {{ $checkin->due_at->diffForHumans() }}
                        </div>
                    </div>

                    @if(auth()->user()->isAdmin())
                        <span style="color:#ccc;font-size:16px;flex-shrink:0;">›</span>
                    @endif

                @if(auth()->user()->isAdmin())
                    </a>
                @else
                    </div>
                @endif
            @empty
                <div style="text-align:center;padding:48px 0;color:#aaa;">
                    <div style="font-size:32px;margin-bottom:10px;">✅</div>
                    <div style="font-size:14px;">No pending check-ins.</div>
                </div>
            @endforelse
        </div>

        @if($checkins->hasPages())
            <div style="margin-top:16px;">{{ $checkins->links() }}</div>
        @endif
    @endif

    {{-- Surrender Requests (admin only) --}}
    @if($tab === 'surrenders' && auth()->user()->isAdmin())
        <div class="card">
            @forelse($surrenders as $s)
                <a href="{{ route('surrenders.index') }}"
                   style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:0.5px solid #f0f0f0;text-decoration:none;color:inherit;border-radius:6px;transition:background 0.12s;margin:0 -8px;padding-left:8px;padding-right:8px;"
                   onmouseover="this.style.background='#f9fdf9'"
                   onmouseout="this.style.background='transparent'">

                    <div style="width:38px;height:38px;border-radius:50%;
                                background:{{ $s->urgency === 'high' ? '#FAECE7' : ($s->urgency === 'medium' ? '#FFF8E1' : '#E1F5EE') }};
                                color:{{ $s->urgency === 'high' ? '#993C1D' : ($s->urgency === 'medium' ? '#7A5500' : '#0F6E56') }};
                                display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                        📩
                    </div>

                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:500;">
                            {{ $s->pet_name }}
                            <span style="color:#888;font-weight:400;"> · {{ $s->submitter_name }}</span>
                        </div>
                        <div style="font-size:12px;color:#888;margin-top:2px;">
                            {{ ucfirst($s->species) }} · {{ $s->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0;">
                        <span class="badge badge-{{ $s->status === 'accepted' ? 'completed' : ($s->status === 'declined' ? 'rejected' : ($s->status === 'contacted' ? 'approved' : 'pending')) }}">
                            {{ ucfirst($s->status) }}
                        </span>
                        <span style="font-size:10px;color:{{ $s->urgency === 'high' ? '#993C1D' : ($s->urgency === 'medium' ? '#B35F00' : '#888') }};font-weight:500;">
                            {{ ucfirst($s->urgency) }} urgency
                        </span>
                    </div>

                    <span style="color:#ccc;font-size:16px;flex-shrink:0;">›</span>
                </a>
            @empty
                <div style="text-align:center;padding:48px 0;color:#aaa;">
                    <div style="font-size:32px;margin-bottom:10px;">📭</div>
                    <div style="font-size:14px;">No surrender requests.</div>
                </div>
            @endforelse
        </div>

        @if($surrenders->hasPages())
            <div style="margin-top:16px;">{{ $surrenders->links() }}</div>
        @endif
    @endif

</div>
@endsection
