@extends('layouts.app')
@section('title', 'Wishlist Destinasi Saya')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> / Wishlist Saya
    </div>

    <div class="section-title" style="margin-bottom:20px">
        <div>
            <h1 style="font-size:1.8rem">Wishlist Destinasi Saya</h1>
            <p style="color:var(--ink-mid);font-size:14px">Daftar tempat wisata Semarang yang ingin kamu kunjungi.</p>
        </div>
        <a href="{{ route('itineraries.index') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px">
            <span class="material-icons" style="font-size:18px">event_note</span> Buat Rencana Trip
        </a>
    </div>

    @if ($wishlists->isEmpty())
        <div class="empty-state">
            <span class="material-icons" style="font-size:40px;color:var(--primary);display:block;margin-bottom:8px">favorite_border</span>
            <strong>Wishlist kamu masih kosong.</strong>
            <p style="margin:6px 0 14px">Jelajahi destinasi seru di Semarang dan klik tombol "Simpan ke Wishlist".</p>
            <a href="{{ route('categories.show', 'kuliner') }}" class="btn btn-primary">Jelajahi Destinasi</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:18px">
            @foreach ($wishlists as $item)
                @php $dest = $item->destination; @endphp
                @if ($dest)
                    <div class="card" style="overflow:hidden;display:flex;flex-direction:column;justify-content:space-between">
                        <div>
                            <div style="position:relative">
                                <a href="{{ route('destinations.show', $dest->slug) }}">
                                    <img src="{{ asset($dest->hero_image) }}" alt="{{ $dest->name }}" style="width:100%;height:175px;object-fit:cover">
                                </a>
                                @if ($dest->category)
                                    <span class="tag card-cat" style="top:10px;left:10px">{{ $dest->category->name }}</span>
                                @endif
                            </div>
                            <div style="padding:14px">
                                <a href="{{ route('destinations.show', $dest->slug) }}" style="font-weight:700;font-size:16px;display:block;margin-bottom:4px">
                                    {{ $dest->name }}
                                </a>
                                <div class="card-meta" style="display:flex;align-items:center;gap:4px;margin-bottom:8px">
                                    <span class="material-icons" style="font-size:14px;color:var(--primary)">place</span>
                                    {{ $dest->location }}
                                </div>
                                <div style="font-size:13px;font-weight:600;color:var(--primary-deep)">
                                    HTM: {{ $dest->htm > 0 ? 'Rp ' . number_format($dest->htm, 0, ',', '.') : 'Gratis' }}
                                </div>
                            </div>
                        </div>
                        <div style="padding:0 14px 14px;display:flex;gap:8px">
                            <a href="{{ route('destinations.show', $dest->slug) }}" class="btn btn-outline" style="flex:1;text-align:center;padding:8px">Detail</a>
                            <form method="POST" action="{{ route('wishlist.toggle', $dest) }}">
                                @csrf
                                <button type="submit" class="btn btn-ghost" style="color:#dc2626;padding:8px 10px" title="Hapus dari Wishlist">
                                    <span class="material-icons" style="font-size:18px">delete_outline</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@endsection