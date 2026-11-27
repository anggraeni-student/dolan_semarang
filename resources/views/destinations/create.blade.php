@extends('layouts.app')
@section('title', 'Tambah Tempat Baru — Dolan Semarang')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('destinations.index') }}">Tempat</a> /
        Tambah Baru
    </div>

    <div style="display:grid;grid-template-columns:70fr 30fr;gap:24px;margin-top:6px" class="dest-form-cols">

        {{-- KIRI: form --}}
        <div style="min-width:0">
            <h1 style="font-size:1.7rem">Tambah Tempat Baru</h1>
            <p style="color:var(--ink-mid);font-size:14px;margin:6px 0 0">
                Bagikan tempat asyik yang belum terdaftar — kamu <b>tidak perlu admin</b> untuk ini.
                Tempatmu langsung tayang &amp; bisa direview komunitas.
            </p>

            <form method="POST" action="{{ route('destinations.store') }}" class="card" style="padding:18px 20px;margin-top:14px">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px" class="two-field">
                    <div>
                        <label class="label" for="name" style="margin-top:0">Nama Tempat *</label>
                        <input id="name" type="text" name="name" class="input" value="{{ old('name') }}" placeholder="mis. Warung Kopi Kita" required maxlength="120">
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="category_id" style="margin-top:0">Kategori *</label>
                        <select id="category_id" name="category_id" class="select" required>
                            <option value="" disabled @selected(!old('category_id'))>Pilih kategori…</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <label class="label" for="location">Lokasi *</label>
                <input id="location" type="text" name="location" class="input" value="{{ old('location') }}" placeholder="mis. Kota Semarang, Jawa Tengah" required maxlength="150">
                @error('location')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                <label class="label" for="description">Deskripsi *</label>
                <textarea id="description" name="description" rows="4" class="input" placeholder="Ceritakan apa yang spesial dari tempat ini (min. 20 karakter)…" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                <label class="label" for="hero_image">Foto Utama</label>
                <select id="hero_image" name="hero_image" class="select">
                    <option value="" @selected(!old('hero_image'))>Pilih dari galeri Dolan…</option>
                    @foreach ($imageFiles as $file)
                        <option value="{{ $file }}" @selected(old('hero_image') === $file)>{{ $file }}</option>
                    @endforeach
                </select>
                <div class="card-meta" style="margin-top:5px">Belum ada upload file (demo) — pilih dari galeri atau isi path sendiri di bawah.</div>

                <label class="label" for="custom_image">atau Path Gambar Sendiri</label>
                <input id="custom_image" type="text" name="custom_image" class="input" value="{{ old('custom_image') }}" placeholder="images/nama-file.jpg (harus sudah ada di public/images)">
                @error('custom_image')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px" class="two-field">
                    <div>
                        <label class="label" for="lat">Latitude (opsional)</label>
                        <input id="lat" type="number" step="0.0000001" name="lat" class="input" value="{{ old('lat') }}" placeholder="-6.966667">
                    </div>
                    <div>
                        <label class="label" for="lng">Longitude (opsional)</label>
                        <input id="lng" type="number" step="0.0000001" name="lng" class="input" value="{{ old('lng') }}" placeholder="110.416667">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px" class="two-field">
                    <div>
                        <label class="label" for="htm">HTM / Harga Tiket (Rp)</label>
                        <input id="htm" type="number" name="htm" class="input" value="{{ old('htm', 0) }}" min="0" placeholder="0 = gratis">
                    </div>
                    <div>
                        <label class="label" for="opening_hours">Jam Buka</label>
                        <input id="opening_hours" type="text" name="opening_hours" class="input" value="{{ old('opening_hours') }}" placeholder="08.00–17.00" maxlength="100">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px" class="two-field">
                    <div>
                        <label class="label" for="best_time">Waktu Terbaik Kunjungan</label>
                        <input id="best_time" type="text" name="best_time" class="input" value="{{ old('best_time') }}" placeholder="Pagi / Sore golden hour" maxlength="100">
                    </div>
                    <div>
                        <label class="label" for="access_info">Info Akses</label>
                        <input id="access_info" type="text" name="access_info" class="input" value="{{ old('access_info') }}" placeholder="15 menit dari Simpang Lima" maxlength="255">
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:14px;margin-top:18px;flex-wrap:wrap">
                    <button type="submit" class="btn btn-primary">Publikasikan Tempat</button>
                    <a href="{{ route('destinations.index') }}" class="btn btn-ghost">Batal</a>
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:11.5px;color:var(--ink-mid);margin-left:auto">
                        <span class="material-icons" style="font-size:15px;color:var(--primary)">celebration</span>
                        Tempat yang kamu tambah tercatat sebagai kontribusimu
                    </span>
                </div>
            </form>
        </div>

        {{-- KANAN: panduan --}}
        <div>
            <div class="card" style="padding:18px 20px">
                <div style="display:flex;align-items:center;gap:8px">
                    <span class="material-icons" style="color:var(--primary);font-size:20px">lightbulb</span>
                    <span style="font-weight:800;font-size:16px">Tips tempat yang layak</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:14px;margin-top:14px">
                    @php
                        $tips = [
                            'Tempatnya nyaman & aman dikunjungi publik — bukan area pribadi.',
                            'Alamatnya jelas dan bisa ditemukan lewat peta online.',
                            'Tulis deskripsi faktual: suasana, harga, dan jam operasional terakhir yang kamu tahu.',
                            'Pilih kategori yang paling pas supaya mudah ditemukan orang.',
                        ];
                    @endphp
                    @foreach ($tips as $i => $tip)
                        <div style="display:flex;gap:10px;align-items:flex-start">
                            <span style="width:22px;height:22px;border-radius:50%;background:var(--primary);color:#FFFFFF;font-size:11px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0">{{ $i + 1 }}</span>
                            <div style="font-size:12.5px;color:var(--ink-mid);line-height:1.5">{{ $tip }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .dest-form-cols { grid-template-columns: 1fr !important; }
            .two-field { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection
