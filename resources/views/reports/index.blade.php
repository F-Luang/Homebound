@extends('layouts.app')
@section('title', 'Reports')

@section('content')
<div class="page">
    <div class="page-header flex items-center justify-between">
        <div>
            <div class="page-title">Reports</div>
            <div class="page-sub">Shelter analytics and adoption statistics.</div>
        </div>
        <div style="font-size:12px;color:#888;">
            Last updated: {{ now()->format('M j, Y · g:i A') }}
        </div>
    </div>

    {{-- ===== OVERVIEW STATS ===== --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:24px;">
        <div class="card" style="text-align:center;padding:20px 16px;">
            <div style="font-size:32px;font-weight:600;color:var(--green);font-family:'Lora',serif;">{{ $totalPets }}</div>
            <div style="font-size:12px;color:#888;margin-top:4px;">Total pets</div>
        </div>
        <div class="card" style="text-align:center;padding:20px 16px;">
            <div style="font-size:32px;font-weight:600;color:#3C3489;font-family:'Lora',serif;">{{ $totalAdopted }}</div>
            <div style="font-size:12px;color:#888;margin-top:4px;">Adopted</div>
        </div>
        <div class="card" style="text-align:center;padding:20px 16px;">
            <div style="font-size:32px;font-weight:600;color:var(--green);font-family:'Lora',serif;">{{ $totalAvailable }}</div>
            <div style="font-size:12px;color:#888;margin-top:4px;">Available</div>
        </div>
        <div class="card" style="text-align:center;padding:20px 16px;">
            <div style="font-size:32px;font-weight:600;color:var(--amber);font-family:'Lora',serif;">{{ $totalApplications }}</div>
            <div style="font-size:12px;color:#888;margin-top:4px;">Applications</div>
        </div>
        <div class="card" style="text-align:center;padding:20px 16px;">
            <div style="font-size:32px;font-weight:600;color:var(--green);font-family:'Lora',serif;">{{ $conversionRate }}%</div>
            <div style="font-size:12px;color:#888;margin-top:4px;">Adoption rate</div>
        </div>
        <div class="card" style="text-align:center;padding:20px 16px;">
            <div style="font-size:32px;font-weight:600;font-family:'Lora',serif;">{{ $totalAdopters }}</div>
            <div style="font-size:12px;color:#888;margin-top:4px;">Registered adopters</div>
        </div>
    </div>

    <div class="two-col" style="margin-bottom:20px;">

        {{-- ===== APPLICATIONS CHART ===== --}}
        <div class="card">
            <div style="font-size:14px;font-weight:500;margin-bottom:4px;">Applications over time</div>
            <div style="font-size:12px;color:#888;margin-bottom:16px;">Last 6 months</div>
            @if($applicationsPerMonth->isEmpty())
                <div class="text-muted">No data yet.</div>
            @else
                @php $maxApp = $applicationsPerMonth->max('total') ?: 1; @endphp
                <div style="display:flex;align-items:flex-end;gap:8px;height:120px;">
                    @foreach($applicationsPerMonth as $month)
                        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;">
                            <div style="flex:1;display:flex;align-items:flex-end;width:100%;">
                                <div style="width:100%;height:{{ round(($month['total']/$maxApp)*100) }}%;background:var(--green-light);border-radius:4px 4px 0 0;border-bottom:2px solid var(--green);transition:height 0.4s;min-height:4px;" title="{{ $month['total'] }} applications"></div>
                            </div>
                            <div style="font-size:10px;color:#888;text-align:center;white-space:nowrap;">{{ $month['label'] }}</div>
                            <div style="font-size:11px;font-weight:500;">{{ $month['total'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ===== ADOPTIONS CHART ===== --}}
        <div class="card">
            <div style="font-size:14px;font-weight:500;margin-bottom:4px;">Completed adoptions</div>
            <div style="font-size:12px;color:#888;margin-bottom:16px;">Last 6 months</div>
            @if($adoptionsPerMonth->isEmpty())
                <div class="text-muted">No completed adoptions yet.</div>
            @else
                @php $maxAdopt = $adoptionsPerMonth->max('total') ?: 1; @endphp
                <div style="display:flex;align-items:flex-end;gap:8px;height:120px;">
                    @foreach($adoptionsPerMonth as $month)
                        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;height:100%;">
                            <div style="flex:1;display:flex;align-items:flex-end;width:100%;">
                                <div style="width:100%;height:{{ round(($month['total']/$maxAdopt)*100) }}%;background:#EEEDFE;border-radius:4px 4px 0 0;border-bottom:2px solid #3C3489;transition:height 0.4s;min-height:4px;" title="{{ $month['total'] }} adoptions"></div>
                            </div>
                            <div style="font-size:10px;color:#888;text-align:center;white-space:nowrap;">{{ $month['label'] }}</div>
                            <div style="font-size:11px;font-weight:500;">{{ $month['total'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="two-col" style="margin-bottom:20px;">

        {{-- ===== SPECIES BREAKDOWN ===== --}}
        <div class="card">
            <div style="font-size:14px;font-weight:500;margin-bottom:16px;">Pets by species</div>
            @php
                $speciesIcons = [
                    'dog'    => ['bg'=>'#E1F5EE','border'=>'var(--green)','icon'=>'pets.png'],
                    'cat'    => ['bg'=>'#FBEAF0','border'=>'#E8A0B0','icon'=>'cat.png'],
                    'rabbit' => ['bg'=>'#E6F1FB','border'=>'#185FA5','icon'=>'rabbit.png'],
                    'bird'   => ['bg'=>'#EAF3DE','border'=>'#3B6D11','icon'=>'bird.png'],
                    'other'  => ['bg'=>'#F5F4F0','border'=>'#888','icon'=>'paws.png'],
                ];
                $totalSpecies = $speciesBreakdown->sum('total') ?: 1;
            @endphp
            @forelse($speciesBreakdown as $species)
                @php $color = $speciesIcons[$species->species] ?? $speciesIcons['other']; @endphp
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <div style="width:28px;height:28px;border-radius:8px;background:{{ $color['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <img src="{{ asset('assets/icons/' . $color['icon']) }}" alt="{{ $species->species }}" style="width:18px;height:18px;object-fit:contain;">
                    </div>
                    <div style="flex:1;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span style="font-size:13px;font-weight:500;">{{ ucfirst($species->species) }}</span>
                            <span style="font-size:12px;color:#888;">{{ $species->total }}</span>
                        </div>
                        <div style="height:6px;background:#f0f0ec;border-radius:4px;overflow:hidden;">
                            <div style="height:100%;width:{{ round(($species->total/$totalSpecies)*100) }}%;background:{{ $color['border'] }};border-radius:4px;transition:width 0.5s;"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-muted">No pets added yet.</div>
            @endforelse
        </div>

        {{-- ===== APPLICATION STATUS BREAKDOWN ===== --}}
        <div class="card">
            <div style="font-size:14px;font-weight:500;margin-bottom:16px;">Applications by status</div>
            @php
                $statusColors = [
                    'pending'      => ['bg'=>'#FAEEDA','color'=>'#854F0B'],
                    'under_review' => ['bg'=>'#E6F1FB','color'=>'#185FA5'],
                    'meet_greet'   => ['bg'=>'#EEEDFE','color'=>'#3C3489'],
                    'home_check'   => ['bg'=>'#FFF3E0','color'=>'#E65100'],
                    'approved'     => ['bg'=>'#EAF3DE','color'=>'#3B6D11'],
                    'rejected'     => ['bg'=>'#FAECE7','color'=>'#993C1D'],
                    'completed'    => ['bg'=>'#E1F5EE','color'=>'#0F6E56'],
                ];
                $totalStatus = $statusBreakdown->sum('total') ?: 1;
            @endphp
            @forelse($statusBreakdown as $item)
                @php $color = $statusColors[$item->status] ?? ['bg'=>'#f0f0ec','color'=>'#888']; @endphp
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <div style="width:10px;height:10px;border-radius:50%;background:{{ $color['color'] }};flex-shrink:0;"></div>
                    <div style="flex:1;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
                            <span style="font-size:13px;">{{ ucfirst(str_replace('_',' ',$item->status)) }}</span>
                            <span style="font-size:12px;color:#888;">{{ $item->total }} ({{ round(($item->total/$totalStatus)*100) }}%)</span>
                        </div>
                        <div style="height:5px;background:#f0f0ec;border-radius:4px;overflow:hidden;">
                            <div style="height:100%;width:{{ round(($item->total/$totalStatus)*100) }}%;background:{{ $color['color'] }};border-radius:4px;opacity:0.7;"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-muted">No applications yet.</div>
            @endforelse
        </div>
    </div>

    <div class="two-col">

        {{-- ===== MOST APPLIED PETS ===== --}}
        <div class="card">
            <div style="font-size:14px;font-weight:500;margin-bottom:16px;">Most applied for pets</div>
            @forelse($mostAppliedPets as $i => $pet)
                <div style="display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:0.5px solid rgba(0,0,0,0.06);">
                    <div style="width:24px;height:24px;border-radius:50%;background:var(--green-light);color:var(--green-dark);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;flex-shrink:0;">{{ $i+1 }}</div>
                    <div style="flex:1;">
                        <div style="font-size:13px;font-weight:500;">{{ $pet->name }}</div>
                        <div style="font-size:11px;color:#888;">{{ ucfirst($pet->species) }} · {{ $pet->breed ?? '' }}</div>
                    </div>
                    <div style="font-size:13px;font-weight:500;color:var(--green);">{{ $pet->applications_count }} <span style="font-size:11px;color:#888;font-weight:400;">apps</span></div>
                </div>
            @empty
                <div class="text-muted">No applications yet.</div>
            @endforelse
        </div>

        {{-- ===== RECENT ADOPTIONS ===== --}}
        <div class="card">
            <div style="font-size:14px;font-weight:500;margin-bottom:16px;">Recent completed adoptions</div>
            @forelse($recentAdoptions as $app)
                <div style="display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:0.5px solid rgba(0,0,0,0.06);">
                    <div style="width:32px;height:32px;border-radius:50%;background:#EEEDFE;color:#3C3489;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;flex-shrink:0;">
                        {{ strtoupper(substr($app->user->name, 0, 1)) }}
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:13px;font-weight:500;">{{ $app->user->name }}</div>
                        <div style="font-size:11px;color:#888;">adopted {{ $app->pet->name }}</div>
                    </div>
                    <div style="font-size:11px;color:#888;">{{ $app->submitted_at->format('M j') }}</div>
                </div>
            @empty
                <div class="text-muted">No completed adoptions yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection