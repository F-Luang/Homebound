@extends('layouts.app')
@section('title', isset($pet) ? 'Edit ' . $pet->name : 'Add Pet')

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

        <div class="card">
            <form method="POST" action="{{ isset($pet) ? route('pets.update', $pet) : route('pets.store') }}"
                enctype="multipart/form-data">
                @csrf
                @isset($pet) @method('PUT') @endisset

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Name *</label>
                        <input class="form-input" name="name" value="{{ old('name', $pet->name ?? '') }}" required>
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Species *</label>
                        <select class="form-input" name="species" required>
                            @foreach(['dog', 'cat', 'rabbit', 'bird', 'other'] as $s)
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
                        <label class="form-label">Age (months) *</label>
                        <input class="form-input" type="number" name="age_months" min="0"
                            value="{{ old('age_months', $pet->age_months ?? '') }}" required>
                        @error('age_months') <div class="form-error">{{ $message }}</div> @enderror
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
                                <option value="{{ $a }}" {{ old('activity_level', $pet->activity_level ?? '') === $a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea class="form-input" name="bio" rows="4"
                        placeholder="Tell adopters about this pet's personality, history, and needs…">{{ old('bio', $pet->bio ?? '') }}</textarea>
                </div>

                <div class="flex gap-12" style="margin-bottom:16px;flex-wrap:wrap;">
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

                @isset($pet)
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-input" name="status" style="width:200px;">
                            @foreach(['available', 'pending', 'adopted'] as $st)
                                <option value="{{ $st }}" {{ old('status', $pet->status) === $st ? 'selected' : '' }}>
                                    {{ ucfirst($st) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endisset

                <div class="form-group">
                    <label class="form-label">Photos</label>
                    <input class="form-input" type="file" name="images[]" multiple accept="image/*">
                    <div style="font-size:11px;color:#888;margin-top:3px;">First image will be set as the primary photo.
                    </div>
                </div>

                <hr class="divider">
                <div class="flex gap-8">
                    <button type="submit" class="btn btn-primary">{{ isset($pet) ? 'Save changes' : 'Add pet' }}</button>
                    <a href="{{ route('pets.index') }}" class="btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection