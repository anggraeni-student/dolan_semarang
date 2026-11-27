@extends('layouts.app')
@section('title', 'Review & Rekomendasi Tempat Asyik di Semarang')

@section('content')

    {{-- HERO ringkas --}}
    <div style="margin-bottom:8px">
        <h1 style="margin-bottom:6px">Dolan ke Mana <span style="color:var(--primary)">Hari Ini?</span></h1>
        <p style="color:var(--ink-mid);max-width:560px">Review jujur dari warga — temukan tempat asyik berikutnya di Semarang & sekitarnya, baca pengalaman asli, lalu bagikan ceritamu.</p>
    </div>

    <x-semarang-filters />

    {{-- Rekomendasi personal (kalau user punya minat) --}}
    @if ($forYou && $forYou->isNotEmpty())
        <div class="section" style="margin-top:20px">
            <div class="section-title">
                <h2>Sesuai Minatmu</h2>
                <a class="see-all" href="{{ route('categories.show', $forYou->first()->category->slug) }}">Lihat semua ›</a>
            </div>
            <div class="grid-cards">
                @foreach ($forYou as $destination)
                    <x-destination-card :destination="$destination" />
                @endforeach
            </div>
        </div>
    @endif

    {{-- Grid utama --}}
    <div class="section">
        <div class="section-title">
            <h2>Tempat Populer</h2>
            <a class="see-all" href="{{ route('categories.show', 'kuliner') }}">Lihat semua ›</a>
        </div>
        <div class="grid-cards">
            @foreach ($destinations as $destination)
                <x-destination-card :destination="$destination" />
            @endforeach
        </div>
    </div>

    {{-- Panduan editor + komunitas --}}
    <div style="display:grid;grid-template-columns:1.6fr 1fr;gap:24px" class="two-col">
        <div class="section" style="margin-top:36px">
            <div class="section-title">
                <h2>Panduan Editor</h2>
                <a class="see-all" href="{{ route('articles.index') }}">Lihat semua ›</a>
            </div>
            @foreach ($articles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="card card-link" style="display:flex;gap:14px;padding:12px;margin-bottom:12px;align-items:center">
                    <img src="{{ asset($article->cover_image) }}" alt="{{ $article->title }}" style="width:96px;height:72px;object-fit:cover;border-radius:10px;flex-shrink:0">
                    <div>
                        <span class="tag" style="background:var(--pink-soft)">{{ $article->tag }}</span>
                        <div style="font-weight:800;font-size:14.5px;margin-top:6px">{{ $article->title }}</div>
                        <div class="card-meta">{{ $article->read_minutes }} menit baca · ★ disukai {{ number_format($article->likes) }}</div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="section" style="margin-top:36px">
            <div class="section-title">
                <h2>Komunitas Teraktif</h2>
            </div>
            <div class="surface">
                @foreach ($topUsers as $i => $u)
                    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;{{ $i < count($topUsers) - 1 ? 'border-bottom:1px solid var(--pink-line)' : '' }}">
                        <span class="avatar">{{ $u->avatar_initials }}</span>
                        <div style="min-width:0">
                            <a href="{{ route('profiles.show', $u->username) }}" style="font-weight:700;color:var(--ink)">{{ $u->name }}</a>
                            <div class="card-meta">{{ $u->reviews_count }} review</div>
                        </div>
                        @if ($i === 0)
                            <span class="badge-lime" style="margin-left:auto">Legenda Dolan</span>
                        @endif
                    </div>
                @endforeach
                <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap">
                    @auth
                        <a href="{{ route('reviews.create', 'kota-lama') }}" class="btn btn-primary">+ Tulis Review</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">+ Tulis Review</a>
                    @endauth
                    <a href="{{ route('community.index') }}" class="btn btn-outline">Lihat Komunitas</a>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA peta --}}
    <div class="surface" style="margin-top:40px;display:flex;align-items:center;gap:20px;flex-wrap:wrap">
        <span class="material-icons" style="font-size:44px;color:var(--primary)">map</span>
        <div style="flex:1;min-width:220px">
            <h3 style="margin-bottom:4px">Cari tempat lewat peta?</h3>
            <p style="margin:0;color:var(--ink-mid);font-size:14px">Lihat sebaran tempat dari Kota Lama sampai Bandungan dalam satu layar.</p>
        </div>
        <a href="{{ route('map.index') }}" class="btn btn-primary">Buka Peta Dolan</a>
    </div>

    <style>
        @media (max-width: 900px) {
            .two-col { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection
