@extends('layouts.app')
@section('title', $pet->name.' — Medical Records')

@section('content')
<div class="page">
    <div style="margin-bottom:16px;">
        <a href="{{ route('pets.show', $pet) }}" class="text-muted" style="font-size:13px;">← Back to {{ $pet->name
            }}</a>
    </div>

    <div class="page-header flex items-center justify-between">
        <div>
            <div class="page-title">{{ $pet->name }}'s medical history</div>
            <div class="page-sub">{{ ucfirst($pet->species) }} · {{ $pet->breed ?? '' }} · {{ floor($pet->age_months/12)
                }}y {{ $pet->age_months%12 }}m</div>
        </div>
    </div>

    <div class="two-col" style="align-items:start;">

        {{-- Timeline --}}
        <div class="card">
            <div style="font-size:14px;font-weight:500;margin-bottom:20px;">Timeline</div>
            @if($records->isEmpty())
            <div class="text-muted">No medical records yet.</div>
            @else
            <div class="timeline">
                @foreach($records as $rec)
                <div class="tl-item">
                    <div
                        class="tl-dot {{ ['vaccination'=>'tl-dot-green','medication'=>'tl-dot-blue','checkup'=>'tl-dot-green','surgery'=>'tl-dot-amber','other'=>'tl-dot-blue'][$rec->record_type] ?? 'tl-dot-blue' }}">
                        {{ strtoupper(substr($rec->record_type,0,1)) }}
                    </div>
                    <div style="flex:1;">
                        <div class="tl-date">{{ $rec->record_date->format('M j, Y') }} · Logged by {{
                            $rec->recordedBy->name }}</div>
                        <div class="tl-title">{{ ucfirst($rec->record_type) }}</div>
                        @if($rec->notes) <div class="tl-desc">{{ $rec->notes }}</div> @endif
                        @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('medical.destroy', $rec) }}" style="margin-top:6px;"
                            onsubmit="return confirm('Delete this record?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Log new record (admin only) --}}
        @if(auth()->user()->isAdmin())
        <div class="card">
            <div style="font-size:14px;font-weight:500;margin-bottom:16px;">Log new record</div>
            <form method="POST" action="{{ route('medical.store') }}">
                @csrf
                <input type="hidden" name="pet_id" value="{{ $pet->id }}">
                <div class="form-group">
                    <label class="form-label">Record type *</label>
                    <select class="form-input" name="record_type" required>
                        @foreach(['vaccination','medication','checkup','surgery','other'] as $t)
                        <option value="{{ $t }}" {{ old('record_type')===$t ? 'selected' : '' }}>{{ ucfirst($t) }}
                        </option>
                        @endforeach
                    </select>
                    @error('record_type') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Date *</label>
                    <input class="form-input" type="date" name="record_date"
                        value="{{ old('record_date', date('Y-m-d')) }}" required>
                    @error('record_date') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-input" name="notes" rows="4"
                        placeholder="Describe the procedure, dosage, observations…">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Save record</button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection