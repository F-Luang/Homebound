@extends('layouts.app')
@section('title', 'Home Visits')

@section('content')
    <div class="page">
        <div class="page-header">
            <div class="page-title">Home Visits</div>
            <div class="page-sub">
                {{ auth()->user()->isAdmin() ? 'All scheduled home visits and meet & greets.' : 'Your assigned visit sessions.' }}
            </div>
        </div>

        {{-- ===== SCHEDULE NEW (admin/volunteer) ===== --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isVolunteer())
            <div class="card" style="margin-bottom:20px;">
                <div style="font-size:15px;font-weight:600;font-family:'Lora',serif;margin-bottom:16px;">Schedule a new visit
                </div>
                <form method="POST" action="{{ route('home-visits.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Application *</label>
                            <select class="form-input" name="application_id" required>
                                <option value="">Select an application…</option>
                                @foreach($openApplications as $app)
                                    <option value="{{ $app->id }}" {{ old('application_id') == $app->id ? 'selected' : '' }}>
                                        {{ $app->user->name }} → {{ $app->pet->name }}
                                        ({{ ucfirst(str_replace('_', ' ', $app->status)) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('application_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date & time *</label>
                            <input class="form-input" type="datetime-local" name="scheduled_at"
                                value="{{ old('scheduled_at', now()->addDay()->format('Y-m-d\TH:i')) }}"
                                min="{{ now()->format('Y-m-d\TH:i') }}" required>
                            @error('scheduled_at') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Location / address</label>
                            <input class="form-input" name="location" placeholder="e.g. 123 Rizal St, Davao City"
                                value="{{ old('location') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Notes</label>
                            <input class="form-input" name="notes" placeholder="Any special instructions or reminders…"
                                value="{{ old('notes') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Schedule visit</button>
                </form>
            </div>
        @endif

        {{-- ===== UPCOMING VISITS ===== --}}
        @php
            $upcoming = $meetGreets->where('status', 'scheduled')->sortBy('scheduled_at');
            $past = $meetGreets->whereIn('status', ['completed', 'cancelled'])->sortByDesc('scheduled_at');
        @endphp

        @if($meetGreets->isEmpty())
            <div class="card" style="text-align:center;padding:64px 24px;">
                <div
                    style="width:72px;height:72px;border-radius:50%;background:#E6F1FB;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:32px;">
                    📅
                </div>
                <div style="font-family:'Lora',serif;font-size:20px;font-weight:600;margin-bottom:8px;">
                    No visits scheduled yet
                </div>
                <div class="text-muted" style="font-size:13px;max-width:320px;margin:0 auto 20px;">
                    {{ auth()->user()->isAdmin() ? 'Use the form above to schedule a home visit or meet & greet for an application.' : 'No sessions have been assigned to you yet. Check back later.' }}
                </div>
                @if(auth()->user()->isAdmin())
                    <a href="#" onclick="document.querySelector('.form-input').focus();return false;" class="btn btn-primary">
                        Schedule first visit
                    </a>
                @endif
            </div>
        @else

            {{-- Upcoming --}}
            @if($upcoming->isNotEmpty())
                <div class="card" style="margin-bottom:16px;">
                    <div
                        style="font-size:13px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:14px;">
                        Upcoming ({{ $upcoming->count() }})
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date & time</th>
                                    <th>Pet</th>
                                    <th>Applicant</th>
                                    @if(auth()->user()->isAdmin())
                                    <th>Volunteer</th> @endif
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcoming as $mg)
                                    <tr>
                                        <td>
                                            <div style="font-weight:500;color:var(--green);">
                                                {{ $mg->scheduled_at->format('M j, Y') }}
                                            </div>
                                            <div style="font-size:11px;color:#888;">
                                                {{ $mg->scheduled_at->format('g:i A') }}
                                                · {{ $mg->scheduled_at->diffForHumans() }}
                                            </div>
                                            @if(!empty($mg->location))
                                                <div style="font-size:11px;color:#888;margin-top:2px;">📍 {{ $mg->location }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pets.show', $mg->application->pet) }}"
                                                style="font-weight:500;color:var(--green);">
                                                {{ $mg->application->pet->name }}
                                            </a>
                                        </td>
                                        <td>{{ $mg->application->user->name }}</td>
                                        @if(auth()->user()->isAdmin())
                                            <td>{{ $mg->volunteer?->name ?? '—' }}</td>
                                        @endif
                                        <td style="font-size:12px;color:#666;">
                                            {{ $mg->notes ?? '—' }}
                                        </td>
                                        <td>
                                            <div class="flex gap-8">
                                                <form method="POST" action="{{ route('home-visits.updateStatus', $mg) }}">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button class="btn btn-primary btn-sm">Mark complete</button>
                                                </form>
                                                <form method="POST" action="{{ route('home-visits.updateStatus', $mg) }}"
                                                    data-confirm="Cancel this visit for {{ $mg->application->pet->name }}?"
                                                    data-title="Cancel visit" data-confirm-text="Yes, cancel">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Past visits --}}
            @if($past->isNotEmpty())
                <div class="card">
                    <div
                        style="font-size:13px;font-weight:500;color:#888;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:14px;">
                        Past visits ({{ $past->count() }})
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Date & time</th>
                                    <th>Pet</th>
                                    <th>Applicant</th>
                                    @if(auth()->user()->isAdmin())
                                    <th>Volunteer</th> @endif
                                    <th>Notes</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($past as $mg)
                                    <tr>
                                        <td>
                                            <div style="font-weight:500;">{{ $mg->scheduled_at->format('M j, Y') }}</div>
                                            <div style="font-size:11px;color:#888;">{{ $mg->scheduled_at->format('g:i A') }}</div>
                                            @if(!empty($mg->location))
                                                <div style="font-size:11px;color:#888;margin-top:2px;">📍 {{ $mg->location }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pets.show', $mg->application->pet) }}"
                                                style="font-weight:500;color:var(--green);">
                                                {{ $mg->application->pet->name }}
                                            </a>
                                        </td>
                                        <td>{{ $mg->application->user->name }}</td>
                                        @if(auth()->user()->isAdmin())
                                            <td>{{ $mg->volunteer?->name ?? '—' }}</td>
                                        @endif
                                        <td style="font-size:12px;color:#666;">{{ $mg->notes ?? '—' }}</td>
                                        <td>
                                            <span class="badge {{ $mg->status === 'completed' ? 'badge-approved' : 'badge-rejected' }}">
                                                {{ ucfirst($mg->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        @endif
    </div>
@endsection