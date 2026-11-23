@extends('layouts.app')

@section('title', $destination->name . ' - Dolan Semarang')

@section('content')
    <p><a href="{{ route('wisata.index') }}">&larr; Kembali ke Daftar Wisata</a></p>

    @if (session('success'))
        <div class="success" style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 1.25rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="margin: 0 0 0.5rem;">{{ $destination->name }}</h1>
            <p style="margin: 0; color: #374151;">
                <strong>Kategori:</strong> {{ $destination->category }} |
                <strong>Estimasi Harga:</strong> {{ $destination->price_range }} |
                <strong>Rating:</strong> ⭐ {{ number_format($destination->reviews->avg('rating') ?? 0, 1) }} ({{ $destination->reviews->count() }} ulasan)
            </p>
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('wisata.edit', $destination) }}" style="padding: 8px 14px; background: #f59e0b; color: white; text-decoration: none; border-radius: 6px; font-size: 0.9rem;">
                Edit
            </a>
            <form method="POST" action="{{ route('wisata.destroy', $destination) }}" onsubmit="return confirm('Yakin ingin menghapus {{ $destination->name }}?');" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding: 8px 14px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9rem;">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <p style="margin-top: 1rem;"><strong>Alamat:</strong> {{ $destination->address ?? '-' }}</p>
    <p style="line-height: 1.6;">{{ $destination->description }}</p>

    <hr style="margin: 2rem 0;">

    <h2>Tulis Ulasan Kamu</h2>
    <form method="POST" action="{{ route('wisata.review.store', $destination) }}">
        @csrf

        <label for="user_name">Nama Kamu</label>
        <input id="user_name" name="user_name" value="{{ old('user_name') }}" placeholder="Contoh: Zizi" required>
        @error('user_name') <div class="error">{{ $message }}</div> @enderror

        <label for="rating">Rating (1 - 5)</label>
        <select id="rating" name="rating" required>
            <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Bagus)</option>
            <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
            <option value="3">⭐⭐⭐ (3 - Cukup)</option>
            <option value="2">⭐⭐ (2 - Kurang)</option>
            <option value="1">⭐ (1 - Buruk)</option>
        </select>
        @error('rating') <div class="error">{{ $message }}</div> @enderror

        <label for="comment">Komentar</label>
        <textarea id="comment" name="comment" rows="3" placeholder="Bagaimana pengalamanmu di sini?" required>{{ old('comment') }}</textarea>
        @error('comment') <div class="error">{{ $message }}</div> @enderror

        <button type="submit">Kirim Ulasan</button>
    </form>

    <hr style="margin: 2rem 0;">

    <h2>Ulasan Pengunjung ({{ $destination->reviews->count() }})</h2>
    @forelse ($destination->reviews as $review)
        <div style="border: 1px solid #e5e7eb; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            <strong>{{ $review->user_name }}</strong> — ⭐ {{ $review->rating }}/5
            <p style="margin-top: 0.5rem;">{{ $review->comment }}</p>
        </div>
    @empty
        <p>Belum ada ulasan untuk tempat ini. Jadilah yang pertama mengulas!</p>
    @endforelse
@endsection