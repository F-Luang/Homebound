@extends('layouts.app')
@section('title', $pet->name)

@section('content')
    <div class="page">
        <div style="margin-bottom:16px;">
            <a href="{{ route('pets.index') }}" class="text-muted" style="font-size:13px;">← Back to pets</a>
        </div>

        <div class="two-col" style="align-items:start;">

            {{-- Left: pet info --}}
            <div>
                <div class="card" style="padding:0;overflow:hidden;margin-bottom:16px;">

                    {{-- Photo / placeholder --}}
                    @php
                        $primary = $pet->images->firstWhere('is_primary', true) ?? $pet->images->first();
                        $bgColor = ['dog' => '#E1F5EE', 'cat' => '#FBEAF0', 'rabbit' => '#E6F1FB', 'bird' => '#EAF3DE', 'other' => '#F5F4F0'][$pet->species] ?? '#F5F4F0';
                    @endphp

                    @if($primary)
                        <div style="height:220px;overflow:hidden;">
                            <img src="{{ asset('storage/' . $primary->path) }}"
                                 alt="{{ $pet->name }}"
                                 style="width:100%;height:100%;object-fit:cover;object-position:center top;">
                        </div>
                    @else
                        <div style="height:220px;background:{{ $bgColor }};display:flex;align-items:center;justify-content:center;font-size:80px;">
                            {{ ['dog' => '🐕', 'cat' => '🐈', 'rabbit' => '🐇', 'bird' => '🐦', 'other' => '🐾'][$pet->species] ?? '🐾' }}
                        </div>
                    @endif

                    <div style="padding:20px 24px;">
                        <div class="flex items-center justify-between" style="margin-bottom:6px;">
                            <div style="font-family:'Lora',serif;font-size:24px;font-weight:600;">{{ $pet->name }}</div>
                            <span class="badge badge-{{ $pet->status }}">{{ ucfirst($pet->status) }}</span>
                        </div>
                        <div style="font-size:14px;color:#888;margin-bottom:16px;">
                            {{ $pet->breed ?? ucfirst($pet->species) }} · {{ floor($pet->age_months / 12) }} yr
                            {{ $pet->age_months % 12 }} mo · {{ ucfirst($pet->size) }} · {{ ucfirst($pet->activity_level) }}
                            activity
                        </div>
                        <div class="flex gap-8" style="flex-wrap:wrap;margin-bottom:16px;">
                            @if($pet->good_with_kids) <span class="tag tag-blue">Kid-friendly</span> @endif
                            @if($pet->hypoallergenic) <span class="tag tag-amber">Hypoallergenic</span> @endif
                            @if($pet->is_senior) <span class="tag tag-coral">Senior</span> @endif
                        </div>
                        @if($pet->bio)
                            <p style="font-size:14px;color:#444;line-height:1.7;">{{ $pet->bio }}</p>
                        @endif
                    </div>
                </div>

                {{-- Admin actions --}}
                @if(auth()->user()?->isAdmin())
                    <div class="card">
                        <div style="font-size:13px;font-weight:500;margin-bottom:12px;">Admin actions</div>
                        <div class="flex gap-8">
                            <a href="{{ route('pets.edit', $pet) }}" class="btn btn-sm">Edit pet</a>
                            <a href="{{ route('medical.index', $pet) }}" class="btn btn-sm">View medical</a>
                            <form method="POST" action="{{ route('pets.destroy', $pet) }}"
                                onsubmit="return confirm('Delete {{ $pet->name }}? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right: apply / medical --}}
            <div>
                {{-- Apply card --}}
                @auth
                    @if($pet->status === 'available')
                        <div class="card" style="margin-bottom:16px;">
                            <div style="font-family:'Lora',serif;font-size:16px;font-weight:600;margin-bottom:6px;">Adopt
                                {{ $pet->name }}</div>
                            <div style="font-size:13px;color:#666;margin-bottom:16px;">Tell us a little about yourself and why you'd
                                be a great match.</div>
                            <form method="POST" action="{{ route('applications.store') }}">
                                @csrf
                                <input type="hidden" name="pet_id" value="{{ $pet->id }}">
                                <div class="form-group">
                                    <label class="form-label">Notes (optional)</label>
                                    <textarea name="notes" class="form-input"
                                        placeholder="Tell us about your home, lifestyle, experience with pets…">{{ old('notes') }}</textarea>
                                    @error('notes') <div class="form-error">{{ $message }}</div> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%;">Submit application</button>
                            </form>
                        </div>
                    @else
                        <div class="card" style="margin-bottom:16px;text-align:center;padding:28px;">
                            <div style="font-size:13px;color:#888;">
                                {{ $pet->status === 'pending' ? 'This pet has a pending adoption.' : 'This pet has been adopted.' }}
                                💛
                            </div>
                        </div>
                    @endif
                @else
                    <div class="card" style="margin-bottom:16px;text-align:center;padding:28px;">
                        <div style="font-size:14px;color:#444;margin-bottom:12px;">Sign in to apply for {{ $pet->name }}</div>
                        <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
                    </div>
                @endauth

                {{-- Medical summary (admin/volunteer only) --}}
                @if(auth()->user()?->isAdmin() || auth()->user()?->isVolunteer())
                    <div class="card">
                        <div class="flex items-center justify-between" style="margin-bottom:16px;">
                            <div style="font-size:14px;font-weight:500;">Medical history</div>
                            <a href="{{ route('medical.index', $pet) }}" class="btn btn-sm">Full timeline</a>
                        </div>
                        @forelse($pet->medicalRecords->take(3) as $rec)
                            <div class="tl-item">
                                <div class="tl-dot {{ ['vaccination' => 'tl-dot-green', 'medication' => 'tl-dot-blue', 'checkup' => 'tl-dot-green', 'surgery' => 'tl-dot-amber', 'other' => 'tl-dot-blue'][$rec->record_type] ?? 'tl-dot-blue' }}">
                                    {{ strtoupper(substr($rec->record_type, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="tl-date">{{ $rec->record_date->format('M j, Y') }}</div>
                                    <div class="tl-title">{{ ucfirst($rec->record_type) }}</div>
                                    @if($rec->notes)
                                        <div class="tl-desc">{{ Str::limit($rec->notes, 80) }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">No records yet.</div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection