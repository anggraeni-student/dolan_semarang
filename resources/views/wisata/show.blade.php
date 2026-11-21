@extends('layouts.app')

@section('title', $destination->name . ' - Dolan Semarang')

@section('content')
    <p><a href="{{ route('wisata.index') }}">&larr; Kembali ke Daftar Wisata</a></p>

    <h1>{{ $destination->name }}</h1>
    <p>
        <strong>Kategori:</strong> {{ $destination->category }} |
        <strong>Estimasi Harga:</strong> {{ $destination->price_range }} |
        <strong>Rating:</strong> ⭐ {{ number_format($destination->reviews->avg('rating') ?? 0, 1) }} ({{ $destination->reviews->count() }} ulasan)
    </p>
    <p><strong>Alamat:</strong> {{ $destination->address ?? '-' }}</p>
    <p>{{ $destination->description }}</p>

    <hr style="margin: 2rem 0;">

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <h2>Tulis Ulasan Kamu</h2>
    <form method="POST" action="{{ route('wisata.review.store', $destination) }}">
        @csrf

        <label for="reviewer_name">Nama Kamu</label>
        <input id="reviewer_name" name="reviewer_name" value="{{ old('reviewer_name') }}" placeholder="Contoh: Zizi">
        @error('reviewer_name') <div class="error">{{ $message }}</div> @enderror

        <label for="rating">Rating (1 - 5)</label>
        <select id="rating" name="rating">
            <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
            <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
            <option value="3">⭐⭐⭐ (3 - Cukup)</option>
            <option value="2">⭐⭐ (2 - Kurang)</option>
            <option value="1">⭐ (1 - Buruk)</option>
        </select>
        @error('rating') <div class="error">{{ $message }}</div> @enderror

        <label for="comment">Komentar</label>
        <textarea id="comment" name="comment" rows="3" placeholder="Bagaimana pengalamanmu di sini?">{{ old('comment') }}</textarea>
        @error('comment') <div class="error">{{ $message }}</div> @enderror

        <button type="submit">Kirim Ulasan</button>
    </form>

    <hr style="margin: 2rem 0;">

    <h2>Ulasan Pengunjung ({{ $destination->reviews->count() }})</h2>
    @forelse ($destination->reviews as $review)
        <div style="border: 1px solid #e5e7eb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <strong>{{ $review->reviewer_name }}</strong> — ⭐ {{ $review->rating }}/5
            <p style="margin-top: 0.5rem;">{{ $review->comment }}</p>
        </div>
    @empty
        <p>Belum ada ulasan untuk tempat ini. Jadilah yang pertama mengulas!</p>
    @endforelse
@endsection