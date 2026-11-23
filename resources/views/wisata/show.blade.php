@extends('layouts.app')

@section('title', $destination->name . ' - Dolan Semarang')

@section('content')
    @php
        $totalReviews = $destination->reviews->count();
        $avgRating = $totalReviews > 0 ? $destination->reviews->avg('rating') : 0;
    @endphp

    {{-- Breadcrumb --}}
    <nav class="text-xs text-gray-400 mb-5 flex items-center gap-2">
        <a href="{{ route('wisata.index') }}" class="hover:text-rose-600">Home</a>
        <span>/</span>
        <a href="{{ route('wisata.index', ['category' => $destination->category]) }}" class="hover:text-rose-600">{{ $destination->category }}</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">{{ $destination->name }}</span>
    </nav>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Hero Detail Destinasi --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start mb-10">
        {{-- Foto Destinasi --}}
        <div class="md:col-span-5 h-64 rounded-2xl overflow-hidden bg-gradient-to-br from-rose-100 via-orange-50 to-amber-100 border border-gray-100 flex items-center justify-center">
            @if ($destination->image_url)
                <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
            @else
                <span class="text-6xl">🏞️</span>
            @endif
        </div>

        {{-- Info Utama --}}
        <div class="md:col-span-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <span class="inline-block bg-rose-50 text-rose-600 text-xs font-bold px-3 py-1 rounded-full mb-2">
                        {{ $destination->category }}
                    </span>
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $destination->name }}</h1>
                    <p class="text-sm text-gray-500 mt-1">📍 {{ $destination->address ?? 'Semarang, Jawa Tengah' }}</p>
                </div>

                {{-- Tombol Edit & Hapus --}}
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('wisata.edit', $destination) }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('wisata.destroy', $destination) }}" onsubmit="return confirm('Yakin ingin menghapus {{ $destination->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            {{-- Rating Besar --}}
            <div class="flex items-center gap-3 mt-4">
                <span class="text-amber-500 text-2xl font-extrabold">★ {{ number_format($avgRating, 1) }}</span>
                <span class="text-sm text-rose-600 font-semibold underline">{{ $totalReviews }} review</span>
                <span class="text-gray-300">|</span>
                <span class="text-sm font-semibold text-emerald-600">{{ $destination->price_range }}</span>
            </div>

            <p class="mt-4 text-sm text-gray-600 leading-relaxed">
                {{ $destination->description }}
            </p>

            <div class="mt-6 flex items-center gap-4">
                <a href="#form-review" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition">
                    Tulis Review
                </a>
                <a href="{{ route('wisata.index') }}" class="text-sm font-semibold text-rose-600 hover:underline">
                    &larr; Lihat Destinasi Lain
                </a>
            </div>
        </div>
    </div>

    <hr class="border-gray-100 my-8">

    {{-- Bagian Ulasan (Kiri) & Ringkasan + Form (Kanan) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Daftar Ulasan (Kiri) --}}
        <div class="lg:col-span-7">
            <h2 class="text-lg font-extrabold text-gray-900 mb-5">
                Review Pengunjung ({{ $totalReviews }})
            </h2>

            <div class="space-y-4">
                @forelse ($destination->reviews as $review)
                    <div class="p-4 rounded-2xl border border-gray-100 bg-white shadow-sm flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-3 mb-2">
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
                    <div class="p-8 text-center rounded-2xl border border-dashed border-gray-200 text-sm text-gray-400">
                        Belum ada ulasan. Jadilah yang pertama membagikan pengalamanmu di sini!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Kolom Kanan: Ringkasan Rating + Form Tulis Review --}}
        <div class="lg:col-span-5 space-y-6">
            {{-- Kotak Ringkasan Rating (Persis Mockup) --}}
            <div class="bg-[#FFF5F7] rounded-2xl p-6 border border-rose-100/70">
                <h2 class="text-sm font-extrabold text-gray-900 mb-4">Ringkasan Rating</h2>

                <div class="flex items-center gap-4 mb-5">
                    <span class="text-4xl font-extrabold text-gray-900">{{ number_format($avgRating, 1) }}</span>
                    <div>
                        <p class="text-amber-500 text-sm font-bold">
                            {{ str_repeat('★', round($avgRating)) }}{{ str_repeat('☆', 5 - round($avgRating)) }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $totalReviews }} review</p>
                    </div>
                </div>

                <div class="space-y-2">
                    @for ($star = 5; $star >= 1; $star--)
                        @php
                            $count = $destination->reviews->where('rating', $star)->count();
                            $percent = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-2.5 text-xs">
                            <span class="w-7 font-semibold text-gray-600">{{ $star }}★</span>
                            <div class="flex-1 h-2 bg-rose-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-600 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="w-9 text-right text-gray-500">{{ $percent }}%</span>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Form Tulis Ulasan --}}
            <div id="form-review" class="bg-[#FFF5F7] rounded-2xl p-6 border border-rose-100/70">
                <h2 class="text-base font-extrabold text-gray-900 mb-4">Bagikan Pengalamanmu</h2>

                <form method="POST" action="{{ route('wisata.review.store', $destination) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label for="user_name" class="block text-xs font-bold text-gray-700 mb-1">Nama Kamu</label>
                        <input id="user_name" name="user_name" value="{{ old('user_name') }}" placeholder="Contoh: Zizi" required
                               class="w-full bg-white border border-rose-100 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30">
                        @error('user_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="rating" class="block text-xs font-bold text-gray-700 mb-1">Rating</label>
                        <select id="rating" name="rating" required
                                class="w-full bg-white border border-rose-100 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30">
                            <option value="5">★★★★★ (5 - Sangat Bagus)</option>
                            <option value="4">★★★★☆ (4 - Bagus)</option>
                            <option value="3">★★★☆☆ (3 - Cukup)</option>
                            <option value="2">★★☆☆☆ (2 - Kurang)</option>
                            <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                        </select>
                        @error('rating') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="comment" class="block text-xs font-bold text-gray-700 mb-1">Ceritakan Pengalamanmu</label>
                        <textarea id="comment" name="comment" rows="3" placeholder="Pemandangan, rute, tips, atau harga tiket..." required
                                  class="w-full bg-white border border-rose-100 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30">{{ old('comment') }}</textarea>
                        @error('comment') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="photo" class="block text-xs font-bold text-gray-700 mb-1">Foto Pengalaman (Opsional, maks 2MB)</label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                               class="w-full bg-white border border-rose-100 rounded-xl px-3 py-1.5 text-xs file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100">
                        @error('photo') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl shadow-sm transition">
                        Kirim Review
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection