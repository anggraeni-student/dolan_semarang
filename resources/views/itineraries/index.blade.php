@extends('layouts.app')
@section('title', 'Trip Planner — Rencana Perjalanan')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> / Trip Planner
    </div>

    <div class="section-title" style="margin-bottom:20px">
        <div>
            <h1 style="font-size:1.8rem">Trip Planner Semarang</h1>
            <p style="color:var(--ink-mid);font-size:14px">Susun jadwal liburanmu lengkap dengan jam kunjungan dan estimasi biaya tiket.</p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1.6fr;gap:24px" class="planner-grid">
        {{-- Form Buat Trip Baru --}}
        <div class="surface" style="align-self:start">
            <h3 style="margin-bottom:12px">Buat Rencana Trip Baru</h3>
            <form method="POST" action="{{ route('itineraries.store') }}" style="display:flex;flex-direction:column;gap:12px">
                @csrf
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;margin-bottom:4px">Nama Rencana Trip *</label>
                    <input type="text" name="title" required placeholder="Contoh: Kuliner Seharian di Kota Lama"
                           style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:10px;font-family:inherit">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;margin-bottom:4px">Tanggal Berangkat</label>
                    <input type="date" name="trip_date"
                           style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:10px;font-family:inherit">
                </div>
                <div>
                    <label style="display:block;font-size:13px;font-weight:700;margin-bottom:4px">Catatan Singkat</label>
                    <textarea name="description" rows="3" placeholder="Misal: Berangkat bareng teman kampus naik motor"
                              style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:10px;font-family:inherit"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%">+ Buat Jadwal Trip</button>
            </form>
        </div>

        {{-- Daftar Rencana Trip User --}}
        <div>
            @forelse ($itineraries as $trip)
                <div class="card" style="padding:18px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;gap:14px;flex-wrap:wrap">
                    <div>
                        <a href="{{ route('itineraries.show', $trip) }}" style="font-size:17px;font-weight:800;display:block;margin-bottom:4px">
                            {{ $trip->title }}
                        </a>
                        <div class="card-meta" style="display:flex;gap:14px;flex-wrap:wrap;font-size:13px">
                            <span>📅 {{ $trip->trip_date ? $trip->trip_date->format('d M Y') : 'Tanggal fleksibel' }}</span>
                            <span>📍 {{ $trip->items->count() }} destinasi</span>
                            <span style="color:var(--primary-deep);font-weight:700">
                                💰 Est. Rp {{ number_format($trip->total_cost, 0, ',', '.') }}
                            </span>
                        </div>
                        @if ($trip->description)
                            <p style="font-size:13.5px;color:var(--ink-mid);margin-top:6px">{{ $trip->description }}</p>
                        @endif
                    </div>
                    <div style="display:flex;gap:8px;align-items:center">
                        <a href="{{ route('itineraries.show', $trip) }}" class="btn btn-primary" style="padding:8px 14px">Atur Jadwal</a>
                        <form method="POST" action="{{ route('itineraries.destroy', $trip) }}" onsubmit="return confirm('Hapus rencana trip ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-ghost" style="color:#dc2626;padding:8px">
                                <span class="material-icons" style="font-size:18px">delete_outline</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <span class="material-icons" style="font-size:40px;color:var(--primary);display:block;margin-bottom:8px">event_note</span>
                    <strong>Belum ada rencana trip.</strong>
                    <p style="margin-top:6px">Mulai buat rencana liburan pertamamu lewat formulir di samping!</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        @media (max-width: 850px) {
            .planner-grid { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection