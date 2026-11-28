@extends('layouts.app')
@section('title', $itinerary->title . ' — Trip Planner')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('itineraries.index') }}">Trip Planner</a> /
        {{ $itinerary->title }}
    </div>

    <div class="surface" style="margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px">
        <div>
            <h1 style="font-size:1.75rem;margin-bottom:4px">{{ $itinerary->title }}</h1>
            <div class="card-meta" style="display:flex;gap:16px;flex-wrap:wrap">
                <span>📅 {{ $itinerary->trip_date ? $itinerary->trip_date->format('d M Y') : 'Tanggal fleksibel' }}</span>
                <span>📍 {{ $itinerary->items->count() }} Destinasi</span>
            </div>
            @if ($itinerary->description)
                <p style="color:var(--ink-mid);font-size:14px;margin-top:8px">{{ $itinerary->description }}</p>
            @endif
        </div>
        <div style="background:#fff;padding:14px 18px;border-radius:14px;border:1px solid var(--line);text-align:right">
            <div style="font-size:12px;color:var(--ink-soft);font-weight:700">TOTAL ESTIMASI BIAYA</div>
            <div style="font-size:1.45rem;font-weight:800;color:var(--primary)">
                Rp {{ number_format($itinerary->total_cost, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1.6fr;gap:24px" class="trip-detail-grid">
        {{-- Form Tambah Destinasi ke Trip --}}
        <div class="card" style="padding:18px;align-self:start">
            <h3 style="margin-bottom:12px">+ Tambah Destinasi ke Jadwal</h3>
            <form method="POST" action="{{ route('itineraries.items.store', $itinerary) }}" style="display:flex;flex-direction:column;gap:12px">
                @csrf
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;margin-bottom:4px">Pilih Destinasi *</label>
                    <select name="destination_id" required style="width:100%;padding:10px;border:1px solid var(--line);border-radius:10px;font-family:inherit;background:#fff">
                        <option value="">-- Pilih Tempat Wisata --</option>
                        @if ($wishlistedDestinations->isNotEmpty())
                            <optgroup label="❤️ Dari Wishlist Saya">
                                @foreach ($wishlistedDestinations as $dest)
                                    <option value="{{ $dest->id }}">{{ $dest->name }} (HTM: Rp {{ number_format($dest->htm, 0, ',', '.') }})</option>
                                @endforeach
                            </optgroup>
                        @endif
                        <optgroup label="🗺️ Semua Destinasi Semarang">
                            @foreach ($allDestinations as $dest)
                                <option value="{{ $dest->id }}">{{ $dest->name }} (HTM: Rp {{ number_format($dest->htm, 0, ',', '.') }})</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;margin-bottom:4px">Jam Kunjungan</label>
                    <input type="time" name="visit_time"
                           style="width:100%;padding:10px;border:1px solid var(--line);border-radius:10px;font-family:inherit">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;margin-bottom:4px">Estimasi Biaya Tambahan (Rp)</label>
                    <input type="number" name="estimated_cost" min="0" step="1000" placeholder="0 (Makan / Parkir)"
                           style="width:100%;padding:10px;border:1px solid var(--line);border-radius:10px;font-family:inherit">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;margin-bottom:4px">Catatan Kunjungan</label>
                    <input type="text" name="notes" placeholder="Misal: Cobain es krim legendaris"
                           style="width:100%;padding:10px;border:1px solid var(--line);border-radius:10px;font-family:inherit">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">Masukkan ke Rundown</button>
            </form>
        </div>

        {{-- Timeline Rundown Perjalanan --}}
        <div>
            <h3 style="margin-bottom:12px">Rundown Perjalanan</h3>
            @forelse ($itinerary->items as $item)
                <div class="card" style="padding:16px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:flex-start;gap:12px">
                    <div style="display:flex;gap:14px;align-items:flex-start">
                        <div style="background:var(--primary);color:#fff;font-weight:800;font-size:13px;padding:6px 10px;border-radius:8px;min-width:64px;text-align:center">
                            {{ $item->visit_time ? \Carbon\Carbon::parse($item->visit_time)->format('H:i') : 'Bebas' }}
                        </div>
                        <div>
                            <a href="{{ route('destinations.show', $item->destination->slug) }}" style="font-weight:800;font-size:16px">
                                {{ $item->destination->name }}
                            </a>
                            <div class="card-meta" style="font-size:13px;margin-top:2px">
                                📍 {{ $item->destination->location }} •
                                HTM: <strong>{{ $item->destination->htm > 0 ? 'Rp ' . number_format($item->destination->htm, 0, ',', '.') : 'Gratis' }}</strong>
                                @if ($item->estimated_cost > 0)
                                    + Biaya lain: <strong>Rp {{ number_format($item->estimated_cost, 0, ',', '.') }}</strong>
                                @endif
                            </div>
                            @if ($item->notes)
                                <div style="margin-top:6px;font-size:13px;background:var(--surface);padding:6px 10px;border-radius:8px;color:var(--ink-mid)">
                                    📝 {{ $item->notes }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('itineraries.items.destroy', [$itinerary, $item]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost" style="color:#dc2626;padding:6px" title="Hapus dari rundown">
                            <span class="material-icons" style="font-size:18px">close</span>
                        </button>
                    </form>
                </div>
            @empty
                <div class="empty-state">
                    <strong>Rundown masih kosong.</strong>
                    <p style="margin-top:6px">Pilih destinasi di sebelah kiri (bisa langsung ambil dari Wishlist kamu!) untuk menyusun jadwal.</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        @media (max-width: 850px) {
            .trip-detail-grid { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection