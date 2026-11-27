@extends('layouts.app')
@section('title', 'Review ' . $destination->name)

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('categories.show', $destination->category->slug) }}">Destinasi</a> /
        <a href="{{ route('destinations.show', $destination->slug) }}">{{ $destination->name }}</a> /
        Review
    </div>

    @php
        $sortLabels = ['helpful' => 'Paling Membantu', 'recent' => 'Terbaru', 'rating' => 'Rating Tertinggi'];
        $sortLabel = $sortLabels[$sort] ?? $sortLabels['helpful'];
        $maxCount = $dist->max() ?: 1;
    @endphp

    <div style="display:grid;grid-template-columns:65fr 35fr;gap:24px;margin-top:6px" class="reviews-cols">

        {{-- KIRI: daftar review --}}
        <div style="min-width:0">
            <div style="display:flex;align-items:baseline;justify-content:space-between;gap:12px;flex-wrap:wrap">
                <h1 style="margin:0;font-size:1.7rem">Review {{ $destination->name }}</h1>
                <div style="font-size:12.5px;color:var(--ink-mid)"><b style="color:var(--ink)">{{ number_format($destination->review_count, 0, ',', '.') }}</b> review · urut: {{ $sortLabel }}</div>
            </div>

            {{-- Sort chips --}}
            <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap">
                @foreach ($sortLabels as $key => $label)
                    <a href="{{ route('reviews.index', $destination->slug) }}?sort={{ $key }}"
                       class="cat-chip {{ $sort === $key ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </div>

            {{-- Daftar review --}}
            <div style="margin-top:14px">
                @forelse ($reviews as $r)
                    <x-review-card :review="$r" />
                @empty
                    <div class="empty-state">
                        <span class="material-icons" style="font-size:40px;color:var(--ink-soft)">rate_review</span>
                        <p style="margin-top:8px">Belum ada review. Jadilah yang pertama!</p>
                        <a href="{{ route('reviews.create', $destination->slug) }}" class="btn btn-primary">+ Tulis Review</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- KANAN: ringkasan rating --}}
        <div>
            <div class="surface">
                <div style="font-weight:800;font-size:17px">Ringkasan Rating</div>

                <div style="display:flex;align-items:center;gap:12px;margin-top:10px">
                    <span class="rating-num" style="font-size:52px;font-weight:800;line-height:1">{{ number_format($destination->rating, 1) }}</span>
                    <div>
                        <span class="stars" style="font-size:15px;letter-spacing:2px">@for ($i = 1; $i <= 5; $i++)@if ($i <= round($destination->rating))★@else<span class="off">★</span>@endif @endfor</span>
                        <div style="font-size:12px;color:var(--ink-mid);margin-top:3px"><b>{{ number_format($destination->review_count, 0, ',', '.') }}</b> review</div>
                    </div>
                </div>

                {{-- Distribusi bintang --}}
                <div style="display:flex;flex-direction:column;gap:8px;margin-top:14px;font-size:12px;color:var(--ink-mid)">
                    @foreach ($dist as $star => $count)
                        <div style="display:flex;align-items:center;gap:8px">
                            <span style="width:20px;font-weight:700">{{ $star }}★</span>
                            <div style="flex:1;height:8px;border-radius:99px;background:var(--line)">
                                <div style="width:{{ round($count / $maxCount * 100) }}%;height:100%;border-radius:99px;background:var(--primary)"></div>
                            </div>
                            <span style="width:36px;text-align:right;font-weight:700">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('reviews.create', $destination->slug) }}" class="btn btn-primary" style="width:100%;text-align:center;margin-top:18px">+ Tulis Review untuk {{ $destination->name }}</a>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .reviews-cols { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection
