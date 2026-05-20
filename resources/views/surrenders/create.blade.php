@extends('layouts.app')
@section('title', 'Surrender a Pet')

@section('content')
<div class="page" style="max-width:700px;">
    <div class="page-header">
        <div class="page-title">Surrender a pet</div>
        <div class="page-sub">We understand that life circumstances change. Fill in this form and our team will reach out within 48 hours.</div>
    </div>

    <div class="card" style="margin-bottom:16px;background:var(--amber-light);border:0.5px solid var(--amber);">
        <div style="font-size:13px;color:var(--amber);line-height:1.6;">
            <strong>Before surrendering</strong> — please consider fostering as a temporary option, or reaching out to friends and family.
            Surrendering is always a last resort, and we'll work with you to find the best solution.
        </div>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('surrenders.store') }}">
            @csrf

            <div style="font-size:12px;font-weight:600;color:var(--ink-3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:14px;">Your information</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Full name *</label>
                    <input type="text" name="submitter_name" class="form-input" value="{{ old('submitter_name') }}" required>
                    @error('submitter_name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="submitter_email" class="form-input" value="{{ old('submitter_email') }}" required>
                    @error('submitter_email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Phone number (optional)</label>
                    <input type="text" name="submitter_phone" class="form-input" value="{{ old('submitter_phone') }}" placeholder="+63 900 000 0000">
                </div>
            </div>

            <div style="border-top:0.5px solid var(--border);margin:20px 0 16px;"></div>
            <div style="font-size:12px;font-weight:600;color:var(--ink-3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:14px;">About the pet</div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Pet name *</label>
                    <input type="text" name="pet_name" class="form-input" value="{{ old('pet_name') }}" required>
                    @error('pet_name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Species *</label>
                    <select name="species" class="form-input" required>
                        <option value="">Select…</option>
                        @foreach(['dog','cat','rabbit','bird','hamster','other'] as $s)
                            <option value="{{ $s }}" {{ old('species') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    @error('species') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Breed (optional)</label>
                    <input type="text" name="breed" class="form-input" value="{{ old('breed') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Approximate age (years)</label>
                    <input type="number" name="age_years" class="form-input" value="{{ old('age_years') }}" min="0" max="30">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Urgency *</label>
                <select name="urgency" class="form-input" required>
                    <option value="low"    {{ old('urgency') === 'low'    ? 'selected' : '' }}>🟢 Low — flexible, within a few months</option>
                    <option value="medium" {{ old('urgency','medium') === 'medium' ? 'selected' : '' }}>🟡 Medium — within a few weeks</option>
                    <option value="high"   {{ old('urgency') === 'high'   ? 'selected' : '' }}>🔴 High — urgent, within days</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Reason for surrendering *</label>
                <textarea name="reason" class="form-input" rows="4" required
                    placeholder="Please explain your situation — we won't judge, we just want to understand so we can help.">{{ old('reason') }}</textarea>
                @error('reason') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Health notes (optional)</label>
                    <textarea name="health_notes" class="form-input" rows="3"
                        placeholder="Vaccinations, medications, vet history…">{{ old('health_notes') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Behavioral notes (optional)</label>
                    <textarea name="behavioral_notes" class="form-input" rows="3"
                        placeholder="Good with kids, other pets, any quirks…">{{ old('behavioral_notes') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit surrender request</button>
        </form>
    </div>
</div>
@endsection
