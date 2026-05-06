@extends('layouts.app')
@section('title', 'Volunteer Management')

@section('content')
    <div class="page">
        <div class="page-header">
            <div class="page-title">Volunteer management</div>
            <div class="page-sub">Approve or revoke volunteer access to the system.</div>
        </div>

        {{-- Pending approval --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="flex items-center justify-between" style="margin-bottom:16px;">
                <div style="font-size:14px;font-weight:500;">
                    Pending approval
                    @if($pending->count() > 0)
                        <span
                            style="background:#FAEEDA;color:#854F0B;font-size:11px;padding:2px 8px;border-radius:20px;margin-left:6px;">
                            {{ $pending->count() }} waiting
                        </span>
                    @endif
                </div>
            </div>

            @if($pending->isEmpty())
                <div class="text-muted">No volunteers pending approval.</div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $volunteer)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div
                                            style="width:32px;height:32px;border-radius:50%;background:#FAEEDA;color:#854F0B;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;">
                                            {{ strtoupper(substr($volunteer->name, 0, 1)) }}
                                        </div>
                                        <div style="font-weight:500;">{{ $volunteer->name }}</div>
                                    </div>
                                </td>
                                <td style="color:#888;">{{ $volunteer->email }}</td>
                                <td style="color:#888;font-size:12px;">{{ $volunteer->created_at->diffForHumans() }}</td>
                                <td>
                                    <form method="POST" action="{{ route('volunteers.approve', $volunteer) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-primary btn-sm">Approve</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Approved volunteers --}}
        <div class="card">
            <div style="font-size:14px;font-weight:500;margin-bottom:16px;">
                Approved volunteers
                <span
                    style="background:#E1F5EE;color:#0F6E56;font-size:11px;padding:2px 8px;border-radius:20px;margin-left:6px;">
                    {{ $approved->count() }} active
                </span>
            </div>

            @if($approved->isEmpty())
                <div style="text-align:center;padding:32px 0;">
                    <div style="font-size:28px;margin-bottom:10px;">🤝</div>
                    <div style="font-size:14px;font-weight:500;color:#1a1a18;margin-bottom:4px;">No approved volunteers yet
                    </div>
                    <div style="font-size:13px;color:#888;">Approve pending volunteers from the section above.</div>
                </div>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approved as $volunteer)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div
                                            style="width:32px;height:32px;border-radius:50%;background:#E1F5EE;color:#0F6E56;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;">
                                            {{ strtoupper(substr($volunteer->name, 0, 1)) }}
                                        </div>
                                        <div style="font-weight:500;">{{ $volunteer->name }}</div>
                                    </div>
                                </td>
                                <td style="color:#888;">{{ $volunteer->email }}</td>
                                <td style="color:#888;font-size:12px;">{{ $volunteer->created_at->format('M j, Y') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('volunteers.revoke', $volunteer) }}"
                                        onsubmit="return confirm('Revoke {{ $volunteer->name }}\'s volunteer access?')">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-danger btn-sm">Revoke</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection