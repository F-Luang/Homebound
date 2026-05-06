@extends('layouts.app')
@section('title', 'Smart Match')

@section('content')
                <div class="page">
                    <div class="page-header">
                        <div class="page-title">Smart matching</div>
                        <div class="page-sub">Tell us about your lifestyle — we'll find your best-fit companions.</div>
                    </div>

                    <div class="two-col" style="align-items:start;">

                        {{-- Preferences form --}}
                        <div class="card">
                            <div style="font-size:14px;font-weight:500;margin-bottom:4px;">Your preferences</div>
                            <div style="font-size:12px;color:var(--ink-3);margin-bottom:16px;">Must-haves narrow the pool. Weights rank the
                                results.</div>

                            <form method="POST" action="{{ route('match.run') }}">
                                @csrf

                                <div
                                    style="font-size:11px;font-weight:500;color:#185FA5;background:#E6F1FB;padding:6px 10px;border-radius:6px;margin-bottom:14px;">
                                    Must-have filters — pets not matching these are excluded
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Species</label>
                                    <select class="form-input" name="species">
                                        <option value="any" {{ old('species', 'any') === 'any' ? 'selected' : '' }}>Any</option>
                                        @foreach(['dog', 'cat', 'rabbit', 'bird', 'other'] as $s)
                                        <option value="{{ $s }}" {{ old('species') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Allergy-safe only</label>
                                    <select class="form-input" name="hypoallergenic">
                                        <option value="0" {{ old('hypoallergenic', '0') === '0' ? 'selected' : '' }}>No requirement
                                        </option>
                                        <option value="1" {{ old('hypoallergenic') === '1' ? 'selected' : '' }}>Yes — hypoallergenic only
                                        </option>
                                    </select>
                                </div>

                                <hr class="divider">
                                <div
                                    style="font-size:11px;font-weight:500;color:#0F6E56;background:#E1F5EE;padding:6px 10px;border-radius:6px;margin-bottom:14px;">
                                    Weighted preferences — adjust how much each factor matters
                                </div>

                                @php
    $weights = [
        'activity' => ['label' => 'Activity level', 'default' => 4],
        'kids' => ['label' => 'Good with kids', 'default' => 3],
        'size' => ['label' => 'Size match', 'default' => 2],
        'senior' => ['label' => 'Senior-friendly', 'default' => 1],
    ];
                                @endphp

                                @foreach($weights as $key => $w)
                                                <div class="form-group">
                                                    <div class="flex items-center justify-between">
                                                        <label class="form-label">{{ $w['label'] }}</label>
                                                        <span style="font-size:12px;font-weight:500;" id="lbl-{{ $key }}">{{ old(
            'weights.' . $key,
            $w['default']
        ) }}</span>
                                                    </div>
                                                    <input type="range" name="weights[{{ $key }}]" min="0" max="5" step="1"
                                                        value="{{ old('weights.' . $key, $w['default']) }}"
                                                        oninput="document.getElementById('lbl-{{ $key }}').textContent=this.value"
                                                        style="width:100%;">
                                                </div>
                                @endforeach

                                <div class="form-group">
                                    <label class="form-label">Preferred activity level</label>
                                    <select class="form-input" name="activity">
                                        <option value="low" {{ old('activity') === 'low' ? 'selected' : '' }}>Low — mostly indoors
                                        </option>
                                        <option value="moderate" {{ old('activity', 'moderate') === 'moderate' ? 'selected' : '' }}>
                                            Moderate — daily walks</option>
                                        <option value="high" {{ old('activity') === 'high' ? 'selected' : '' }}>High — outdoor enthusiast
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Preferred size</label>
                                    <select class="form-input" name="size">
                                        <option value="small" {{ old('size') === 'small' ? 'selected' : '' }}>Small</option>
                                        <option value="medium" {{ old('size', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="large" {{ old('size') === 'large' ? 'selected' : '' }}>Large</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Find my matches
                                    →</button>
                            </form>
                        </div>

                        {{-- Results --}}
                        <div>
                            <div style="font-size:14px;font-weight:500;margin-bottom:14px;">
                                @if(isset($results)) {{ $results->count() }} match{{ $results->count() !== 1 ? 'es' : '' }} found @else
                                Your matches will appear here @endif
                            </div>

                            @if(isset($results))
                                @if($results->isEmpty())
                                <div class="card" style="text-align:center;padding:40px;">
                                    <div style="font-size:32px;margin-bottom:12px;">🔍</div>
                                    <div class="text-muted">No pets match your must-have filters. Try adjusting them.</div>
                                </div>
                                @else
                                    @foreach($results as $i => $item)
                                                                @php $pet = $item['pet'];
                $score = $item['score']; @endphp
                                                                <a href="{{ route('pets.show', $pet) }}" style="display:block;margin-bottom:10px;">
                                                                    <div class="card" style="{{ $i === 0 ? 'border:1.5px solid var(--green);' : '' }}padding:14px 16px;">
                                                                        <div class="flex items-center gap-12">
                                                                            <div style="font-size:36px;width:52px;text-align:center;">
                                                                                {{ ['dog' => '🐕', 'cat' => '🐈', 'rabbit' => '🐇', 'bird' => '🐦', 'other' => '🐾'][$pet->species] ??
                    '🐾' }}
                                                                            </div>
                                                                            <div style="flex:1;">
                                                                                <div style="font-size:11px;font-weight:500;color:var(--green);margin-bottom:2px;">
                                                                                    {{ $score }}% match{{ $i === 0 ? ' — best fit' : '' }}
                                                                                </div>
                                                                                <div style="font-family:'Lora',serif;font-size:15px;font-weight:600;">{{ $pet->name }}</div>
                                                                                <div style="font-size:12px;color:var(--ink-3);">{{ $pet->breed ?? ucfirst($pet->species) }} · {{
                    ucfirst($pet->activity_level) }} · {{ ucfirst($pet->size) }}</div>
                                                                                <div style="margin-top:6px;height:4px;background:var(--border);border-radius:4px;overflow:hidden;">
                                                                                    <div
                                                                                        style="height:100%;width:{{ $score }}%;background:var(--green);border-radius:4px;transition:width 0.4s;">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                    @endforeach
                                @endif
                            @else
                            <div class="card" style="text-align:center;padding:48px;border-style:dashed;">
                                <div style="font-size:32px;margin-bottom:12px;">🐾</div>
                                <div class="text-muted">Set your preferences and hit <strong>Find my matches</strong>.</div>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
@endsection