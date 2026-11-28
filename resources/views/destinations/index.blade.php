@extends('layouts.app')
@section('title', 'Semua Destinasi di Semarang Raya')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        Tempat
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:6px">
        <div>
            <h1 style="margin:0">Semua Destinasi</h1>
            <p style="color:var(--ink-mid);margin:6px 0 0;font-size:14px">
                {{ $destinations->count() }} tempat di Semarang & sekitarnya · pilih kategori di strip atas untuk memfilter
            </p>
        </div>
        @auth
            <a href="{{ route('destinations.create') }}" class="btn btn-primary">+ Tambah Tempat Baru</a>
        @endauth
    </div>

    {{-- Pencarian lokal + filter HTM/jam buka + urutan (bukan filter kategori: itu sudah di strip atas) --}}
    <form method="GET" action="{{ route('destinations.index') }}" style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;align-items:center">
        @if ($sort !== 'rating')<input type="hidden" name="sort" value="{{ $sort }}">@endif
        <div style="display:flex;align-items:center;gap:8px;flex:1;min-width:220px;border:1px solid var(--line);border-radius:999px;padding:8px 16px;background:#FAFAFA">
            <span class="material-icons" style="font-size:17px;color:var(--ink-soft)">search</span>
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama / lokasi tempat…" aria-label="Cari tempat" style="border:none;background:none;outline:none;flex:1;min-width:0;font:inherit;font-size:13px;color:var(--ink)">
        </div>

        {{-- Filter HTM (harga tiket) --}}
        <label style="display:inline-flex;align-items:center;gap:6px;border:1px solid var(--line);border-radius:999px;padding:7px 8px 7px 14px;background:#FAFAFA;font-size:12.5px;color:var(--ink-mid)">
            <span class="material-icons" style="font-size:15px;color:var(--ink-soft)">confirmation_number</span>
            <select name="htm" onchange="this.form.submit()" style="border:none;background:none;outline:none;font:inherit;font-weight:700;color:var(--ink)">
                <option value="">Semua HTM</option>
                <option value="gratis" @selected($htm === 'gratis')>Gratis</option>
                <option value="murah" @selected($htm === 'murah')>≤ Rp10rb</option>
                <option value="sedang" @selected($htm === 'sedang')>Rp10–50rb</option>
                <option value="mahal" @selected($htm === 'mahal')>&gt; Rp50rb</option>
            </select>
        </label>

        {{-- Filter jam buka --}}
        <label style="display:inline-flex;align-items:center;gap:6px;border:1px solid var(--line);border-radius:999px;padding:7px 8px 7px 14px;background:#FAFAFA;font-size:12.5px;color:var(--ink-mid)">
            <span class="material-icons" style="font-size:15px;color:var(--ink-soft)">schedule</span>
            <select name="jam" onchange="this.form.submit()" style="border:none;background:none;outline:none;font:inherit;font-weight:700;color:var(--ink)">
                <option value="">Semua Jam Buka</option>
                <option value="pagi" @selected($jam === 'pagi')>Buka pagi (≤ 09.00)</option>
                <option value="malam" @selected($jam === 'malam')>Tutup malam (≥ 18.00)</option>
                <option value="24jam" @selected($jam === '24jam')>24 jam</option>
            </select>
        </label>
        <noscript><button type="submit" class="btn btn-outline" style="padding:8px 14px;font-size:13px">Terapkan</button></noscript>
    </form>

    @php
        $sortLabels = ['rating' => 'Rating Tertinggi', 'review' => 'Terbanyak Direview', 'recent' => 'Terbaru'];
        $carry = array_filter(['q' => $q, 'htm' => $htm, 'jam' => $jam]);
        $prefix = $carry ? '&' : '?';
    @endphp
    <div style="display:flex;gap:10px;margin-top:10px;flex-wrap:wrap;align-items:center">
        @foreach ($sortLabels as $key => $label)
            <a href="{{ route('destinations.index', $carry) }}{{ $prefix }}sort={{ $key }}"
               class="cat-chip {{ $sort === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
        @if ($htm !== '' || $jam !== '' || $q !== '')
            <a href="{{ route('destinations.index') }}" class="cat-chip" style="border-style:dashed">
                <span class="material-icons" style="font-size:14px">close</span>Reset filter
            </a>
        @endif
    </div>

    {{-- Grid destinasi --}}
    @if ($destinations->isNotEmpty())
        <div class="grid-cards" style="margin-top:18px">
            @foreach ($destinations as $destination)
                <x-destination-card :destination="$destination" />
            @endforeach
        </div>
    @else
        <div class="empty-state" style="margin-top:18px">
            <span class="material-icons" style="font-size:40px;color:var(--ink-soft)">explore_off</span>
            <p style="margin-top:8px">@if ($q) Tidak ada tempat yang cocok dengan "{{ $q }}". @elseif ($htm !== '' || $jam !== '') Tidak ada tempat yang cocok dengan filter HTM/jam buka yang dipilih. @else Belum ada tempat. @endif</p>
            <a href="{{ route('destinations.index') }}" class="btn btn-outline">Reset pencarian &amp; filter</a>
        </div>
    @endif
@endsection
