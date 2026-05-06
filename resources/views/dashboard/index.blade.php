@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="page">
        <div class="page-header flex items-center justify-between">
            <div>
                <div class="page-title">Welcome back, {{ auth()->user()->name }}</div>
                <div class="page-sub">Here's what's happening at the shelter today.</div>
            </div>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('pets.create') }}" class="btn btn-primary">+ Add pet</a>
            @endif
        </div>

        {{-- Stats row --}}
        <div class="three-col" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));margin-bottom:24px;">
            <div class="card" style="background:#f9fdf9;">
                <div style="font-size:11px;font-weight:500;color:#888;margin-bottom:6px;">Pets available</div>
                <div style="font-size:28px;font-weight:500;color:var(--green);">{{ $availableCount }}</div>
            </div>
            <div class="card" style="background:#fffdf8;">
                <div style="font-size:11px;font-weight:500;color:#888;margin-bottom:6px;">Pending applications</div>
                <div style="font-size:28px;font-weight:500;color:var(--amber);">{{ $pendingCount }}</div>
            </div>
            <div class="card">
                <div style="font-size:11px;font-weight:500;color:#888;margin-bottom:6px;">Adoptions this month</div>
                <div style="font-size:28px;font-weight:500;">{{ $adoptedThisMonth }}</div>
            </div>
            <div class="card">
                <div style="font-size:11px;font-weight:500;color:#888;margin-bottom:6px;">Scheduled meet & greets</div>
                <div style="font-size:28px;font-weight:500;">{{ $scheduledMeetGreets }}</div>
            </div>
        </div>
        {{-- Smart Match CTA for adopters --}}
        @if(!auth()->user()->isAdmin())
            <div class="card"
                style="margin-bottom:24px;background:linear-gradient(135deg,#f0fdf8,#e6f9f0);border-color:rgba(29,158,117,0.2);">
                <div class="flex items-center justify-between">
                    <div>
                        <div style="font-family:'Lora',serif;font-size:16px;font-weight:600;margin-bottom:4px;">
                            Find your perfect match 🐾
                        </div>
                        <div style="font-size:13px;color:#666;">
                            Tell us about your lifestyle and we'll rank the best pets for you.
                        </div>
                    </div>
                    <a href="{{ route('match.index') }}" class="btn btn-primary" style="flex-shrink:0;">
                        Try Smart Match →
                    </a>
                </div>
            </div>
        @endif

        <div class="two-col">
            {{-- Recent applications --}}
            <div class="card">
                <div class="flex items-center justify-between" style="margin-bottom:16px;">
                    <div style="font-size:14px;font-weight:500;">Recent applications</div>
                    <a href="{{ route('applications.index') }}" class="btn btn-sm">View all</a>
                </div>
                @forelse($recentApplications as $app)
                    <div class="flex items-center justify-between"
                        style="padding:8px 0;border-bottom:0.5px solid rgba(0,0,0,0.06);">
                        <div>
                            <div style="font-size:13px;font-weight:500;">{{ $app->user->name }}</div>
                            <div style="font-size:11px;color:#888;">→ {{ $app->pet->name }}</div>
                        </div>
                        <span
                            class="badge badge-{{ str_replace('_', '-', $app->status) }}">{{ ucfirst(str_replace('_', ' ', $app->status)) }}</span>
                    </div>
                @empty
                    <div style="text-align:center;padding:24px 0;">
                        <div style="font-size:24px;margin-bottom:8px;">📋</div>
                        <div class="text-muted" style="font-size:13px;">No applications yet.</div>
                    </div>
                @endforelse
            </div>

            {{-- Recently added pets --}}
            <div class="card">
                <div class="flex items-center justify-between" style="margin-bottom:16px;">
                    <div style="font-size:14px;font-weight:500;">Recently added pets</div>
                    <a href="{{ route('pets.index') }}" class="btn btn-sm">View all</a>
                </div>
                @forelse($recentPets as $pet)
                    <div class="flex items-center justify-between"
                        style="padding:8px 0;border-bottom:0.5px solid rgba(0,0,0,0.06);">
                        <div>
                            <div style="font-size:13px;font-weight:500;">{{ $pet->name }}</div>
                            <div style="font-size:11px;color:#888;">{{ ucfirst($pet->species) }} ·
                                {{ floor($pet->age_months / 12)
                                                                                                                                        }}y
                                {{ $pet->age_months % 12 }}m
                            </div>
                        </div>
                        <span class="badge badge-{{ $pet->status }}">{{ ucfirst($pet->status) }}</span>
                    </div>
                @empty
                    <div style="text-align:center;padding:24px 0;">
                        <div style="font-size:24px;margin-bottom:8px;">🐾</div>
                        <div class="text-muted" style="font-size:13px;">No pets added yet.</div>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('pets.create') }}" class="btn btn-primary btn-sm" style="margin-top:10px;">+ Add first pet</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection