@extends('layouts.app')

@section('title', 'Tambah Wisata - Dolan Semarang')

@section('content')
    <h1>Tambah Destinasi Wisata</h1>

    <form method="POST" action="{{ route('wisata.store') }}">
        @csrf

        <label for="name">Nama Tempat Wisata</label>
        <input id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Lawang Sewu">
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="category">Kategori</label>
        <select id="category" name="category">
            <option value="">-- Pilih Kategori --</option>
            <option value="Sejarah" @selected(old('category') === 'Sejarah')>Sejarah</option>
            <option value="Alam" @selected(old('category') === 'Alam')>Alam</option>
            <option value="Kuliner" @selected(old('category') === 'Kuliner')>Kuliner</option>
            <option value="Keluarga" @selected(old('category') === 'Keluarga')>Keluarga</option>
        </select>
        @error('category')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="price_range">Estimasi Harga Tiket</label>
        <input id="price_range" name="price_range" value="{{ old('price_range') }}" placeholder="Contoh: Rp10.000 - Rp20.000">
        @error('price_range')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="address">Alamat (Opsional)</label>
        <input id="address" name="address" value="{{ old('address') }}" placeholder="Contoh: Jl. Pemuda, Semarang Tengah">
        @error('address')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="description">Deskripsi Singkat</label>
        <textarea id="description" name="description" rows="4" placeholder="Ceritakan singkat tentang tempat ini...">{{ old('description') }}</textarea>
        @error('description')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Simpan Wisata</button>
    </form>
@endsection