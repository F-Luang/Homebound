@extends('layouts.app')
@section('title', 'Edit ' . $pet->name)

@section('content')
    <div class="page" style="max-width:720px;">
        <div style="margin-bottom:20px;">
            <a href="{{ route('pets.index') }}" class="text-muted" style="font-size:13px;">← Back to pets</a>
        </div>

        <div class="page-header">
            <div class="page-title">{{ isset($pet) ? 'Edit ' . $pet->name : 'Add a new pet' }}</div>
            <div class="page-sub">
                {{ isset($pet) ? 'Update this pet\'s profile.' : 'Fill in the details to list a new pet for adoption.' }}
            </div>
        </div>

        <form method="POST" action="{{ isset($pet) ? route('pets.update', $pet) : route('pets.store') }}"
            enctype="multipart/form-data">
            @csrf
            @isset($pet) @method('PUT') @endisset

            {{-- ===== BASIC INFO ===== --}}
            <div class="card" style="margin-bottom:16px;">
                <div
                    style="font-size:12px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                    Basic information
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Name *</label>
                        <input class="form-input" name="name" value="{{ old('name', $pet->name ?? '') }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Species *</label>
                        <select class="form-input" name="species" required>
                            @foreach(['dog', 'cat', 'rabbit', 'bird', 'hamster', 'other'] as $s)
                                <option value="{{ $s }}" {{ old('species', $pet->species ?? '') === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        @error('species') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Breed</label>
                        <input class="form-input" name="breed" value="{{ old('breed', $pet->breed ?? '') }}"
                            placeholder="e.g. Labrador Retriever">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gender *</label>
                        <select class="form-input" name="gender" required>
                            @foreach(['male' => 'Male', 'female' => 'Female', 'unknown' => 'Unknown'] as $val => $label)
                                <option value="{{ $val }}" {{ old('gender', $pet->gender ?? 'unknown') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Age (months) *</label>
                        <input class="form-input" type="number" name="age_months" min="0"
                            value="{{ old('age_months', $pet->age_months ?? '') }}" required>
                        @error('age_months') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight (kg)</label>
                        <input class="form-input" type="number" step="0.01" min="0.01" name="weight_kg"
                            value="{{ old('weight_kg', $pet->weight_kg ?? '') }}" placeholder="e.g. 0.08 for a small bird">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Size *</label>
                        <select class="form-input" name="size" required>
                            @foreach(['small', 'medium', 'large'] as $s)
                                <option value="{{ $s }}" {{ old('size', $pet->size ?? '') === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Activity level *</label>
                        <select class="form-input" name="activity_level" required>
                            @foreach(['low', 'moderate', 'high'] as $a)
                                <option value="{{ $a }}" {{ old('activity_level', $pet->activity_level ?? '') === $a ? 'selected' : '' }}>
                                    {{ ucfirst($a) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Special note</label>
                    <input class="form-input" name="special_note"
                        value="{{ old('special_note', $pet->special_note ?? '') }}"
                        placeholder="e.g. Needs daily walk (30 min), Indoor only">
                    <div style="font-size:11px;color:#888;margin-top:3px;">Shown as a badge on the pet's profile.</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea class="form-input" name="bio" rows="4"
                        placeholder="Tell adopters about this pet's personality, history, and needs…">{{ old('bio', $pet->bio ?? '') }}</textarea>
                </div>

                <div class="flex gap-12" style="flex-wrap:wrap;">
                    <label class="flex items-center gap-8" style="font-size:13px;cursor:pointer;">
                        <input type="checkbox" name="good_with_kids" value="1" {{ old('good_with_kids', $pet->good_with_kids ?? false) ? 'checked' : '' }}>
                        Good with kids
                    </label>
                    <label class="flex items-center gap-8" style="font-size:13px;cursor:pointer;">
                        <input type="checkbox" name="hypoallergenic" value="1" {{ old('hypoallergenic', $pet->hypoallergenic ?? false) ? 'checked' : '' }}>
                        Hypoallergenic
                    </label>
                    <label class="flex items-center gap-8" style="font-size:13px;cursor:pointer;">
                        <input type="checkbox" name="is_senior" value="1" {{ old('is_senior', $pet->is_senior ?? false) ? 'checked' : '' }}>
                        Senior pet
                    </label>
                </div>
            </div>

            {{-- ===== CARE INFO ===== --}}
            <div class="card" style="margin-bottom:16px;">
                <div
                    style="font-size:12px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                    Care information
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Food</label>
                        <input class="form-input" name="food" value="{{ old('food', $pet->food ?? '') }}"
                            placeholder="e.g. Dry kibble, 2× daily">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Feeding time</label>
                        <input class="form-input" name="feeding_time"
                            value="{{ old('feeding_time', $pet->feeding_time ?? '') }}"
                            placeholder="e.g. 7:00 AM & 6:00 PM">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Water</label>
                        <input class="form-input" name="water" value="{{ old('water', $pet->water ?? '') }}"
                            placeholder="e.g. Fresh, always available">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Medication</label>
                        <input class="form-input" name="medication" value="{{ old('medication', $pet->medication ?? '') }}"
                            placeholder="e.g. Flea tablet, monthly — or None">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Veterinarian</label>
                    <input class="form-input" name="vet" value="{{ old('vet', $pet->vet ?? '') }}"
                        placeholder="e.g. Dr. Santos · 082-111-0001">
                </div>
            </div>

            {{-- ===== STATUS (edit only) ===== --}}
            @isset($pet)
                <div class="card" style="margin-bottom:16px;">
                    <div
                        style="font-size:12px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                        Listing status
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Status</label>
                        <select class="form-input" name="status" style="width:200px;">
                            @foreach(['available', 'pending', 'adopted'] as $st)
                                <option value="{{ $st }}" {{ old('status', $pet->status) === $st ? 'selected' : '' }}>
                                    {{ ucfirst($st) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endisset

            {{-- ===== PHOTOS ===== --}}
            <div class="card" style="margin-bottom:16px;">
                <div
                    style="font-size:12px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px;">
                    Photos
                </div>

                {{-- Separate upload form — independent from the main edit form --}}
                @isset($pet)
                    <form method="POST" action="{{ route('pet-images.store', $pet) }}" enctype="multipart/form-data"
                        style="margin-bottom:0;">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Add photos</label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input class="form-input" type="file" name="images[]" multiple
                                    accept="image/jpeg,image/png,image/webp" style="flex:1;">
                                <button type="submit" class="btn btn-primary btn-sm" style="white-space:nowrap;flex-shrink:0;">
                                    Upload photos
                                </button>
                            </div>
                            <div style="font-size:11px;color:#888;margin-top:4px;">
                                Max 5 files · jpg, png, webp · 3MB each. First uploaded becomes main if none set.
                            </div>
                            @error('images.*') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </form>
                @else
                    {{-- On create page — upload is part of the main form --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Upload photos</label>
                        <input class="form-input" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp">
                        <div style="font-size:11px;color:#888;margin-top:4px;">
                            First image will be set as the primary photo. Max 5 files, 3MB each.
                        </div>
                        @error('images.*') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                @endisset

                {{-- Gallery manager — only visible when editing an existing pet --}}
                @isset($pet)
                    @if($pet->images->count() > 0)
                        <hr class="divider">
                        <div
                            style="font-size:12px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:14px;">
                            Manage existing photos
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;">
                            @foreach($pet->images as $img)
                                <div
                                    style="border-radius:8px;overflow:hidden;border:2px solid {{ $img->is_primary ? '#1D9E75' : 'rgba(0,0,0,0.08)' }};">
                                    <img src="{{ str_starts_with($img->path, 'http') ? $img->path : asset('storage/' . $img->path) }}" alt="{{ $pet->name }}"
                                        style="width:100%;height:90px;object-fit:cover;display:block;">
                                    <div style="padding:5px;display:flex;flex-direction:column;gap:4px;background:#fafaf8;">
                                        @if(!$img->is_primary)
                                            <form method="POST" action="{{ route('pet-images.setPrimary', [$pet, $img]) }}">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm" style="width:100%;font-size:10px;padding:3px 0;">
                                                <button class="btn btn-sm" style="width:100%;font-size:10px;padding:3px 0;">
                                                    Set as main
                                                </button>
                                            </form>
                                        @else
                                            <div style="font-size:10px;text-align:center;color:#1D9E75;font-weight:500;padding:3px 0;">
                                                ✓ Main photo
                                            </div>
                                        @endif
                                        <form method="POST" action="{{ route('pet-images.destroy', [$pet, $img]) }}"
                                            onsubmit="return confirm('Delete this photo?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm" style="width:100%;font-size:10px;padding:3px 0;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <hr class="divider">
                        <div style="font-size:12px;color:#aaa;">
                            No photos uploaded yet. Use the field above to add some.
                        </div>
                    @endif
                @endisset
            </div>

            {{-- ===== ACTIONS ===== --}}
            <div class="flex gap-8">
                <button type="submit" class="btn btn-primary">{{ isset($pet) ? 'Save changes' : 'Add pet' }}</button>
                <a href="{{ route('pets.index') }}" class="btn">Cancel</a>
            </div>

        </form>
    </div>
@endsection