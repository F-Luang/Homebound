@extends('layouts.app')
@section('title', 'Share Your Story')

@section('content')
<div class="page" style="max-width:680px;">
    <div style="margin-bottom:16px;">
        <a href="{{ route('stories.index') }}" class="text-muted" style="font-size:13px;">← Back to stories</a>
    </div>
    <div class="page-header">
        <div class="page-title">Share your story</div>
        <div class="page-sub">Tell the world about your new companion. Your story helps other families take the leap.</div>
    </div>

    @if($completedApplications->isEmpty())
        <div class="card" style="text-align:center;padding:48px 24px;">
            <div style="font-size:36px;margin-bottom:12px;">🐾</div>
            <div style="font-family:'Lora',serif;font-size:18px;font-weight:600;margin-bottom:8px;">No completed adoptions found</div>
            <div class="text-muted">You can share a story once your adoption is marked as completed — or you may have already submitted one!</div>
        </div>
    @else
        <div class="card">
            <form method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Which pet is this story about? *</label>
                    <select name="application_id" class="form-input" required>
                        <option value="">Select an adoption…</option>
                        @foreach($completedApplications as $app)
                            <option value="{{ $app->id }}" {{ old('application_id') == $app->id ? 'selected' : '' }}>
                                {{ $app->pet->name }} ({{ ucfirst($app->pet->species) }}) — adopted {{ $app->updated_at->format('M Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('application_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Your story *</label>
                    <textarea name="content" class="form-input" rows="6" required
                        placeholder="How has your life changed? What's your pet's favourite thing? Any advice for future adopters?"
                        style="min-height:140px;">{{ old('content') }}</textarea>
                    <div style="font-size:11px;color:var(--ink-3);margin-top:4px;">Minimum 30 characters.</div>
                    @error('content') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Photo (optional)</label>
                    <input type="file" name="photo" class="form-input" accept="image/jpg,image/jpeg,image/png,image/webp">
                    <div style="font-size:11px;color:var(--ink-3);margin-top:4px;">JPG, PNG or WebP · max 3 MB</div>
                    @error('photo') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div style="background:var(--surface-2);border-radius:8px;padding:12px 14px;font-size:12px;color:var(--ink-3);margin-bottom:20px;">
                    ℹ️ Your story will be reviewed by our team before it appears on the public wall. This usually takes less than 24 hours.
                </div>

                <button type="submit" class="btn btn-primary">Submit story</button>
            </form>
        </div>
    @endif
</div>
@endsection
