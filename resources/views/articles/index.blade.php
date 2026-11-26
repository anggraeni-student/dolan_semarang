@extends('layouts.app')
@section('title', 'Panduan Perjalanan')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> / Panduan
    </div>

    <div style="margin-bottom:14px">
        <h1 style="margin-bottom:6px">Panduan Perjalanan</h1>
        <p style="color:var(--ink-mid);max-width:560px;margin:0">Itinerary, tips, dan hidden gems dari kontributor terverifikasi.</p>
    </div>

    {{-- ARTIKEL UTAMA --}}
    @if ($featured)
        <a href="{{ route('articles.show', $featured->slug) }}" class="card card-link" style="display:flex;margin-top:12px;flex-wrap:wrap">
            <img src="{{ asset($featured->cover_image) }}" alt="{{ $featured->title }}" style="width:45%;min-width:280px;height:220px;object-fit:cover">
            <div style="flex:1;padding:16px 22px;display:flex;flex-direction:column;gap:8px;min-width:260px">
                <div><span class="tag" style="background:var(--pink-soft);letter-spacing:.6px">{{ $featured->tag }}</span></div>
                <h2 style="font-size:1.45rem;margin:0">{{ $featured->title }}</h2>
                <p style="font-size:14px;color:var(--ink-mid);margin:0;line-height:1.55">{{ $featured->excerpt }}</p>
                <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--ink-soft);margin-top:auto;flex-wrap:wrap">
                    <span>oleh <b style="color:var(--ink)">{{ $featured->author->name }}</b></span>
                    <span class="badge-lime"><span class="material-icons" style="font-size:12px">verified</span>Kontributor Terverifikasi</span>
                    <span>· {{ $featured->read_minutes }} menit baca</span>
                    <span style="margin-left:auto;color:var(--star);font-weight:700">★ disukai <b style="color:var(--ink)">{{ number_format($featured->likes) }}</b></span>
                </div>
            </div>
        </a>
    @endif

    {{-- GRID ARTIKEL --}}
    <div class="section">
        <div class="section-title">
            <h2>Artikel Terbaru</h2>
        </div>
        <div class="grid-cards">
            @forelse ($articles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="card card-link">
                    <div class="card-img">
                        <img src="{{ asset($article->cover_image) }}" alt="{{ $article->title }}" loading="lazy">
                    </div>
                    <div class="card-body">
                        <span class="tag" style="background:var(--pink-soft);letter-spacing:.6px">{{ $article->tag }}</span>
                        <h3 class="card-title" style="margin-top:8px">{{ $article->title }}</h3>
                        <div class="card-meta" style="margin-top:6px">{{ $article->read_minutes }} menit baca · ★ disukai {{ number_format($article->likes) }}</div>
                    </div>
                </a>
            @empty
                <div class="empty-state" style="grid-column:1/-1">Belum ada artikel panduan.</div>
            @endforelse
        </div>
    </div>
@endsection
