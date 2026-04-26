@extends('layouts.app')
@section('title', 'Applications')

@section('content')
<div class="page">
    <div class="page-header">
        <div class="page-title">{{ auth()->user()->isAdmin() ? 'All applications' : 'My applications' }}</div>
        <div class="page-sub">{{ auth()->user()->isAdmin() ? 'Review and advance each application through the workflow.'
            : 'Track the status of your adoption applications.' }}</div>
    </div>

    {{-- Workflow steps reference (admin only) --}}
    @if(auth()->user()->isAdmin())
    <div class="card" style="margin-bottom:20px;">
        <div style="font-size:12px;color:#888;margin-bottom:12px;font-weight:500;">Adoption workflow stages</div>
        <div class="steps">
            @foreach(['Submitted','Under review','Meet & greet','Home check','Approved','Completed'] as $i => $label)
            <div class="step done">
                <div class="step-circle">{{ $i+1 }}</div>
                <div class="step-label">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="card">
        @if($applications->isEmpty())
        <div style="text-align:center;padding:40px;">
            <div style="font-size:32px;margin-bottom:12px;">📋</div>
            <div class="text-muted">No applications yet.</div>
            @if(!auth()->user()->isAdmin())
            <a href="{{ route('pets.index') }}" class="btn btn-primary" style="margin-top:16px;">Browse pets</a>
            @endif
        </div>
        @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        @if(auth()->user()->isAdmin()) <th>Applicant</th> @endif
                        <th>Pet</th>
                        <th>Submitted</th>
                        <th>Stage</th>
                        <th>Status</th>
                        @if(auth()->user()->isAdmin()) <th>Action</th> @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        @if(auth()->user()->isAdmin())
                        <td>
                            <div style="font-weight:500;">{{ $app->user->name }}</div>
                            <div style="font-size:11px;color:#888;">{{ $app->user->email }}</div>
                        </td>
                        @endif
                        <td>
                            <a href="{{ route('pets.show', $app->pet) }}" style="font-weight:500;color:var(--green);">{{
                                $app->pet->name }}</a>
                            <div style="font-size:11px;color:#888;">{{ ucfirst($app->pet->species) }}</div>
                        </td>
                        <td style="color:#888;font-size:12px;">{{ $app->submitted_at->format('M j, Y') }}</td>
                        <td style="font-size:13px;">{{ ucfirst(str_replace('_',' ',$app->status)) }}</td>
                        <td><span class="badge badge-{{ str_replace(['_',' '],'-',$app->status) }}">{{
                                ucfirst(str_replace('_',' ',$app->status)) }}</span></td>

                        @if(auth()->user()->isAdmin())
                        <td>
                            @php
                            $next = [
                            'pending' => 'under_review',
                            'under_review' => 'meet_greet',
                            'meet_greet' => 'home_check',
                            'home_check' => 'approved',
                            'approved' => 'completed',
                            ][$app->status] ?? null;
                            @endphp
                            @if($next)
                            <form method="POST" action="{{ route('applications.updateStatus', $app) }}"
                                style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $next }}">
                                <button class="btn btn-primary btn-sm">→ {{ ucfirst(str_replace('_',' ',$next))
                                    }}</button>
                            </form>
                            @endif
                            @if(!in_array($app->status, ['rejected','completed']))
                            <form method="POST" action="{{ route('applications.updateStatus', $app) }}"
                                style="display:inline;margin-left:4px;"
                                onsubmit="return confirm('Reject this application?')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </form>
                            @endif
                            @if($app->status === 'meet_greet' && !$app->meetGreet)
                            <a href="{{ route('meet-greets.index') }}" class="btn btn-sm"
                                style="margin-left:4px;">Schedule</a>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $applications->links() }}</div>
        @endif
    </div>
</div>
@endsection