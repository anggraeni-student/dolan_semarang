@extends('layouts.app')

@section('title', 'Dolan Semarang - Direktori Wisata Lokal')

@section('content')
    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Baris Tab Kategori --}}
    <div class="flex items-center gap-6 border-b border-gray-100 pb-3 mb-8 overflow-x-auto text-sm">
        <a href="{{ route('wisata.index') }}"
           class="whitespace-nowrap pb-1 transition {{ !request('category') ? 'text-rose-600 font-bold border-b-2 border-rose-600' : 'text-gray-500 hover:text-gray-900' }}">
            Semua
        </a>
        @foreach ($categories as $cat)
            <a href="{{ route('wisata.index', ['category' => $cat, 'search' => request('search')]) }}"
               class="whitespace-nowrap pb-1 transition {{ request('category') == $cat ? 'text-rose-600 font-bold border-b-2 border-rose-600' : 'text-gray-500 hover:text-gray-900' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Layout 2 Kolom (Kiri: Destinasi, Kanan: Sidebar Pink) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Kolom Kiri (8 kolom) --}}
        <div class="lg:col-span-8">
            <div class="flex items-center justify-between mb-5">
                <h1 class="text-xl font-extrabold text-gray-900">
                    {{ request('category') ? 'Kategori: ' . request('category') : 'Rekomendasi Untukmu' }}
                </h1>
                @if (request()->filled('search') || request()->filled('category'))
                    <a href="{{ route('wisata.index') }}" class="text-xs font-semibold text-rose-600 hover:underline">
                        Reset filter
                    </a>
                @endif
            </div>

            @if ($destinations->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                    @foreach ($destinations as $item)
                        <a href="{{ route('wisata.show', $item) }}" class="group block bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:border-rose-200 transition">
                            {{-- Area Gambar + Badge --}}
                            <div class="relative h-36 bg-gradient-to-br from-rose-100 via-orange-50 to-amber-100 overflow-hidden">
                                @if ($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-rose-300 text-4xl">
                                        🏞️
                                    </div>
                                @endif

                                {{-- Badge Kategori (Kiri Atas) --}}
                                <span class="absolute top-2.5 left-2.5 bg-white/90 backdrop-blur text-gray-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                                    {{ $item->category }}
                                </span>

                                {{-- Badge KLIK (Kanan Atas) --}}
                                <span class="absolute top-2.5 right-2.5 bg-rose-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm group-hover:bg-rose-700 transition">
                                    KLIK &rsaquo;
                                </span>
                            </div>

                            {{-- Info Kartu --}}
                            <div class="p-3.5">
                                <h2 class="font-bold text-gray-900 text-sm truncate group-hover:text-rose-600 transition">
                                    {{ $item->name }}
                                </h2>
                                <div class="flex items-center gap-1.5 mt-1 text-xs text-gray-500">
                                    <span class="text-amber-500 font-bold">★ {{ number_format($item->reviews_avg_rating ?? 0, 1) }}</span>
                                    <span>&middot;</span>
                                    <span>{{ $item->reviews_count }} review</span>
                                </div>
                                <p class="text-[11px] text-emerald-600 font-semibold mt-1.5 truncate">
                                    {{ $item->price_range }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-2xl text-gray-400 text-sm">
                    Destinasi tidak ditemukan. Coba kata kunci atau kategori lain.
                </div>
            @endif
        </div>

        {{-- Kolom Kanan: Sidebar Pink Lembut (4 kolom) --}}
        <aside class="lg:col-span-4 bg-[#FFF5F7] rounded-2xl p-6 border border-rose-100/70">
            <h2 class="font-bold text-gray-900 text-sm flex items-center gap-2 mb-5">
                <span>🏆</span> Destinasi Terpopuler
            </h2>

            <div class="space-y-4 mb-6">
                @foreach ($destinations->sortByDesc('reviews_count')->take(3) as $index => $top)
                    <a href="{{ route('wisata.show', $top) }}" class="flex items-center gap-3 group">
                        <span class="text-xs font-bold text-gray-400 w-3">{{ $loop->iteration }}</span>
                        <div class="w-9 h-9 rounded-full bg-rose-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($top->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-rose-700 group-hover:underline truncate">{{ $top->name }}</p>
                            <p class="text-xs text-gray-500">{{ $top->reviews_count }} review &middot; ★ {{ number_format($top->reviews_avg_rating ?? 0, 1) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('wisata.create') }}" class="block w-full text-center bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-3 rounded-xl shadow-sm transition">
                + Tambah Destinasi Baru
            </a>
        </aside>

    </div>
@endsection