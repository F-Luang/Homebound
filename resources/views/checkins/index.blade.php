@extends('layouts.app')
@section('title', 'Post-Adoption Check-ins')

@section('content')
<div class="page">
    <div class="page-header">
        <div class="page-title">Post-adoption check-ins</div>
        <div class="page-sub">Follow up with adopters at 1 week, 1 month, and 6 months after adoption.</div>
    </div>

    @if($checkins->isEmpty())
        <div class="card" style="text-align:center;padding:64px 24px;">
            <div style="font-size:48px;margin-bottom:16px;">✅</div>
            <div style="font-family:'Lora',serif;font-size:20px;font-weight:600;margin-bottom:8px;">No check-ins yet</div>
            <div class="text-muted">Check-ins are scheduled automatically when an adoption is completed.</div>
        </div>
    @else
        <div class="card" style="padding:0;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Adopter</th>
                            <th>Pet</th>
                            <th>Check-in</th>
                            <th>Due date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($checkins as $checkin)
                            @php $overdue = $checkin->isOverdue(); @endphp
                            <tr>
                                <td>
                                    <div style="font-weight:500;">{{ $checkin->application->user->name ?? '—' }}</div>
                                    <div style="font-size:11px;color:var(--ink-3);">{{ $checkin->application->user->email ?? '' }}</div>
                                </td>
                                <td>
                                    <div style="font-weight:500;">{{ $checkin->application->pet->name ?? 'Deleted pet' }}</div>
                                    <div style="font-size:11px;color:var(--ink-3);">{{ ucfirst($checkin->application->pet->species ?? '') }}</div>
                                </td>
                                <td style="font-size:13px;">{{ $checkin->label }}</td>
                                <td style="font-size:12px;color:{{ $overdue ? 'var(--coral)' : 'var(--ink-3)' }};">
                                    {{ $checkin->due_at->format('M j, Y') }}
                                    @if($overdue) <span style="font-size:10px;font-weight:600;">OVERDUE</span> @endif
                                </td>
                                <td>
                                    @if($checkin->status === 'completed')
                                        <span class="badge badge-completed">Completed</span>
                                    @elseif($checkin->status === 'missed')
                                        <span class="badge badge-rejected">Missed</span>
                                    @else
                                        <span class="badge badge-{{ $overdue ? 'rejected' : 'pending' }}">
                                            {{ $overdue ? 'Overdue' : 'Pending' }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($checkin->status === 'pending')
                                        <form method="POST" action="{{ route('checkins.complete', $checkin) }}"
                                              style="display:flex;gap:6px;align-items:center;">
                                            @csrf @method('PATCH')
                                            <input class="form-input" style="font-size:12px;padding:4px 8px;flex:1;min-width:150px;"
                                                   name="notes" placeholder="Optional notes…">
                                            <button class="btn btn-primary btn-sm">Mark done</button>
                                        </form>
                                    @elseif($checkin->status === 'completed')
                                        <div style="font-size:12px;color:var(--ink-3);">
                                            Done by {{ $checkin->completedBy->name ?? '—' }}
                                            · {{ $checkin->completed_at?->format('M j') }}
                                            @if($checkin->notes)
                                                <div style="margin-top:3px;font-style:italic;">"{{ Str::limit($checkin->notes, 60) }}"</div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:16px;">{{ $checkins->links() }}</div>
        </div>
    @endif
</div>
@endsection
