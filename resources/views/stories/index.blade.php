@extends('layouts.app')
@section('title', 'Success Stories')

@section('content')
<div class="page">
    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <div class="page-title">Success stories</div>
            <div class="page-sub">Families who found their forever companion through Homebound.</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            @auth
                @if(auth()->user()->role === 'adopter')
                    <a href="{{ route('stories.create') }}" class="btn btn-primary btn-sm">Share your story</a>
                @endif
                @if(auth()->user()->isAdmin() && $pendingCount > 0)
                    <span class="badge badge-pending">{{ $pendingCount }} awaiting review</span>
                @endif
            @endauth
        </div>
    </div>

    {{-- Admin pending stories --}}
    @if(auth()->check() && auth()->user()->isAdmin() && $pendingCount > 0)
        @php $pending = \App\Models\SuccessStory::with('user')->where('is_published', false)->latest()->get(); @endphp
        <div class="card" style="margin-bottom:24px;border:1.5px solid var(--amber-light);">
            <div style="font-size:13px;font-weight:500;margin-bottom:12px;color:var(--amber);">⏳ Pending approval ({{ $pendingCount }})</div>
            @foreach($pending as $s)
                <div style="padding:12px 0;border-bottom:0.5px solid var(--border);display:flex;gap:12px;align-items:flex-start;">
                    <div style="flex:1;">
                        <div style="font-weight:500;margin-bottom:2px;">{{ $s->pet_name }} · by {{ $s->user->name }}</div>
                        <div style="font-size:13px;color:var(--ink-2);">{{ Str::limit($s->content, 120) }}</div>
                    </div>
                    <div style="display:flex;gap:6px;flex-shrink:0;">
                        <form method="POST" action="{{ route('stories.approve', $s) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-primary btn-sm">Publish</button>
                        </form>
                        <form method="POST" action="{{ route('stories.destroy', $s) }}" data-confirm="Remove this story?">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($stories->isEmpty())
        <div class="card" style="text-align:center;padding:64px 24px;">
            <div style="font-size:48px;margin-bottom:16px;">🐾</div>
            <div style="font-family:'Lora',serif;font-size:20px;font-weight:600;margin-bottom:8px;">No stories yet</div>
            <div class="text-muted">Be the first to share your adoption journey!</div>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;">
            @foreach($stories as $story)
                <div class="card" style="padding:0;overflow:hidden;">
                    @if($story->photo_path)
                        <img src="{{ $story->photo_path }}" alt="{{ $story->pet_name }}"
                             style="width:100%;height:200px;object-fit:cover;">
                    @else
                        <div style="height:120px;background:var(--green-light);display:flex;align-items:center;justify-content:center;font-size:52px;">🐾</div>
                    @endif
                    <div style="padding:18px 20px;">
                        <div style="font-family:'Lora',serif;font-size:17px;font-weight:600;margin-bottom:4px;">
                            {{ $story->pet_name }} found their forever home!
                        </div>
                        <div style="font-size:12px;color:var(--ink-3);margin-bottom:12px;">
                            Adopted by {{ $story->user->name }} · {{ $story->created_at->format('M Y') }}
                        </div>
                        <div style="font-size:13px;color:var(--ink-2);line-height:1.7;">
                            "{{ Str::limit($story->content, 180) }}"
                        </div>
                        @if(auth()->check() && auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('stories.destroy', $story) }}"
                                  data-confirm="Remove this story?" style="margin-top:12px;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div style="margin-top:24px;">{{ $stories->links() }}</div>
    @endif
</div>
@endsection
