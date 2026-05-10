@extends('layouts.app')
@section('title', $pet->name)

@section('content')
                                <div class="page">
                                    <div style="margin-bottom:16px;">
                                        <a href="{{ route('pets.index') }}" class="text-muted" style="font-size:13px;">← Back to pets</a>
                                    </div>

                                    <div class="two-col" style="align-items:start;">

                                        {{-- ===== LEFT: pet info ===== --}}
                                        <div>
                                            <div class="card" style="padding:0;overflow:hidden;margin-bottom:16px;">

                                                @php
    $primary = $pet->images->firstWhere('is_primary', true) ?? $pet->images->first();
    $bgColor = ['dog' => '#E1F5EE', 'cat' => '#FBEAF0', 'rabbit' => '#E6F1FB', 'bird' => '#EAF3DE', 'other' => '#F5F4F0'][$pet->species] ?? '#F5F4F0';
                                                @endphp

                                                {{-- Main photo --}}
                                                @if($primary)
                                                    <div style="height:280px;overflow:hidden;position:relative;">
                                                        <img id="main-photo"
                                                            src="{{ str_starts_with($primary->path, 'http') ? $primary->path : asset('storage/' . $primary->path) }}" alt="{{ $pet->name }}"
                                                            style="width:100%;height:100%;object-fit:cover;object-position:center top;transition:opacity 0.2s;">
                                                    </div>
                                                @else
                                                    <div
                                                        style="height:220px;background:{{ $bgColor }};display:flex;align-items:center;justify-content:center;font-size:80px;">
                                                        {{ ['dog' => '🐕', 'cat' => '🐈', 'rabbit' => '🐇', 'bird' => '🐦', 'other' => '🐾'][$pet->species] ?? '🐾' }}
                                                    </div>
                                                @endif

                                                <div style="padding:20px 24px;">
                                                    <div class="flex items-center justify-between" style="margin-bottom:6px;">
                                                        <div style="font-family:'Lora',serif;font-size:24px;font-weight:600;">{{ $pet->name }}</div>
                                                        <span class="badge badge-{{ $pet->status }}">{{ ucfirst($pet->status) }}</span>
                                                    </div>
                                                    <div style="font-size:14px;color:#888;margin-bottom:12px;">
                                                        {{ $pet->breed ?? ucfirst($pet->species) }} · {{ floor($pet->age_months / 12) }} yr
                                                        {{ $pet->age_months % 12 }} mo · {{ ucfirst($pet->size) }} · {{ ucfirst($pet->activity_level) }}
                                                        activity
                                                        @if($pet->weight_kg) · {{ $pet->weight_kg }} kg @endif
                                                    </div>
                                                    <div class="flex" style="flex-wrap:wrap;gap:4px;margin-bottom:12px;">
                                                        @if($pet->good_with_kids) <span class="tag tag-blue">Kid-friendly</span> @endif
                                                        @if($pet->hypoallergenic) <span class="tag tag-amber">Hypoallergenic</span> @endif
                                                        @if($pet->is_senior) <span class="tag tag-coral">Senior</span> @endif
                                                        @if($pet->special_note)
                                                            <span class="tag" style="background:#EEEDFE;color:#3C3489;">{{ $pet->special_note }}</span>
                                                        @endif
                                                    </div>
                                                    @if($pet->bio)
                                                        <p style="font-size:14px;color:#444;line-height:1.7;margin-bottom:0;">{{ $pet->bio }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- ===== CARE INFO ===== --}}
                                            @php
    $careFields = [
        'food' => ['label' => 'Food', 'icon' => 'food.png', 'bg' => '#FFF3E0'],
        'feeding_time' => ['label' => 'Feeding time', 'icon' => 'feeding-time.png', 'bg' => '#FAEEDA'],
        'water' => ['label' => 'Water', 'icon' => 'water.png', 'bg' => '#d8e2ff'],
        'medication' => ['label' => 'Medication', 'icon' => 'icon-medication.svg', 'bg' => '#E1F5EE'],
        'vet' => ['label' => 'Vet', 'icon' => 'vet.png', 'bg' => '#deffe1'],
    ];
    $hasCareInfo = collect($careFields)->keys()->some(fn($f) => !empty($pet->$f));
                                            @endphp

                                            @if($hasCareInfo)
                                                <div class="card" style="margin-bottom:16px;">
                                                    <div style="font-size:13px;font-weight:500;margin-bottom:14px;">Care information</div>
                                                    <div style="display:flex;flex-direction:column;gap:0;">
                                                        @foreach($careFields as $field => $meta)
                                                            @if(!empty($pet->$field))
                                                                <div
                                                                    style="display:flex;align-items:center;gap:12px;padding:9px 0;border-bottom:0.5px solid rgba(0,0,0,0.06);">
                                                                    <div
                                                                        style="width:28px;height:28px;border-radius:8px;background:{{ $meta['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                                        <img src="{{ asset('assets/icons/' . $meta['icon']) }}" alt="{{ $meta['label'] }}"
                                                                            style="width:18px;height:18px;object-fit:contain;">
                                                                    </div>
                                                                    <div style="font-size:12px;color:#888;width:90px;flex-shrink:0;">{{ $meta['label'] }}</div>
                                                                    <div style="font-size:13px;color:#1a1a18;font-weight:500;">{{ $pet->$field }}</div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Admin actions --}}
                        @if(auth()->user()?->isAdmin())
                            <div class="card">
                                <div style="font-size:13px;font-weight:500;margin-bottom:12px;">Admin actions</div>
                                <div class="flex gap-8">
                                    <a href="{{ route('pets.edit', $pet) }}" class="btn btn-sm">Edit pet</a>
                                    <a href="{{ route('medical.index', $pet) }}" class="btn btn-sm">View medical</a>
                                    <form method="POST" action="{{ route('pets.destroy', $pet) }}"
                                        data-confirm="Delete {{ $pet->name }}? This cannot be undone."
                                        data-title="Delete pet" data-confirm-text="Delete">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endif



                                        </div>
                                        {{-- ===== END LEFT COLUMN ===== --}}

                                        {{-- ===== RIGHT: apply + medical + gallery ===== --}}
                                        <div>

                                            {{-- Apply card --}}
                                            @auth
                                                @if($pet->status === 'available' && !auth()->user()->isAdmin())
                                                    <div class="card" style="margin-bottom:16px;">
                                                        <div style="font-family:'Lora',serif;font-size:16px;font-weight:600;margin-bottom:6px;">
                                                            Adopt {{ $pet->name }}
                                                        </div>
                                                        <div style="font-size:13px;color:#666;margin-bottom:16px;">
                                                            Tell us a little about yourself and why you'd be a great match.
                                                        </div>
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
                                                <div class="card" style="margin-bottom:16px;">
                                                    <div class="flex items-center justify-between" style="margin-bottom:16px;">
                                                        <div style="font-size:14px;font-weight:500;">Medical history</div>
                                                        <a href="{{ route('medical.index', $pet) }}" class="btn btn-sm">Full timeline</a>
                                                    </div>
                                                    @forelse($pet->medicalRecords->take(3) as $rec)
                                                        <div class="tl-item">
                                                            <div
                                                                class="tl-dot {{ ['vaccination' => 'tl-dot-green', 'medication' => 'tl-dot-blue', 'checkup' => 'tl-dot-green', 'surgery' => 'tl-dot-amber', 'other' => 'tl-dot-blue'][$rec->record_type] ?? 'tl-dot-blue' }}">
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

                                            {{-- ===== PHOTO GALLERY (right column, visible to all) ===== --}}
                                            @if($pet->images->count() > 0)
                                                <div class="card">
                                                    <div style="font-size:14px;font-weight:500;margin-bottom:14px;">
                                                        Photos <span style="font-size:12px;color:#888;font-weight:400;">({{ $pet->images->count() }})</span>
                                                    </div>
                                                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:8px;">
                                                        @foreach($pet->images as $img)
                                                            @php
                                                                $imgUrl = str_starts_with($img->path, 'http') ? $img->path : asset('storage/' . $img->path);
                                                            @endphp
                                                            <div onclick="openLightbox('{{ $imgUrl }}', '{{ $pet->name }}')"
                                                                style="cursor:zoom-in;border-radius:8px;overflow:hidden;border:0.5px solid rgba(0,0,0,0.08);transition:transform 0.15s,box-shadow 0.15s;"
                                                                onmouseover="this.style.transform='scale(1.03)';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.12)'"
                                                                onmouseout="this.style.transform='scale(1)';this.style.boxShadow='none'">
                                                                <img src="{{ $imgUrl }}" alt="{{ $pet->name }}" style="width:100%;height:90px;object-fit:cover;display:block;">
                                                                @if($img->is_primary)
                                                                    <div style="background:#1D9E75;color:white;font-size:10px;font-weight:500;text-align:center;padding:3px 0;">
                                                                        Main photo
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div style="font-size:11px;color:#aaa;margin-top:10px;text-align:center;">
                                                        Click any photo to expand
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        {{-- ===== END RIGHT COLUMN ===== --}}

                                    </div>
                                </div>

                                {{-- ===== LIGHTBOX ===== --}}
                                <div id="lightbox" onclick="closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.88);z-index:9999;
                                                                            align-items:center;justify-content:center;cursor:zoom-out;padding:24px;">
                                    <div style="position:relative;max-width:90vw;max-height:90vh;">
                                        <img id="lightbox-img" src="" alt="" style="max-width:90vw;max-height:88vh;object-fit:contain;border-radius:10px;
                                                                                    box-shadow:0 8px 48px rgba(0,0,0,0.6);display:block;">
                                        <div id="lightbox-caption"
                                            style="text-align:center;color:rgba(255,255,255,0.7);font-size:13px;margin-top:10px;"></div>
                                        <button onclick="closeLightbox()" style="position:absolute;top:-14px;right:-14px;width:32px;height:32px;
                                                                                       border-radius:50%;background:#fff;border:none;font-size:18px;
                                                                                       cursor:pointer;display:flex;align-items:center;justify-content:center;
                                                                                       box-shadow:0 2px 8px rgba(0,0,0,0.3);line-height:1;">×</button>
                                    </div>
                                </div>

                                <script>
                                    function openLightbox(src, name) {
                                        const lb = document.getElementById('lightbox');
                                        document.getElementById('lightbox-img').src = src;
                                        document.getElementById('lightbox-caption').textContent = name;
                                        lb.style.display = 'flex';
                                        document.body.style.overflow = 'hidden';
                                    }
                                    function closeLightbox() {
                                        document.getElementById('lightbox').style.display = 'none';
                                        document.body.style.overflow = '';
                                    }
                                    document.addEventListener('keydown', function (e) {
                                        if (e.key === 'Escape') closeLightbox();
                                    });
                                </script>

@endsection