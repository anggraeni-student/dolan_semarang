@extends('layouts.app')

@section('title', 'Daftar Wisata - Dolan Semarang')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <h1 style="margin: 0;">Dolan Semarang</h1>
            <p style="margin: 0.25rem 0 0; color: #555;">Direktori tempat wisata lokal Semarang.</p>
        </div>
        <a href="{{ route('wisata.create') }}" style="padding: 10px 16px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">
            + Tambah Wisata
        </a>
    </div>

    @if (session('success'))
        <div class="success" style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 1.25rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Pencarian & Filter Kategori --}}
    <form method="GET" action="{{ route('wisata.index') }}" style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama wisata..."
            style="flex: 1; min-width: 200px; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;"
        >

        <select name="category" style="padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>

        <button type="submit" style="padding: 8px 16px; background: #111827; color: white; border: none; border-radius: 6px; cursor: pointer;">
            Cari
        </button>

        @if (request()->filled('search') || request()->filled('category'))
            <a href="{{ route('wisata.index') }}" style="padding: 8px 12px; color: #555; text-decoration: none; align-self: center;">
                Reset
            </a>
        @endif
    </form>

    {{-- Card Grid Wisata --}}
    @if ($destinations->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
            @foreach ($destinations as $item)
                <div style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <span style="background: #eff6ff; color: #1d4ed8; font-size: 0.8rem; font-weight: bold; padding: 4px 10px; border-radius: 999px;">
                                {{ $item->category }}
                            </span>
                            <span style="font-size: 0.85rem; color: #4b5563;">
                                ⭐ {{ number_format($item->reviews_avg_rating ?? 0, 1) }} ({{ $item->reviews_count }})
                            </span>
                        </div>

                        <h2 style="margin: 0.5rem 0; font-size: 1.2rem;">
                            <a href="{{ route('wisata.show', $item) }}" style="text-decoration: none; color: #111827;">
                                {{ $item->name }}
                            </a>
                        </h2>

                        <p style="margin: 0.25rem 0; font-size: 0.9rem; color: #059669; font-weight: bold;">
                            {{ $item->price_range }}
                        </p>

                        <p style="margin: 0.25rem 0 0.75rem; font-size: 0.85rem; color: #6b7280;">
                            📍 {{ $item->address ?? 'Alamat belum diisi' }}
                        </p>

                        <p style="margin: 0; font-size: 0.9rem; color: #374151; line-height: 1.5;">
                            {{ \Illuminate\Support\Str::limit($item->description, 110) }}
                        </p>
                    </div>

                    <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
                        <a href="{{ route('wisata.show', $item) }}" style="color: #2563eb; font-weight: bold; text-decoration: none; font-size: 0.9rem;">
                            Lihat Detail &rarr;
                        </a>
                        <a href="{{ route('wisata.edit', $item) }}" style="color: #6b7280; text-decoration: none; font-size: 0.85rem;">
                            Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p style="color: #6b7280; padding: 2rem; text-align: center; border: 1px dashed #d1d5db; border-radius: 8px;">
            Tidak ada tempat wisata yang ditemukan.
        </p>
    @endif
@endsection