@extends('layouts.app')
@section('title', 'Applications')

@section('content')
    <div class="page">
        <div class="page-header">
            <div class="page-title">{{ auth()->user()->isAdmin() ? 'All applications' : 'My applications' }}</div>
            <div class="page-sub">{{ auth()->user()->isAdmin()
        ? 'Review and advance each application through the workflow.'
        : 'Track the status of your adoption applications.' }}</div>
        </div>

        @if($applications->isEmpty())
            {{-- ===== EMPTY STATE ===== --}}
            <div class="card" style="text-align:center;padding:64px 24px;">
                <div
                    style="width:72px;height:72px;border-radius:50%;background:var(--green-light);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:32px;">
                    📋
                </div>
                <div style="font-family:'Lora',serif;font-size:20px;font-weight:600;margin-bottom:8px;color:#1a1a18;">
                    No applications yet
                </div>
                <div class="text-muted" style="margin-bottom:24px;max-width:340px;margin-left:auto;margin-right:auto;">
                    {{ auth()->user()->isAdmin()
                ? 'Applications from adopters will appear here once they start applying.'
                : 'You haven\'t applied for any pets yet. Browse available pets and start your adoption journey.' }}
                </div>
                @if(!auth()->user()->isAdmin())
                    <a href="{{ route('pets.index') }}" class="btn btn-primary">Browse pets</a>
                @endif
            </div>

        @else

            {{-- ===== ADOPTER VIEW ===== --}}
            @if(!auth()->user()->isAdmin())
                @php
                    $stages = [
                        'pending' => ['label' => 'Submitted', 'step' => 1],
                        'under_review' => ['label' => 'Under review', 'step' => 2],
                        'meet_greet' => ['label' => 'Meet & greet', 'step' => 3],
                        'home_check' => ['label' => 'Home check', 'step' => 4],
                        'approved' => ['label' => 'Approved', 'step' => 5],
                        'completed' => ['label' => 'Completed', 'step' => 5],
                        'rejected' => ['label' => 'Rejected', 'step' => 0],
                        'cancelled' => ['label' => 'Cancelled', 'step' => 0],
                    ];
                @endphp

                <div style="display:flex;flex-direction:column;gap:16px;">
                    @foreach($applications as $app)
                        @php
                            $current = $stages[$app->status] ?? ['step' => 1, 'label' => ucfirst($app->status)];
                            $isRejected = $app->status === 'rejected';
                            $species = $app->pet->species ?? 'other';
                            $bgColors = ['dog' => '#E1F5EE', 'cat' => '#FBEAF0', 'rabbit' => '#E6F1FB', 'bird' => '#EAF3DE', 'other' => '#F5F4F0'];
                            $emojis = ['dog' => '🐕', 'cat' => '🐈', 'rabbit' => '🐇', 'bird' => '🐦', 'other' => '🐾'];
                        @endphp
                        <div class="card">
                            {{-- Pet info row --}}
                            <div class="flex items-center justify-between" style="margin-bottom:20px;">
                                <div class="flex items-center gap-12">
                                    <div
                                        style="width:44px;height:44px;border-radius:10px;background:{{ $bgColors[$species] ?? '#F5F4F0' }};display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">
                                        {{ $emojis[$species] ?? '🐾' }}
                                    </div>
                                    <div>
                                        <div style="font-family:'Lora',serif;font-weight:600;font-size:16px;">
                                            @if($app->pet)
                                                <a href="{{ route('pets.show', $app->pet) }}"
                                                    style="color:var(--green);">{{ $app->pet->name }}</a>
                                            @else
                                                <span style="color:#888;">Deleted pet</span>
                                            @endif
                                        </div>
                                        <div style="font-size:12px;color:#888;">Applied {{ $app->submitted_at->format('M j, Y') }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-8">
                                    <span class="badge badge-{{ str_replace(['_', ' '], '-', $app->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                    @if($app->status === 'pending')
                                        <form method="POST" action="{{ route('applications.cancel', $app) }}"
                                            data-confirm="Are you sure you want to cancel this application?">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Step tracker --}}
                            @if(!$isRejected && $app->status !== 'cancelled')
                                <div class="steps" style="margin-bottom:0;">
                                    @foreach(['Submitted', 'Under review', 'Meet & greet', 'Home check', 'Approved'] as $i => $label)
                                        @php $stepNum = $i + 1; @endphp
                                        <div
                                            class="step {{ $current['step'] > $stepNum ? 'done' : ($current['step'] === $stepNum ? 'current' : '') }}">
                                            <div class="step-circle">
                                                @if($current['step'] > $stepNum) ✓ @else {{ $stepNum }} @endif
                                            </div>
                                            <div class="step-label">{{ $label }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($app->status === 'cancelled')
                                <div
                                    style="padding:12px 16px;background:#F5F4F0;border-radius:8px;font-size:13px;color:#888;text-align:center;">
                                    You cancelled this application. Feel free to apply again for any available pet.
                                </div>
                            @else
                                <div
                                    style="padding:12px 16px;background:var(--coral-light);border-radius:8px;font-size:13px;color:var(--coral);text-align:center;">
                                    This application was not approved. Feel free to browse other pets.
                                </div>
                            @endif

                            {{-- View detail button --}}
                            <div style="margin-top:14px;padding-top:14px;border-top:0.5px solid rgba(0,0,0,0.06);">
                                <a href="{{ route('applications.show', $app) }}" class="btn btn-sm" style="font-size:12px;">
                                    View full details →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top:20px;">{{ $applications->links() }}</div>

            @else
                {{-- ===== ADMIN VIEW ===== --}}

                {{-- Search & filter bar --}}
                <form method="GET" action="{{ route('applications.index') }}" class="card"
                    style="margin-bottom:16px;padding:14px 20px;">
                    <div class="flex gap-12 items-center" style="flex-wrap:wrap;">
                        <input class="form-input" style="flex:1;min-width:200px;" name="search"
                            placeholder="Search by applicant name, email or pet…" value="{{ request('search') }}">
                        <select class="form-input" style="width:160px;" name="status">
                            <option value="">All statuses</option>
                            @foreach(['pending', 'under_review', 'meet_greet', 'home_check', 'approved', 'rejected', 'completed', 'cancelled'] as $s)
                                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                                </option>
                            @endforeach
                        </select>
                        <select class="form-input" style="width:140px;" name="species">
                            <option value="">All species</option>
                            @foreach(['dog', 'cat', 'rabbit', 'bird', 'other'] as $s)
                                <option value="{{ $s }}" {{ request('species') === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Search</button>
                        @if(request()->hasAny(['search', 'status', 'species']))
                            <a href="{{ route('applications.index') }}" class="btn">Clear</a>
                        @endif
                    </div>
                    @if(request()->hasAny(['search', 'status', 'species']))
                        <div class="flex gap-8" style="margin-top:10px;flex-wrap:wrap;">
                            @if(request('search'))
                                <span style="background:#E1F5EE;color:#0F6E56;font-size:11px;padding:3px 10px;border-radius:20px;">
                                    🔍 "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('status'))
                                <span style="background:#E6F1FB;color:#185FA5;font-size:11px;padding:3px 10px;border-radius:20px;">
                                    Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                                </span>
                            @endif
                            @if(request('species'))
                                <span style="background:#FAEEDA;color:#854F0B;font-size:11px;padding:3px 10px;border-radius:20px;">
                                    Species: {{ ucfirst(request('species')) }}
                                </span>
                            @endif
                        </div>
                    @endif
                </form>

                @if(request()->hasAny(['search', 'status', 'species']))
                    <div style="font-size:13px;color:#888;margin-bottom:12px;">
                        {{ $applications->total() }} result{{ $applications->total() !== 1 ? 's' : '' }} found
                        @if(request('search')) for "<strong>{{ request('search') }}</strong>" @endif
                    </div>
                @endif

                <div class="card" style="margin-bottom:20px;">
                    <div style="font-size:12px;color:#888;margin-bottom:12px;font-weight:500;">Adoption workflow stages</div>
                    <div class="steps">
                        @foreach(['Submitted', 'Under review', 'Meet & greet', 'Home check', 'Approved', 'Completed'] as $i => $label)
                            <div class="step done">
                                <div class="step-circle">{{ $i + 1 }}</div>
                                <div class="step-label">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Pet</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $app)
                                    @php
                                        $next = [
                                            'pending' => 'under_review',
                                            'under_review' => 'meet_greet',
                                            'meet_greet' => 'home_check',
                                            'home_check' => 'approved',
                                            'approved' => 'completed',
                                        ][$app->status] ?? null;
                                    @endphp
                                    <tr>
                                        {{-- Applicant --}}
                                        <td>
                                            <a href="{{ route('applications.show', $app) }}" style="display:block;">
                                                <div style="font-weight:500;color:var(--green);">{{ $app->user->name ?? 'Unknown' }}
                                                </div>
                                                <div style="font-size:11px;color:#888;">{{ $app->user->email ?? '' }}</div>
                                            </a>
                                        </td>

                                        {{-- Pet --}}
                                        <td>
                                            @if($app->pet)
                                                <a href="{{ route('pets.show', $app->pet) }}"
                                                    style="color:var(--green);">{{ $app->pet->name }}</a>
                                                <div style="font-size:11px;color:#888;">{{ ucfirst($app->pet->species) }}</div>
                                            @else
                                                <div style="font-weight:500;color:#888;">Deleted pet</div>
                                            @endif
                                        </td>

                                        {{-- Submitted --}}
                                        <td style="color:#888;font-size:12px;">{{ $app->submitted_at->format('M j, Y') }}</td>

                                        {{-- Status --}}
                                        <td>
                                            <span class="badge badge-{{ str_replace(['_', ' '], '-', $app->status) }}">
                                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td>
                                            <div class="flex gap-8">
                                                @if($next)
                                                    <form method="POST" action="{{ route('applications.updateStatus', $app) }}">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $next }}">
                                                        <button class="btn btn-primary btn-sm">
                                                            → {{ ucfirst(str_replace('_', ' ', $next)) }}
                                                        </button>
                                                    </form>
                                                @endif
                                                @if(!in_array($app->status, ['rejected', 'completed']))
                                                    <form method="POST" action="{{ route('applications.updateStatus', $app) }}"
                                                        data-confirm="Reject this application from {{ $app->user->name }}? This cannot be undone.">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                    </form>
                                                @endif
                                                @if($app->status === 'completed')
                                                    <a href="{{ route('applications.contract', $app) }}" class="btn btn-sm"
                                                        style="font-size:11px;">
                                                        ↓ Contract
                                                    </a>
                                                @endif
                                                @if($app->status === 'meet_greet' && !$app->meetGreet)
                                                    <a href="{{ route('home-visits.index') }}" class="btn btn-sm">Schedule visit</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:16px;">{{ $applications->links() }}</div>
                </div>
            @endif
        @endif
    </div>
@endsection