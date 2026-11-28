@extends('layouts.app')
@section('title', $destination->name . ' — Review & Rekomendasi')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('destinations.index') }}">Tempat</a> /
        <a href="{{ route('categories.show', $destination->category->slug) }}">{{ $destination->category->name }}</a> /
        {{ $destination->name }}
    </div>

    {{-- HERO --}}
    <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:24px" class="detail-hero">
        <div style="border-radius:18px;overflow:hidden;position:relative">
            <img src="{{ asset($destination->hero_image) }}" alt="{{ $destination->name }}" style="width:100%;height:340px;object-fit:cover">
            <span class="tag card-cat" style="top:14px;left:14px">{{ $destination->category->name }}</span>
        </div>
        <div>
            <h1 style="font-size:2.1rem">{{ $destination->name }}</h1>
            <div class="card-meta" style="display:flex;align-items:center;gap:6px;margin-bottom:12px">
                <span class="material-icons" style="font-size:16px;color:var(--primary)">place</span>
                {{ $destination->location }}
            </div>
            <div class="rating-line" style="margin-bottom:16px">
                <span class="stars" style="font-size:18px">@for ($i = 1; $i <= 5; $i++)@if ($i <= round($destination->rating))★@else<span class="off">★</span>@endif @endfor</span>
                <span class="rating-num" style="font-size:26px">{{ number_format($destination->rating, 1) }}</span>
                <a href="{{ route('reviews.index', $destination->slug) }}" style="font-weight:700">{{ $destination->review_count }} review ›</a>
            </div>
            <p style="color:var(--ink-mid);font-size:14.5px">{{ $destination->description }}</p>
            @if ($destination->creator)
                <div class="card-meta" style="margin-top:10px">
                    <span class="material-icons" style="font-size:14px;vertical-align:-2px;color:var(--primary)">volunteer_activism</span>
                    Ditambahkan oleh kontributor
                    <a href="{{ route('profiles.show', $destination->creator->username) }}" style="font-weight:700;color:var(--ink)">{{ $destination->creator->name }}</a>
                    <x-admin-badge :user="$destination->creator" />
                </div>
            @endif
            @auth
                @if (($destination->authorId() ?? null) !== auth()->id())
                    <div style="margin-top:10px">
                        <x-report-button :model="$destination" />
                    </div>
                @endif
            @endauth
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px">
                @auth
                    <a href="{{ route('reviews.create', $destination->slug) }}" class="btn btn-primary">✍ Tulis Review</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">✍ Tulis Review</a>
                @endauth
                <a href="{{ route('map.index') }}" class="btn btn-outline">Lihat di Peta</a>

                @auth
                    @php
                        $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                            ->where('destination_id', $destination->id)
                            ->exists();
                    @endphp
                    <form method="POST" action="{{ route('wishlist.toggle', $destination) }}">
                        @csrf
                        <button type="submit" class="btn {{ $isWishlisted ? 'btn-primary' : 'btn-outline' }}" style="display:inline-flex;align-items:center;gap:6px">
                            <span class="material-icons" style="font-size:17px">{{ $isWishlisted ? 'favorite' : 'favorite_border' }}</span>
                            {{ $isWishlisted ? 'Tersimpan di Wishlist' : 'Simpan ke Wishlist' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px">
                        <span class="material-icons" style="font-size:17px">favorite_border</span> Simpan ke Wishlist
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- SKOR 4 ASPEK --}}
    <div class="section">
        <div class="section-title"><h2>Penilaian Komunitas</h2></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:14px">
            @php
                $aspects = [
                    ['label' => 'Pemandangan', 'icon' => 'landscape', 'score' => round($destination->reviews->avg('score_view'), 1)],
                    ['label' => 'Akses', 'icon' => 'route', 'score' => round($destination->reviews->avg('score_access'), 1)],
                    ['label' => 'Kebersihan', 'icon' => 'cleaning_services', 'score' => round($destination->reviews->avg('score_cleanliness'), 1)],
                    ['label' => 'Fasilitas', 'icon' => 'storefront', 'score' => round($destination->reviews->avg('score_facility'), 1)],
                ];
            @endphp
            @foreach ($aspects as $a)
                <div class="card" style="padding:16px">
                    <div style="display:flex;align-items:center;gap:8px;color:var(--ink-mid);font-size:13px;font-weight:600">
                        <span class="material-icons" style="font-size:18px;color:var(--primary)">{{ $a['icon'] }}</span>
                        {{ $a['label'] }}
                    </div>
                    <div class="rating-num" style="font-size:26px;margin-top:6px">{{ $a['score'] ?? '–' }}<span style="font-size:14px;color:var(--ink-soft)">/5</span></div>
                    <div style="height:6px;background:var(--line);border-radius:3px;margin-top:8px;overflow:hidden">
                        <div style="height:100%;width:{{ ($a['score'] ?? 0) / 5 * 100 }}%;background:var(--primary);border-radius:3px"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- REVIEW TERATAS --}}
    <div class="section">
        <div class="section-title">
            <h2>Review Teratas</h2>
            <a class="see-all" href="{{ route('reviews.index', $destination->slug) }}">Lihat semua {{ $destination->review_count }} review ›</a>
        </div>
        @forelse ($topReviews as $review)
            <x-review-card :review="$review" />
        @empty
            <div class="empty-state">Belum ada review. Jadilah yang pertama!</div>
        @endforelse
    </div>

    {{-- INFO PRAKTIS --}}
    <div class="surface">
        <h3 style="margin-bottom:10px">Info Praktis</h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;font-size:14px">
            <div>
                <div class="card-meta" style="font-weight:700;color:var(--primary-deep)">HTM (Harga Tiket)</div>
                {{ $destination->htm > 0 ? 'Rp ' . number_format($destination->htm, 0, ',', '.') : 'Gratis' }}
            </div>
            <div>
                <div class="card-meta" style="font-weight:700;color:var(--primary-deep)">Jam Buka</div>
                {{ $destination->opening_hours ?? '–' }}
            </div>
            <div>
                <div class="card-meta" style="font-weight:700;color:var(--primary-deep)">Waktu Terbaik</div>
                {{ $destination->best_time ?? '–' }}
            </div>
            <div>
                <div class="card-meta" style="font-weight:700;color:var(--primary-deep)">Akses</div>
                {{ $destination->access_info ?? '–' }}
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .detail-hero { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection