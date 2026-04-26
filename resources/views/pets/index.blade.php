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
            <div class="card" style="text-align:center;padding:48px;">
                <div style="font-size:32px;margin-bottom:12px;">🐾</div>
                <div style="font-size:15px;color:#888;">No pets match your search. Try adjusting your filters.</div>
            </div>
        @else
            <div class="pet-grid">
                @foreach($pets as $pet)
                    @php $primary = $pet->images->firstWhere('is_primary', true) ?? $pet->images->first(); @endphp
                    <a href="{{ route('pets.show', $pet) }}" style="display:block;">
                        <div class="card" style="padding:0;overflow:hidden;transition:border-color 0.15s;"
                            onmouseover="this.style.borderColor='rgba(0,0,0,0.25)'"
                            onmouseout="this.style.borderColor='rgba(0,0,0,0.1)'">

                            {{-- Photo or emoji fallback --}}
                            @if($primary)
                                <div style="height:130px;overflow:hidden;">
                                    <img src="{{ asset('storage/' . $primary->path) }}" alt="{{ $pet->name }}"
                                        style="width:100%;height:100%;object-fit:cover;object-position:center top;">
                                </div>
                            @else
                                <div
                                    style="height:130px;background:{{ ['dog' => '#E1F5EE', 'cat' => '#FBEAF0', 'rabbit' => '#E6F1FB', 'bird' => '#EAF3DE', 'other' => '#F5F4F0'][$pet->species] ?? '#F5F4F0' }};display:flex;align-items:center;justify-content:center;font-size:52px;">
                                    {{ ['dog' => '🐕', 'cat' => '🐈', 'rabbit' => '🐇', 'bird' => '🐦', 'other' => '🐾'][$pet->species] ?? '🐾' }}
                                </div>
                            @endif

                            <div style="padding:14px 16px;">
                                <div style="font-family:'Lora',serif;font-size:16px;font-weight:600;margin-bottom:2px;">
                                    {{ $pet->name }}
                                </div>
                                <div style="font-size:12px;color:#888;margin-bottom:10px;">
                                    {{ $pet->breed ?? ucfirst($pet->species) }} · {{ floor($pet->age_months / 12) }}y ·
                                    {{ ucfirst($pet->size) }}
                                </div>
                                <div class="flex gap-8" style="flex-wrap:wrap;">
                                    <span class="tag tag-teal">{{ ucfirst($pet->activity_level) }}</span>
                                    @if($pet->good_with_kids) <span class="tag tag-blue">Kid-friendly</span> @endif
                                    @if($pet->hypoallergenic) <span class="tag tag-amber">Hypoallergenic</span> @endif
                                    @if($pet->is_senior) <span class="tag tag-coral">Senior</span> @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div style="margin-top:24px;">{{ $pets->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection