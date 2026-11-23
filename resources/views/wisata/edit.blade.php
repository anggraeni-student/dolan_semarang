@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h1>Edit Destinasi Wisata</h1>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 16px;">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('wisata.update', $destination) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 14px;">
            <label for="name" style="display: block; font-weight: bold;">Nama Tempat Wisata</label>
            <input type="text" id="name" name="name" value="{{ old('name', $destination->name) }}" required style="width: 100%; padding: 8px; margin-top: 4px;">
        </div>

        <div style="margin-bottom: 14px;">
            <label for="category" style="display: block; font-weight: bold;">Kategori</label>
            <select id="category" name="category" required style="width: 100%; padding: 8px; margin-top: 4px;">
                @foreach (['Sejarah', 'Alam', 'Kuliner', 'Religi', 'Edukasi'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $destination->category) == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 14px;">
            <label for="price_range" style="display: block; font-weight: bold;">Estimasi Harga Tiket</label>
            <input type="text" id="price_range" name="price_range" value="{{ old('price_range', $destination->price_range) }}" required style="width: 100%; padding: 8px; margin-top: 4px;">
        </div>

        <div style="margin-bottom: 14px;">
            <label for="address" style="display: block; font-weight: bold;">Alamat (Opsional)</label>
            <input type="text" id="address" name="address" value="{{ old('address', $destination->address) }}" style="width: 100%; padding: 8px; margin-top: 4px;">
        </div>

        <div style="margin-bottom: 16px;">
            <label for="description" style="display: block; font-weight: bold;">Deskripsi Singkat</label>
            <textarea id="description" name="description" rows="4" required style="width: 100%; padding: 8px; margin-top: 4px;">{{ old('description', $destination->description) }}</textarea>
        </div>

        <button type="submit" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer;">
            Simpan Perubahan
        </button>
        <a href="{{ route('wisata.show', $destination) }}" style="margin-left: 10px; color: #555;">Batal</a>
    </form>
</div>
@endsection