@extends('layouts.app')

@section('title', 'Review Terbaru - Dolan Semarang')

@section('content')
    <nav class="text-xs text-gray-400 mb-5 flex items-center gap-2">
        <a href="{{ route('wisata.index') }}" class="hover:text-rose-600">Home</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">Review Terbaru</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Kolom Kiri: Feed Semua Ulasan --}}
        <div class="lg:col-span-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">Review Pengunjung Semarang</h1>
                    <p class="text-sm text-gray-500 mt-1">Pengalaman asli terbaru dari seluruh destinasi wisata.</p>
                </div>
                <span class="text-xs font-bold bg-rose-50 text-rose-600 px-3 py-1.5 rounded-full">
                    {{ $totalReviews }} Review
                </span>
            </div>

            <div class="space-y-4">
                @forelse ($reviews as $review)
                    <div class="p-5 rounded-2xl border border-gray-100 bg-white shadow-sm hover:border-rose-200 transition flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            {{-- Header Reviewer & Link Destinasi --}}
                            <div class="flex items-center justify-between gap-2 mb-2.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-rose-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($review->user_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ $review->user_name }}</p>
                                        <p class="text-xs text-amber-500 font-semibold">
                                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                            <span class="text-gray-400 font-normal ml-1">{{ $review->created_at->diffForHumans() }}</span>
                                        </p>
                                    </div>
                                </div>

                                @if ($review->destination)
                                    <a href="{{ route('wisata.show', $review->destination) }}"
                                       class="text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 px-3 py-1 rounded-full transition shrink-0">
                                        📍 {{ $review->destination->name }}
                                    </a>
                                @endif
                            </div>

                            <p class="text-sm text-gray-600 leading-relaxed pl-12">
                                {{ $review->comment }}
                            </p>
                        </div>

                        {{-- Foto Ulasan (jika ada) --}}
                        @if ($review->photo_url)
                            <a href="{{ $review->photo_url }}" target="_blank" class="shrink-0">
                                <img src="{{ $review->photo_url }}" alt="Foto ulasan {{ $review->user_name }}"
                                     class="w-20 h-20 object-cover rounded-xl border border-gray-100 hover:opacity-90 transition">
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="p-10 text-center rounded-2xl border border-dashed border-gray-200 text-sm text-gray-400">
                        Belum ada ulasan yang masuk. Pilih destinasi dan jadilah yang pertama mengulas!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Kolom Kanan: Sidebar Pink (Statistik & Komunitas Teraktif) --}}
        <aside class="lg:col-span-4 space-y-6">
            {{-- Ringkasan Rating Semua Wisata --}}
            <div class="bg-[#FFF5F7] rounded-2xl p-6 border border-rose-100/70">
                <h2 class="text-sm font-extrabold text-gray-900 mb-3">Rata-rata Wisata Semarang</h2>
                <div class="flex items-center gap-4">
                    <span class="text-4xl font-extrabold text-gray-900">{{ number_format($avgRating, 1) }}</span>
                    <div>
                        <p class="text-amber-500 text-sm font-bold">
                            {{ str_repeat('★', round($avgRating)) }}{{ str_repeat('☆', 5 - round($avgRating)) }}
                        </p>
                        <p class="text-xs text-gray-500">Dari total {{ $totalReviews }} ulasan</p>
                    </div>
                </div>
            </div>

            {{-- Komunitas Teraktif --}}
            <div class="bg-[#FFF5F7] rounded-2xl p-6 border border-rose-100/70">
                <h2 class="font-bold text-gray-900 text-sm flex items-center gap-2 mb-5">
                    <span>🔥</span> Komunitas Teraktif
                </h2>

                <div class="space-y-4 mb-6">
                    @forelse ($topContributors as $contributor)
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-gray-400 w-3">{{ $loop->iteration }}</span>
                            <div class="w-9 h-9 rounded-full bg-rose-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($contributor->user_name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-rose-700 truncate">{{ $contributor->user_name }}</p>
                                <p class="text-xs text-gray-500">{{ $contributor->total_reviews }} review</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">Belum ada kontributor.</p>
                    @endforelse
                </div>

                <a href="{{ route('wisata.index') }}" class="block w-full text-center bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-3 rounded-xl shadow-sm transition">
                    Pilih Destinasi untuk Diulas
                </a>
            </div>
        </aside>
    </div>
@endsection
