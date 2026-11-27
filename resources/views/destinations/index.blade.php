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

    {{-- Pencarian lokal + urutan (bukan filter kategori: itu sudah di strip atas) --}}
    <form method="GET" action="{{ route('destinations.index') }}" style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;align-items:center">
        <div style="display:flex;align-items:center;gap:8px;flex:1;min-width:220px;border:1px solid var(--line);border-radius:999px;padding:8px 16px;background:#FAFAFA">
            <span class="material-icons" style="font-size:17px;color:var(--ink-soft)">search</span>
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama / lokasi tempat…" aria-label="Cari tempat" style="border:none;background:none;outline:none;flex:1;min-width:0;font:inherit;font-size:13px;color:var(--ink)">
        </div>
        @php
            $sortLabels = ['rating' => 'Rating Tertinggi', 'review' => 'Terbanyak Direview', 'recent' => 'Terbaru'];
        @endphp
        @foreach ($sortLabels as $key => $label)
            <a href="{{ route('destinations.index', array_filter(['q' => $q])) }}{{ $q ? '&' : '?' }}sort={{ $key }}"
               class="cat-chip {{ $sort === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </form>

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
            <p style="margin-top:8px">@if ($q) Tidak ada tempat yang cocok dengan "{{ $q }}". @else Belum ada tempat. @endif</p>
            <a href="{{ route('destinations.index') }}" class="btn btn-outline">Reset pencarian</a>
        </div>
    @endif
@endsection
