@extends('layouts.app')
@section('title', 'Kategori: ' . $category->name)

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('destinations.index') }}">Tempat</a> /
        {{ $category->name }}
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:6px">
        <h1 style="margin:0">{{ $category->name }}</h1>
        <div style="font-size:13px;color:var(--ink-mid)"><b style="color:var(--ink)">{{ $destinations->count() }}</b> tempat · diurutkan dari rating tertinggi</div>
    </div>

    <div style="font-size:13px;color:var(--ink-mid);margin-top:6px">
        Ingin ganti kategori? Pakai strip kategori di atas —
        <a href="{{ route('destinations.index') }}" style="color:var(--primary);font-weight:700">atau lihat semua destinasi ›</a>
    </div>

    {{-- Grid destinasi. Filter kategori cukup SEKALI lewat strip di atas:
         halaman ini memang sudah difilter per kategori, jadi tidak ada
         lagi chips filter kedua di bawah judul. --}}
    @forelse ($destinations as $destination)
        @if ($loop->first)
            <div class="grid-cards" style="margin-top:18px">
        @endif
            <x-destination-card :destination="$destination" />
        @if ($loop->last)
            </div>
        @endif
    @empty
        <div class="empty-state" style="margin-top:18px">
            <span class="material-icons" style="font-size:40px;color:var(--ink-soft)">explore_off</span>
            <p style="margin-top:8px">Belum ada tempat di kategori ini.</p>
            <a href="{{ route('destinations.index') }}" class="btn btn-outline">Lihat semua destinasi</a>
        </div>
    @endforelse
@endsection
