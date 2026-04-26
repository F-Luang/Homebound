@extends('layouts.app')
@section('title', 'Scheduling')

@section('content')
<div class="page">
    <div class="page-header">
        <div class="page-title">Meet &amp; greet scheduling</div>
        <div class="page-sub">
            @if(auth()->user()->isAdmin()) All scheduled appointments. @else Your assigned meet & greet sessions. @endif
        </div>
    </div>

    <div class="two-col" style="align-items:start;">

        {{-- Schedule list --}}
        <div class="card" style="grid-column:1/-1;">
            @if($meetGreets->isEmpty())
            <div style="text-align:center;padding:40px;">
                <div style="font-size:32px;margin-bottom:12px;">📅</div>
                <div class="text-muted">No meet & greets scheduled yet.</div>
            </div>
            @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Date & time</th>
                            <th>Pet</th>
                            <th>Applicant</th>
                            <th>Volunteer</th>
                            <th>Status</th>
                            @if(auth()->user()->isAdmin() || auth()->user()->isVolunteer()) <th>Action</th> @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($meetGreets as $mg)
                        <tr>
                            <td>
                                <div style="font-weight:500;">{{ $mg->scheduled_at->format('M j, Y') }}</div>
                                <div style="font-size:11px;color:#888;">{{ $mg->scheduled_at->format('g:i A') }}</div>
                            </td>
                            <td>
                                <a href="{{ route('pets.show', $mg->application->pet) }}"
                                    style="font-weight:500;color:var(--green);">
                                    {{ $mg->application->pet->name }}
                                </a>
                            </td>
                            <td>{{ $mg->application->user->name }}</td>
                            <td>{{ $mg->volunteer?->name ?? '—' }}</td>
                            <td>
                                <span
                                    class="badge {{ $mg->status === 'scheduled' ? 'badge-review' : ($mg->status === 'completed' ? 'badge-approved' : 'badge-rejected') }}">
                                    {{ ucfirst($mg->status) }}
                                </span>
                            </td>
                            @if(auth()->user()->isAdmin() || auth()->user()->isVolunteer())
                            <td>
                                @if($mg->status === 'scheduled')
                                <form method="POST" action="{{ route('meet-greets.updateStatus', $mg) }}"
                                    style="display:inline;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button class="btn btn-primary btn-sm">Mark complete</button>
                                </form>
                                <form method="POST" action="{{ route('meet-greets.updateStatus', $mg) }}"
                                    style="display:inline;margin-left:4px;"
                                    onsubmit="return confirm('Cancel this session?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="btn btn-danger btn-sm">Cancel</button>
                                </form>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Schedule new (admin/volunteer) --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isVolunteer())
        <div class="card" style="grid-column:1/-1;">
            <div style="font-size:14px;font-weight:500;margin-bottom:16px;">Schedule a new meet &amp; greet</div>
            <form method="POST" action="{{ route('meet-greets.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Application *</label>
                        <select class="form-input" name="application_id" required>
                            <option value="">Select an application…</option>
                            @foreach($openApplications as $app)
                            <option value="{{ $app->id }}" {{ old('application_id')==$app->id ? 'selected' : '' }}>
                                {{ $app->user->name }} → {{ $app->pet->name }} ({{ ucfirst(str_replace('_','
                                ',$app->status)) }})
                            </option>
                            @endforeach
                        </select>
                        @error('application_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date & time *</label>
                        <input class="form-input" type="datetime-local" name="scheduled_at"
                            value="{{ old('scheduled_at') }}" required>
                        @error('scheduled_at') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <input class="form-input" name="notes" placeholder="Any special instructions or location details…"
                        value="{{ old('notes') }}">
                </div>
                <button type="submit" class="btn btn-primary">Schedule session</button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection