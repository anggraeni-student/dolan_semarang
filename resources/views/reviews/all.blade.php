@extends('layouts.app')
@section('title', 'Jelajah Review Komunitas — Dolan Semarang')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        Review
    </div>

    @php
        $sortLabels = ['helpful' => 'Paling Membantu', 'recent' => 'Terbaru', 'rating' => 'Rating Tertinggi'];
        $sortLabel = $sortLabels[$sort] ?? $sortLabels['helpful'];
    @endphp

    <div style="display:grid;grid-template-columns:65fr 35fr;gap:24px;margin-top:6px" class="reviews-cols">

        {{-- KIRI: daftar review lintas destinasi --}}
        <div style="min-width:0">
            <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;flex-wrap:wrap">
                <h1 style="margin:0;font-size:1.7rem">Jelajah Review</h1>
                <div style="font-size:12.5px;color:var(--ink-mid)"><b style="color:var(--ink)">{{ number_format($reviews->count(), 0, ',', '.') }}</b> review dari seluruh Semarang Raya · urut: {{ $sortLabel }}</div>
            </div>

            {{-- Sort chips (bukan kategori — ini urutan) --}}
            <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap">
                @foreach ($sortLabels as $key => $label)
                    <a href="{{ route('reviews.all', array_filter(['kategori' => $activeCategory?->slug])) }}{{ $activeCategory ? '&' : '?' }}sort={{ $key }}"
                       class="cat-chip {{ $sort === $key ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
                @if ($activeCategory)
                    <a href="{{ route('reviews.all') }}" class="cat-chip" style="border-style:dashed">
                        <span class="material-icons" style="font-size:14px">close</span>Hapus filter {{ $activeCategory->name }}
                    </a>
                @endif
            </div>

            {{-- Daftar review --}}
            <div style="margin-top:14px">
                @forelse ($reviews as $r)
                    <div class="card" style="padding:0;margin-bottom:14px;overflow:hidden">
                        {{-- baris destinasi --}}
                        <a href="{{ route('destinations.show', $r->destination->slug) }}"
                           style="display:flex;align-items:center;gap:10px;padding:10px 16px;background:var(--pink-soft);text-decoration:none">
                            <img src="{{ asset($r->destination->hero_image) }}" alt="{{ $r->destination->name }}" style="width:34px;height:34px;object-fit:cover;border-radius:8px" loading="lazy">
                            <span style="font-weight:800;font-size:13px;color:var(--ink)">{{ $r->destination->name }}</span>
                            <span class="tag" style="background:#fff">{{ $r->destination->category->name }}</span>
                            <span class="material-icons" style="font-size:16px;color:var(--primary);margin-left:auto">arrow_forward</span>
                        </a>
                        {{-- isi review --}}
                        <div style="padding:0 16px 14px">
                            <x-review-card :review="$r" />
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <span class="material-icons" style="font-size:40px;color:var(--ink-soft)">rate_review</span>
                        <p style="margin-top:8px">@if ($activeCategory) Belum ada review untuk kategori {{ $activeCategory->name }}. @else Belum ada review sama sekali. @endif</p>
                        <a href="{{ route('destinations.index') }}" class="btn btn-primary">Cari tempat untuk direview</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- KANAN: filter kategori ringan + destinasi terbaik --}}
        <div style="display:flex;flex-direction:column;gap:16px">
            <div class="surface" style="padding:18px 20px">
                <div style="display:flex;align-items:center;gap:6px;font-weight:800;font-size:14px;margin-bottom:12px">
                    <span class="material-icons" style="font-size:17px;color:var(--primary)">filter_alt</span>
                    Filter Kategori Destinasi
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px">
                    @foreach ($categories as $cat)
                        <a href="{{ route('reviews.all', ['kategori' => $cat->slug]) }}"
                           class="cat-chip {{ $activeCategory?->id === $cat->id ? 'active' : '' }}">
                            <span class="material-icons" style="font-size:14px">{{ $cat->icon }}</span>{{ $cat->name }}
                        </a>
                    @endforeach
                </div>
                <div class="card-meta" style="margin-top:12px">Ini satu-satunya tempat filter kategori di halaman review — pas &amp; relevan.</div>
            </div>

            <div class="surface" style="padding:18px 20px">
                <div style="display:flex;align-items:center;gap:6px;font-weight:800;font-size:14px;margin-bottom:12px">
                    <span class="material-icons" style="font-size:17px;color:var(--primary)">military_tech</span>
                    Rating Tertinggi Saat Ini
                </div>
                @foreach ($topDestinations as $d)
                    <a href="{{ route('destinations.show', $d->slug) }}" style="display:flex;align-items:center;gap:10px;padding:9px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--pink-line)' : '' }}">
                        <img src="{{ asset($d->hero_image) }}" alt="{{ $d->name }}" style="width:38px;height:38px;object-fit:cover;border-radius:10px" loading="lazy">
                        <div style="min-width:0;flex:1">
                            <div style="font-weight:700;font-size:13px;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $d->name }}</div>
                            <div class="card-meta">{{ $d->review_count }} review</div>
                        </div>
                        <span class="rating-num" style="font-size:15px">{{ number_format($d->rating, 1) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .reviews-cols { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection
