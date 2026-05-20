@extends('layouts.app')
@section('title', 'Surrender Requests')

@section('content')
<div class="page">
    <div class="page-header">
        <div class="page-title">Surrender requests</div>
        <div class="page-sub">Pets whose owners need to rehome them. Contact each submitter within 48 hours.</div>
    </div>

    @if($surrenders->isEmpty())
        <div class="card" style="text-align:center;padding:64px 24px;">
            <div style="font-size:48px;margin-bottom:16px;">📭</div>
            <div style="font-family:'Lora',serif;font-size:20px;font-weight:600;">No surrender requests</div>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:14px;">
            @foreach($surrenders as $s)
                <div class="card">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                                <div style="font-family:'Lora',serif;font-weight:600;font-size:16px;">{{ $s->pet_name }}</div>
                                <span class="badge badge-{{ $s->urgency === 'high' ? 'rejected' : ($s->urgency === 'medium' ? 'pending' : 'available') }}">
                                    {{ ucfirst($s->urgency) }} urgency
                                </span>
                                <span class="badge badge-{{ $s->status === 'accepted' ? 'completed' : ($s->status === 'declined' ? 'rejected' : ($s->status === 'contacted' ? 'approved' : 'pending')) }}">
                                    {{ ucfirst($s->status) }}
                                </span>
                            </div>
                            <div style="font-size:12px;color:var(--ink-3);margin-bottom:8px;">
                                {{ ucfirst($s->species) }}{{ $s->breed ? ' · ' . $s->breed : '' }}{{ $s->age_years !== null ? ' · ~' . $s->age_years . 'y' : '' }}
                                · Submitted {{ $s->created_at->format('M j, Y') }}
                            </div>
                            <div style="font-size:13px;color:var(--ink-2);margin-bottom:8px;">
                                <strong>Reason:</strong> {{ Str::limit($s->reason, 150) }}
                            </div>
                            <div style="font-size:12px;color:var(--ink-3);">
                                <strong>Contact:</strong> {{ $s->submitter_name }} — {{ $s->submitter_email }}
                                {{ $s->submitter_phone ? '· ' . $s->submitter_phone : '' }}
                            </div>
                            @if($s->admin_notes)
                                <div style="margin-top:8px;font-size:12px;color:var(--ink-3);font-style:italic;">
                                    Note: {{ $s->admin_notes }}
                                </div>
                            @endif
                        </div>

                        {{-- Status update form --}}
                        <form method="POST" action="{{ route('surrenders.updateStatus', $s) }}"
                              style="display:flex;flex-direction:column;gap:6px;min-width:200px;">
                            @csrf @method('PATCH')
                            <select name="status" class="form-input" style="font-size:12px;padding:5px 8px;">
                                @foreach(['pending','contacted','accepted','declined'] as $st)
                                    <option value="{{ $st }}" {{ $s->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="admin_notes" class="form-input" style="font-size:12px;padding:5px 8px;"
                                   placeholder="Internal note…" value="{{ old('admin_notes', $s->admin_notes) }}">
                            <button class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="margin-top:20px;">{{ $surrenders->links() }}</div>
    @endif
</div>
@endsection
