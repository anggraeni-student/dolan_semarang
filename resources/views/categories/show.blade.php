@extends('layouts.app')
@section('title', 'Kategori: ' . $category->name)

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('categories.show', 'kuliner') }}">Tempat</a> /
        {{ $category->name }}
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:6px">
        <h1 style="margin:0">Kategori: {{ $category->name }}</h1>
        <div style="font-size:13px;color:var(--ink-mid)"><b style="color:var(--ink)">{{ $destinations->count() }}</b> tempat · diurutkan dari rating tertinggi</div>
    </div>

    {{-- Filter chips (sub-kategori seperti prototype) --}}
    @php
        $filters = [
            'kuliner' => 'Kuliner',
            'sejarah' => 'Sejarah & Budaya',
            'nongkrong' => 'Nongkrong',
            'kafe' => 'Kafe & Kedai',
            'alam' => 'Alam & Bukit',
        ];
    @endphp
    <div style="display:flex;gap:10px;margin-top:12px;align-items:center;flex-wrap:wrap">
        <a href="{{ route('categories.show', $category->slug) }}"
           class="cat-chip {{ $activeFilter ? '' : 'active' }}">Semua</a>
        @foreach ($filters as $filterSlug => $filterLabel)
            <a href="{{ route('categories.show', $category->slug) }}?filter={{ $filterSlug }}"
               class="cat-chip {{ $activeFilter === $filterSlug ? 'active' : '' }}">{{ $filterLabel }}</a>
        @endforeach
        <span class="material-icons" style="font-size:17px;color:var(--ink-soft);margin-left:4px">tune</span>
    </div>

    {{-- Grid destinasi --}}
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
            <a href="{{ route('categories.show', 'kuliner') }}" class="btn btn-outline">Lihat kategori lain</a>
        </div>
    @endforelse
@endsection
