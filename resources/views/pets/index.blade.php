@extends('layouts.app')
@section('title', 'Browse Pets')

@section('content')
    <div class="page">
        <div class="page-header flex items-center justify-between">
            <div>
                <div class="page-title">Find your companion</div>
                <div class="page-sub">Browse all available pets at the shelter.</div>
            </div>
            @if(auth()->user()?->isAdmin())
                <a href="{{ route('pets.create') }}" class="btn btn-primary">+ Add pet</a>
            @endif
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('pets.index') }}" class="card" style="margin-bottom:20px;padding:14px 20px;">
            <div class="flex gap-12 items-center" style="flex-wrap:wrap;">
                <input class="form-input" style="width:220px;" name="search" placeholder="Search by name, breed…"
                    value="{{ request('search') }}">
                <select class="form-input" style="width:140px;" name="species">
                    <option value="">All species</option>
                    @foreach(['dog', 'cat', 'rabbit', 'bird', 'other'] as $s)
                        <option value="{{ $s }}" {{ request('species') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <select class="form-input" style="width:140px;" name="size">
                    <option value="">Any size</option>
                    @foreach(['small', 'medium', 'large'] as $s)
                        <option value="{{ $s }}" {{ request('size') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <select class="form-input" style="width:160px;" name="activity_level">
                    <option value="">Any activity</option>
                    <option value="low" {{ request('activity_level') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="moderate" {{ request('activity_level') === 'moderate' ? 'selected' : '' }}>Moderate
                    </option>
                    <option value="high" {{ request('activity_level') === 'high' ? 'selected' : '' }}>High</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
                @if(request()->hasAny(['search', 'species', 'size', 'activity_level']))
                    <a href="{{ route('pets.index') }}" class="btn">Clear</a>
                @endif
            </div>
        </form>

        {{-- Grid --}}
        @if($pets->isEmpty())
            <div class="card" style="text-align:center;padding:64px 24px;">
                <div
                    style="width:72px;height:72px;border-radius:50%;background:#E6F1FB;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:32px;">
                    🐾
                </div>
                <div style="font-family:'Lora',serif;font-size:20px;font-weight:600;margin-bottom:8px;">
                    No pets found
                </div>
                <div class="text-muted" style="margin-bottom:24px;max-width:340px;margin-left:auto;margin-right:auto;">
                    @if(request()->hasAny(['search', 'species', 'size', 'activity_level']))
                        No pets match your search filters. Try adjusting or clearing them.
                    @else
                        No pets have been added to the shelter yet.
                    @endif
                </div>
                @if(request()->hasAny(['search', 'species', 'size', 'activity_level']))
                    <a href="{{ route('pets.index') }}" class="btn btn-primary">Clear filters</a>
                @elseif(auth()->user()?->isAdmin())
                    <a href="{{ route('pets.create') }}" class="btn btn-primary">+ Add first pet</a>
                @endif
            </div>

        @else
            <div class="pet-grid">
                @foreach($pets as $pet)
                                @php
                    $primary = $pet->images->firstWhere('is_primary', true) ?? $pet->images->first();
                    $bgColor = ['dog' => '#E1F5EE', 'cat' => '#FBEAF0', 'rabbit' => '#E6F1FB', 'bird' => '#EAF3DE', 'other' => '#F5F4F0'][$pet->species] ?? '#F5F4F0';
                    $emoji = ['dog' => '🐕', 'cat' => '🐈', 'rabbit' => '🐇', 'bird' => '🐦', 'other' => '🐾'][$pet->species] ?? '🐾';
                                @endphp

                                <div style="position:relative;">
                                {{-- Heart / save button (adopters only) --}}
                                @auth
                                    @if(auth()->user()->role === 'adopter')
                                        @php $saved = isset($savedPetIds) && $savedPetIds->has($pet->id); @endphp
                                        <form class="fav-form"
                                            data-saved="{{ $saved ? 'true' : 'false' }}"
                                            data-store="{{ route('favourites.store', $pet) }}"
                                            data-destroy="{{ route('favourites.destroy', $pet) }}"
                                            style="position:absolute;top:10px;right:10px;z-index:3;">
                                            @csrf
                                            <button type="submit" title="{{ $saved ? 'Remove from saved' : 'Save pet' }}"
                                                style="background:rgba(255,255,255,0.92);border:none;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 4px rgba(0,0,0,0.18);">
                                                {{ $saved ? '❤️' : '🤍' }}
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                                <a href="{{ route('pets.show', $pet) }}" style="display:block;">
                                    <div class="card" style="padding:0;overflow:hidden;transition:border-color 0.15s;position:relative;"
                                        onmouseover="this.style.borderColor='rgba(0,0,0,0.25)'"
                                        onmouseout="this.style.borderColor='rgba(0,0,0,0.1)'">

                                        {{-- Status overlay --}}
                                        @if($pet->status !== 'available')
                                            <div
                                                style="position:absolute;inset:0;z-index:2;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;
                                                                                                                                                                                                        background:{{ $pet->status === 'adopted' ? 'rgba(60,52,137,0.75)' : 'rgba(186,117,23,0.75)' }};
                                                                                                                                                                                                        backdrop-filter:blur(2px);">
                                                <div style="font-size:28px;">{{ $pet->status === 'adopted' ? '🏡' : '⏳' }}</div>
                                                <div style="font-size:13px;font-weight:600;color:white;text-align:center;">
                                                    {{ $pet->status === 'adopted' ? 'Adopted' : 'Pending adoption' }}
                                                </div>
                                                <div style="font-size:11px;color:rgba(255,255,255,0.8);text-align:center;padding:0 12px;">
                                                    {{ $pet->status === 'adopted' ? $pet->name . ' has found a home!' : 'An application is being processed.' }}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Pet image --}}
                                        @if($primary)
                                            <div style="height:130px;overflow:hidden;">
                                                <img src="{{ str_starts_with($primary->path, 'http') ? $primary->path : asset('storage/' . $primary->path) }}" alt="{{ $pet->name }}"
                                                    style="width:100%;height:100%;object-fit:cover;object-position:center top;">
                                            </div>
                                        @else
                                            <div
                                                style="height:130px;background:{{ $bgColor }};display:flex;align-items:center;justify-content:center;font-size:52px;">
                                                {{ $emoji }}
                                            </div>
                                        @endif

                                        {{-- Pet info --}}
                                        <div style="padding:14px 16px;">
                                            <div style="font-family:'Lora',serif;font-size:16px;font-weight:600;margin-bottom:2px;">
                                                {{ $pet->name }}
                                            </div>
                                            <div style="font-size:12px;color:#888;margin-bottom:10px;">
                                                {{ $pet->breed ?? ucfirst($pet->species) }} · {{ floor($pet->age_months / 12) }}y
                                                @if(($pet->gender ?? 'unknown') !== 'unknown') · {{ ucfirst($pet->gender) }} @endif
                                                · {{ ucfirst($pet->size) }}
                                            </div>
                                            <div class="flex" style="flex-wrap:wrap;gap:4px;">
                                                <span class="tag tag-teal">{{ ucfirst($pet->activity_level) }}</span>
                                                @if($pet->good_with_kids) <span class="tag tag-blue">Kid-friendly</span> @endif
                                                @if($pet->hypoallergenic) <span class="tag tag-amber">Hypoallergenic</span> @endif
                                                @if($pet->is_senior) <span class="tag tag-coral">Senior</span> @endif
                                                @if($pet->special_note)
                                                    <span class="tag" style="background:#EEEDFE;color:#3C3489;">{{ $pet->special_note }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                </div>{{-- end relative wrapper --}}
                @endforeach
            </div>
            <div style="margin-top:24px;">{{ $pets->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection