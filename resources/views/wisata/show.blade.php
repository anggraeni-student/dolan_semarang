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

    <div id="live-alert" class="hidden mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium"></div>

    {{-- Hero Detail Destinasi --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start mb-10">
        <div class="md:col-span-5 h-64 rounded-2xl overflow-hidden bg-gradient-to-br from-rose-100 via-orange-50 to-amber-100 border border-gray-100 flex items-center justify-center">
            @if ($destination->image_url)
                <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" class="w-full h-full object-cover">
            @else
                <span class="text-6xl">🏞️</span>
            @endif
        </div>

        <div class="md:col-span-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <span class="inline-block bg-rose-50 text-rose-600 text-xs font-bold px-3 py-1 rounded-full mb-2">
                        {{ $destination->category }}
                    </span>
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $destination->name }}</h1>
                    <p class="text-sm text-gray-500 mt-1">📍 {{ $destination->address ?? 'Semarang, Jawa Tengah' }}</p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    @auth
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
                    @else
                        <button type="button" onclick="openAuthModal('login')" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            Edit
                        </button>
                        <button type="button" onclick="openAuthModal('login')" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition">
                            Hapus
                        </button>
                    @endauth
                </div>
            </div>

            <div class="flex items-center gap-3 mt-4">
                <span class="text-amber-500 text-2xl font-extrabold">★ <span id="hero-avg">{{ number_format($avgRating, 1) }}</span></span>
                <span class="text-sm text-rose-600 font-semibold underline"><span id="hero-count">{{ $totalReviews }}</span> review</span>
                <span class="text-gray-300">|</span>
                <span class="text-sm font-semibold text-emerald-600">{{ $destination->price_range }}</span>
            </div>

            <p class="mt-4 text-sm text-gray-600 leading-relaxed">{{ $destination->description }}</p>

            <div class="mt-6 flex items-center gap-4">
                @auth
                    <a href="#form-review" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition">
                        Tulis Review
                    </a>
                @else
                    <button type="button" onclick="openAuthModal('login')" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition">
                        Tulis Review
                    </button>
                @endauth
                <a href="{{ route('wisata.index') }}" class="text-sm font-semibold text-rose-600 hover:underline">
                    &larr; Lihat Destinasi Lain
                </a>
            </div>
        </div>
    </div>

    <hr class="border-gray-100 my-8">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Daftar Ulasan (Kiri) --}}
        <div class="lg:col-span-7">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-extrabold text-gray-900">
                    Review Pengunjung (<span id="list-count">{{ $totalReviews }}</span>)
                </h2>
                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Update
                </span>
            </div>

            <div id="reviews-list" class="space-y-4">
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
                            <p class="text-sm text-gray-600 leading-relaxed pl-12">{{ $review->comment }}</p>
                        </div>

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

        {{-- Kolom Kanan: Ringkasan Rating + Form --}}
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-[#FFF5F7] rounded-2xl p-6 border border-rose-100/70">
                <h2 class="text-sm font-extrabold text-gray-900 mb-4">Ringkasan Rating</h2>

                <div class="flex items-center gap-4 mb-5">
                    <span id="summary-avg" class="text-4xl font-extrabold text-gray-900">{{ number_format($avgRating, 1) }}</span>
                    <div>
                        <p id="summary-stars" class="text-amber-500 text-sm font-bold">
                            {{ str_repeat('★', round($avgRating)) }}{{ str_repeat('☆', 5 - round($avgRating)) }}
                        </p>
                        <p class="text-xs text-gray-500"><span id="summary-count">{{ $totalReviews }}</span> review</p>
                    </div>
                </div>

                <div id="rating-bars" class="space-y-2">
                    @for ($star = 5; $star >= 1; $star--)
                        @php
                            $count = $destination->reviews->where('rating', $star)->count();
                            $percent = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-2.5 text-xs">
                            <span class="w-7 font-semibold text-gray-600">{{ $star }}★</span>
                            <div class="flex-1 h-2 bg-rose-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-600 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="w-9 text-right text-gray-500">{{ $percent }}%</span>
                        </div>
                    @endfor
                </div>
            </div>

            <div id="form-review" class="bg-[#FFF5F7] rounded-2xl p-6 border border-rose-100/70">
                <h2 class="text-base font-extrabold text-gray-900 mb-3">Bagikan Pengalamanmu</h2>

                @auth
                    <p class="text-xs text-gray-500 mb-4">Mengulas sebagai <strong class="text-rose-600">{{ auth()->user()->name }}</strong></p>

                    <form id="review-form" method="POST" action="{{ route('wisata.review.store', $destination) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf

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
                        </div>

                        <div>
                            <label for="comment" class="block text-xs font-bold text-gray-700 mb-1">Ceritakan Pengalamanmu</label>
                            <textarea id="comment" name="comment" rows="3" placeholder="Pemandangan, rute, tips, atau harga tiket..." required
                                      class="w-full bg-white border border-rose-100 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30"></textarea>
                        </div>

                        <div>
                            <label for="photo" class="block text-xs font-bold text-gray-700 mb-1">Foto Pengalaman (Opsional, maks 2MB)</label>
                            <input type="file" id="photo" name="photo" accept="image/*"
                                   class="w-full bg-white border border-rose-100 rounded-xl px-3 py-1.5 text-xs file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100">
                        </div>

                        <button id="submit-btn" type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl shadow-sm transition">
                            Kirim Review
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                        Masuk atau buat akun dulu dengan email &amp; namamu untuk membagikan ulasan di tempat ini.
                    </p>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="openAuthModal('login')" class="flex-1 text-center bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl shadow-sm transition">
                            Masuk
                        </button>
                        <button type="button" onclick="openAuthModal('register')" class="flex-1 text-center bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 font-bold text-sm py-2.5 rounded-xl transition">
                            Daftar
                        </button>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <script>
        const jsonUrl = @json(route('wisata.reviews.json', $destination));
        const reviewForm = document.getElementById('review-form');
        const submitBtn = document.getElementById('submit-btn');
        const liveAlert = document.getElementById('live-alert');

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderRealtime(data) {
            document.getElementById('hero-avg').textContent = data.avg_rating;
            document.getElementById('hero-count').textContent = data.total_reviews;
            document.getElementById('list-count').textContent = data.total_reviews;
            document.getElementById('summary-avg').textContent = data.avg_rating;
            document.getElementById('summary-count').textContent = data.total_reviews;
            document.getElementById('summary-stars').textContent = '★'.repeat(data.avg_round) + '☆'.repeat(5 - data.avg_round);

            document.getElementById('rating-bars').innerHTML = data.breakdown.map(item => `
                <div class="flex items-center gap-2.5 text-xs">
                    <span class="w-7 font-semibold text-gray-600">${item.star}★</span>
                    <div class="flex-1 h-2 bg-rose-100 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-600 rounded-full transition-all duration-500" style="width: ${item.percent}%"></div>
                    </div>
                    <span class="w-9 text-right text-gray-500">${item.percent}%</span>
                </div>
            `).join('');

            const listEl = document.getElementById('reviews-list');
            if (data.reviews.length === 0) {
                listEl.innerHTML = `<div class="p-8 text-center rounded-2xl border border-dashed border-gray-200 text-sm text-gray-400">Belum ada ulasan. Jadilah yang pertama membagikan pengalamanmu di sini!</div>`;
                return;
            }

            listEl.innerHTML = data.reviews.map(r => `
                <div class="p-4 rounded-2xl border border-gray-100 bg-white shadow-sm flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 rounded-full bg-rose-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                ${escapeHtml(r.initials)}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">${escapeHtml(r.user_name)}</p>
                                <p class="text-xs text-amber-500 font-semibold">
                                    ${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}
                                    <span class="text-gray-400 font-normal ml-1">${escapeHtml(r.time_ago)}</span>
                                </p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed pl-12">${escapeHtml(r.comment)}</p>
                    </div>
                    ${r.photo_url ? `
                        <a href="${r.photo_url}" target="_blank" class="shrink-0">
                            <img src="${r.photo_url}" alt="Foto ulasan" class="w-20 h-20 object-cover rounded-xl border border-gray-100 hover:opacity-90 transition">
                        </a>
                    ` : ''}
                </div>
            `).join('');
        }

        if (reviewForm) {
            reviewForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                submitBtn.disabled = true;
                submitBtn.textContent = 'Mengirim...';

                try {
                    const res = await fetch(reviewForm.action, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: new FormData(reviewForm),
                    });

                    if (!res.ok) throw new Error('Gagal mengirim ulasan. Pastikan semua kolom terisi benar.');

                    const data = await res.json();
                    renderRealtime(data);
                    reviewForm.reset();

                    liveAlert.textContent = 'Terima kasih! Ulasan kamu langsung tampil secara real-time.';
                    liveAlert.classList.remove('hidden');
                    setTimeout(() => liveAlert.classList.add('hidden'), 4000);
                } catch (err) {
                    alert(err.message);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Kirim Review';
                }
            });
        }

        setInterval(async () => {
            try {
                const res = await fetch(jsonUrl, { headers: { 'Accept': 'application/json' } });
                if (res.ok) renderRealtime(await res.json());
            } catch (_) {}
        }, 5000);
    </script>
@endsection