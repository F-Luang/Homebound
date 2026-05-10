@extends('layouts.app')
@section('title', 'Application — ' . ($application->pet->name ?? 'Deleted pet'))

@section('content')
    <div class="page">
        <div style="margin-bottom:16px;">
            <a href="{{ route('applications.index') }}" class="text-muted" style="font-size:13px;">← Back to applications</a>
        </div>

        <div class="two-col" style="align-items:start;">

            {{-- ===== LEFT: application info ===== --}}
            <div>
                {{-- Status card --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="flex items-center justify-between" style="margin-bottom:20px;">
                        <div>
                            <div style="font-family:'Lora',serif;font-size:20px;font-weight:600;">
                                {{ $application->user->name ?? 'Unknown user' }}
                            </div>
                            <div style="font-size:13px;color:#888;margin-top:2px;">
                                Applied for <strong style="color:#1a1a18;">{{ $application->pet->name ?? 'Deleted pet' }}</strong>
                                · {{ $application->submitted_at->format('M j, Y') }}
                            </div>
                        </div>
                        <span class="badge badge-{{ str_replace('_', '-', $application->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </div>

                    {{-- Workflow progress --}}
                    <div class="steps" style="margin-bottom:16px;">
                        @php
                            $stages = ['pending','under_review','meet_greet','home_check','approved','completed'];
                            $currentIndex = array_search($application->status, $stages);
                            if ($application->status === 'rejected') $currentIndex = -1;
                        @endphp
                        @foreach($stages as $i => $stage)
                            <div class="step {{ $i < $currentIndex ? 'done' : ($i === $currentIndex ? 'current' : '') }}">
                                <div class="step-circle">
                                    @if($i < $currentIndex) ✓ @else {{ $i + 1 }} @endif
                                </div>
                                <div class="step-label">{{ ucfirst(str_replace('_', ' ', $stage)) }}</div>
                            </div>
                        @endforeach
                    </div>

                    @if($application->status === 'rejected')
                        <div style="background:#FAECE7;border:0.5px solid #F0997B;border-radius:8px;padding:12px 14px;font-size:13px;color:#993C1D;">
                            ✕ This application has been rejected.
                        </div>
                    @endif
                </div>

                {{-- Applicant info --}}
                <div class="card" style="margin-bottom:16px;">
                    <div style="font-size:13px;font-weight:500;margin-bottom:14px;">Applicant information</div>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                        <div style="width:44px;height:44px;border-radius:50%;background:#E1F5EE;color:#0F6E56;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:600;flex-shrink:0;">
                            {{ strtoupper(substr($application->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:500;">{{ $application->user->name ?? 'Unknown' }}</div>
                            <div style="font-size:12px;color:#888;">{{ $application->user->email ?? '' }}</div>
                        </div>
                    </div>
                    @if($application->notes)
                        <div style="font-size:12px;font-weight:500;color:#888;margin-bottom:6px;">Application notes</div>
                        <div style="font-size:13px;color:#444;line-height:1.7;background:#F5F4F0;border-radius:8px;padding:12px 14px;">
                            {{ $application->notes }}
                        </div>
                    @else
                        <div class="text-muted">No notes provided.</div>
                    @endif
                </div>

                {{-- Meet & greet info --}}
                @if($application->meetGreet)
                    <div class="card" style="margin-bottom:16px;">
                        <div style="font-size:13px;font-weight:500;margin-bottom:14px;">Meet & greet</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div>
                                <div style="font-size:11px;color:#888;margin-bottom:3px;">Scheduled</div>
                                <div style="font-size:13px;font-weight:500;">
                                    {{ $application->meetGreet->scheduled_at->format('M j, Y · g:i A') }}
                                </div>
                            </div>
                            <div>
                                <div style="font-size:11px;color:#888;margin-bottom:3px;">Status</div>
                                <span class="badge badge-{{ $application->meetGreet->status === 'completed' ? 'approved' : 'review' }}">
                                    {{ ucfirst($application->meetGreet->status) }}
                                </span>
                            </div>
                            @if($application->meetGreet->volunteer)
                                <div>
                                    <div style="font-size:11px;color:#888;margin-bottom:3px;">Volunteer</div>
                                    <div style="font-size:13px;font-weight:500;">{{ $application->meetGreet->volunteer->name }}</div>
                                </div>
                            @endif
                            @if($application->meetGreet->notes)
                                <div style="grid-column:1/-1;">
                                    <div style="font-size:11px;color:#888;margin-bottom:3px;">Notes</div>
                                    <div style="font-size:13px;color:#444;">{{ $application->meetGreet->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Admin actions --}}
                @if(auth()->user()->isAdmin() && !in_array($application->status, ['rejected','completed']))
                    <div class="card">
                        <div style="font-size:13px;font-weight:500;margin-bottom:12px;">Advance application</div>
                        <div class="flex gap-8" style="flex-wrap:wrap;">
                            @foreach($nextStages as $next)
                                @if($next !== 'rejected')
                                    <form method="POST" action="{{ route('applications.updateStatus', $application) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $next }}">
                                        <button class="btn btn-primary btn-sm">
                                            → {{ ucfirst(str_replace('_', ' ', $next)) }}
                                        </button>
                                    </form>
                                @endif
                            @endforeach
                            <form method="POST" action="{{ route('applications.updateStatus', $application) }}"
                                onsubmit="return confirm('Reject this application?')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ===== RIGHT: pet info ===== --}}
            <div>
                <div class="card">
                    <div style="font-size:13px;font-weight:500;margin-bottom:14px;">Pet profile</div>

                    @if($application->pet)
                        @php
                            $primary = $application->pet->images->firstWhere('is_primary', true)
                                ?? $application->pet->images->first();
                        @endphp
                        @if($primary)
                            <img src="{{ str_starts_with($primary->path, 'http') ? $primary->path : asset('storage/' . $primary->path) }}"
                                alt="{{ $application->pet->name }}"
                                style="width:100%;height:180px;object-fit:cover;border-radius:10px;margin-bottom:14px;">
                        @else
                            <div style="height:140px;background:#E1F5EE;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:52px;margin-bottom:14px;">
                                {{ ['dog'=>'🐕','cat'=>'🐈','rabbit'=>'🐇','bird'=>'🐦','other'=>'🐾'][$application->pet->species] ?? '🐾' }}
                            </div>
                        @endif

                        <div style="font-family:'Lora',serif;font-size:18px;font-weight:600;margin-bottom:4px;">
                            {{ $application->pet->name }}
                        </div>
                        <div style="font-size:13px;color:#888;margin-bottom:12px;">
                            {{ $application->pet->breed ?? ucfirst($application->pet->species) }}
                            · {{ floor($application->pet->age_months / 12) }}y
                            · {{ ucfirst($application->pet->size) }}
                            · {{ ucfirst($application->pet->activity_level) }}
                        </div>
                        <div class="flex gap-8" style="flex-wrap:wrap;margin-bottom:14px;">
                            @if($application->pet->good_with_kids) <span class="tag tag-blue">Kid-friendly</span> @endif
                            @if($application->pet->hypoallergenic) <span class="tag tag-amber">Hypoallergenic</span> @endif
                            @if($application->pet->is_senior) <span class="tag tag-coral">Senior</span> @endif
                        </div>
                        <a href="{{ route('pets.show', $application->pet) }}" class="btn btn-sm"
                            style="width:100%;text-align:center;display:block;">
                            View full profile
                        </a>
                    @else
                        <div style="text-align:center;padding:24px 0;">
                            <div style="font-size:32px;margin-bottom:8px;">🐾</div>
                            <div class="text-muted">This pet has been removed from the shelter.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection