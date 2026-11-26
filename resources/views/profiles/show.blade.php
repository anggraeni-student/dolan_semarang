@extends('layouts.app')
@section('title', $profileUser->name . ' — Profil Dolan Semarang')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('community.index') }}">Komunitas</a> /
        {{ $profileUser->name }}
    </div>

    {{-- HEADER PROFIL --}}
    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <span class="avatar" style="width:72px;height:72px;font-size:24px">{{ $profileUser->avatar_initials }}</span>
        <div style="min-width:0">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                <h1 style="margin:0;font-size:1.7rem">{{ $profileUser->name }}</h1>
                <span class="badge-lime"><span class="material-icons" style="font-size:13px">verified</span>{{ $profileUser->badge['name'] }}</span>
                <span class="card-meta" style="font-weight:700">Level {{ $profileUser->badge['level'] }}</span>
            </div>
            @if ($profileUser->bio)
                <p style="margin:4px 0 0;color:var(--ink-mid);font-size:13.5px">{{ $profileUser->bio }}</p>
            @endif
        </div>
    </div>

    {{-- STAT STRIP --}}
    <div class="card" style="display:flex;align-items:center;justify-content:space-around;gap:0;padding:14px 24px;margin-top:16px;flex-wrap:wrap">
        <div style="text-align:center;padding:6px 10px">
            <div style="font-weight:800;font-size:22px;color:var(--primary)">{{ $reviews->count() }}</div>
            <div style="font-size:11.5px;color:var(--ink-mid)">review</div>
        </div>
        <div style="width:1px;height:34px;background:var(--pink-line)"></div>
        <div style="text-align:center;padding:6px 10px">
            <div style="font-weight:800;font-size:22px;color:var(--primary)">{{ number_format($profileUser->helpful_received) }}</div>
            <div style="font-size:11.5px;color:var(--ink-mid)">terbantu</div>
        </div>
        <div style="width:1px;height:34px;background:var(--pink-line)"></div>
        <div style="text-align:center;padding:6px 10px">
            <div style="font-weight:800;font-size:22px;color:var(--primary)">{{ $profileUser->provinces_visited }}</div>
            <div style="font-size:11.5px;color:var(--ink-mid)">tempat<br>dikunjungi</div>
        </div>
        <div style="width:1px;height:34px;background:var(--pink-line)"></div>
        <div style="text-align:center;padding:6px 10px">
            <div style="font-weight:800;font-size:22px;color:var(--primary)">Level {{ $profileUser->badge['level'] }}</div>
            <div style="font-size:11.5px;color:var(--ink-mid)">{{ $profileUser->badge['name'] }}</div>
        </div>
    </div>

    {{-- REVIEW + KOLEKSI --}}
    <div style="display:flex;gap:20px;align-items:flex-start;margin-top:24px" class="profile-cols">
        {{-- KIRI: REVIEW --}}
        <div style="flex:1;min-width:0">
            <div class="section-title">
                <h2>Review</h2>
            </div>
            @forelse ($reviews as $review)
                <div class="card" style="display:flex;gap:14px;padding:15px 16px;align-items:center;margin-bottom:12px">
                    <img src="{{ asset($review->destination->hero_image) }}" alt="{{ $review->destination->name }}" style="width:86px;height:86px;object-fit:cover;border-radius:12px;flex-shrink:0">
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                            <a href="{{ route('destinations.show', $review->destination->slug) }}" style="font-weight:800;font-size:14.5px;color:var(--ink)">{{ $review->destination->name }}</a>
                            <span class="stars">@for ($i = 1; $i <= 5; $i++)@if ($i <= round($review->score))★@else<span class="off">★</span>@endif @endfor</span>
                            <span class="rating-num" style="font-size:14px">{{ number_format($review->score, 1) }}</span>
                        </div>
                        <p style="font-size:13px;color:var(--ink-mid);font-style:italic;line-height:1.5;margin:4px 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $review->body }}</p>
                        <div class="card-meta">👍 {{ $review->helpful_count }} terbantu · {{ $review->visited_at?->translatedFormat('M Y') ?? $review->created_at->translatedFormat('M Y') }}</div>
                    </div>
                </div>
            @empty
                <div class="empty-state">Belum ada review.</div>
            @endforelse
        </div>

        {{-- KANAN: KOLEKSI --}}
        <div style="flex:0 0 300px;min-width:0" class="profile-side">
            <div class="card" style="padding:16px 18px">
                <div style="display:flex;align-items:center;gap:6px;font-weight:800;font-size:14px;margin-bottom:12px">
                    <span class="material-icons" style="font-size:17px;color:var(--primary)">bookmarks</span>
                    Koleksi
                </div>
                @if ($collections->isEmpty())
                    <div class="card-meta">Belum ada koleksi.</div>
                @else
                    <div style="display:flex;flex-direction:column;gap:10px">
                        @foreach ($collections as $collection)
                            <span class="cat-chip" style="justify-content:space-between">
                                <span style="display:inline-flex;align-items:center;gap:6px;min-width:0">
                                    <span class="material-icons" style="font-size:14px;color:var(--primary)">{{ $collection->icon ?? 'bookmark' }}</span>
                                    {{ $collection->name }}
                                </span>
                                <b>({{ $collection->destinations_count }})</b>
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .profile-cols { flex-direction: column !important; }
            .profile-side { flex: 1 1 auto !important; width: 100%; }
        }
    </style>
@endsection
