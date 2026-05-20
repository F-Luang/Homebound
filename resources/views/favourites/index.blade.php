@extends('layouts.app')
@section('title', 'Saved Pets')

@section('content')
<div class="page">
    <div class="page-header">
        <div class="page-title">Saved pets</div>
        <div class="page-sub">Pets you've hearted — apply before someone else does.</div>
    </div>

    @if($favourites->isEmpty())
        <div class="card" style="text-align:center;padding:64px 24px;">
            <div style="font-size:48px;margin-bottom:16px;">🤍</div>
            <div style="font-family:'Lora',serif;font-size:20px;font-weight:600;margin-bottom:8px;">No saved pets yet</div>
            <div class="text-muted" style="margin-bottom:24px;">Hit the heart on any pet profile to save them here.</div>
            <a href="{{ route('pets.index') }}" class="btn btn-primary">Browse pets</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
            @foreach($favourites as $fav)
                @php
                    $pet = $fav->pet;
                    $primary = $pet->images->firstWhere('is_primary', true) ?? $pet->images->first();
                    $imgUrl  = $primary ? (str_starts_with($primary->path,'http') ? $primary->path : asset('storage/'.$primary->path)) : null;
                    $emojis  = ['dog'=>'🐕','cat'=>'🐈','rabbit'=>'🐇','bird'=>'🐦','hamster'=>'🐹','other'=>'🐾'];
                @endphp
                <div class="card" style="padding:0;overflow:hidden;position:relative;">
                    {{-- Remove heart --}}
                    <form method="POST" action="{{ route('favourites.destroy', $pet) }}" style="position:absolute;top:10px;right:10px;z-index:2;">
                        @csrf @method('DELETE')
                        <button type="submit" title="Remove from saved"
                            style="background:rgba(255,255,255,0.9);border:none;border-radius:50%;width:32px;height:32px;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;box-shadow:0 1px 4px rgba(0,0,0,0.15);">
                            ❤️
                        </button>
                    </form>

                    {{-- Pet image --}}
                    <a href="{{ route('pets.show', $pet) }}">
                        <div style="height:160px;background:#E1F5EE;display:flex;align-items:center;justify-content:center;font-size:56px;">
                            @if($imgUrl)
                                <img src="{{ $imgUrl }}" alt="{{ $pet->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                {{ $emojis[$pet->species] ?? '🐾' }}
                            @endif
                        </div>
                    </a>

                    <div style="padding:14px 16px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                            <a href="{{ route('pets.show', $pet) }}"
                               style="font-family:'Lora',serif;font-size:16px;font-weight:600;color:var(--green);">
                                {{ $pet->name }}
                            </a>
                            <span class="badge badge-{{ $pet->status === 'available' ? 'available' : ($pet->status === 'pending' ? 'pending' : 'adopted') }}">
                                {{ ucfirst($pet->status) }}
                            </span>
                        </div>
                        <div style="font-size:12px;color:var(--ink-3);margin-bottom:10px;">
                            {{ $pet->breed ?? ucfirst($pet->species) }} · {{ floor($pet->age_months / 12) }}y {{ $pet->age_months % 12 }}m · {{ ucfirst($pet->size) }}
                        </div>
                        @if($pet->status === 'available')
                            <a href="{{ route('pets.show', $pet) }}" class="btn btn-primary btn-sm" style="width:100%;text-align:center;display:block;">
                                Apply to adopt
                            </a>
                        @elseif($pet->status === 'pending')
                            <div style="font-size:12px;color:var(--amber);padding:6px 0;">⏳ Application in progress — check back soon</div>
                        @else
                            <div style="font-size:12px;color:var(--ink-3);padding:6px 0;">This pet has found a home 🏠</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
