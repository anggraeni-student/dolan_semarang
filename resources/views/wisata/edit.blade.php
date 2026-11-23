@extends('layouts.app')

@section('title', 'Edit ' . $destination->name . ' - Dolan Semarang')

@section('content')
    <nav class="text-xs text-gray-400 mb-5 flex items-center gap-2">
        <a href="{{ route('wisata.index') }}" class="hover:text-rose-600">Home</a>
        <span>/</span>
        <a href="{{ route('wisata.show', $destination) }}" class="hover:text-rose-600">{{ $destination->name }}</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">Edit</span>
    </nav>

    <div class="max-w-2xl bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Edit Destinasi Wisata</h1>
        <p class="text-sm text-gray-500 mb-6">Perbarui informasi untuk <strong>{{ $destination->name }}</strong>.</p>

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('wisata.update', $destination) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold text-gray-700 mb-1.5">Nama Tempat Wisata</label>
                <input type="text" id="name" name="name" value="{{ old('name', $destination->name) }}" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-xs font-bold text-gray-700 mb-1.5">Kategori</label>
                    <select id="category" name="category" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                        @foreach (['Sejarah', 'Alam', 'Kuliner', 'Religi', 'Edukasi'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $destination->category) == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="price_range" class="block text-xs font-bold text-gray-700 mb-1.5">Estimasi Harga Tiket</label>
                    <input type="text" id="price_range" name="price_range" value="{{ old('price_range', $destination->price_range) }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-gray-700 mb-1.5">Alamat (Opsional)</label>
                <input type="text" id="address" name="address" value="{{ old('address', $destination->address) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
            </div>

            <div>
                <label for="image_url" class="block text-xs font-bold text-gray-700 mb-1.5">Link Foto Wisata (Opsional)</label>
                <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $destination->image_url) }}" placeholder="https://contoh.com/foto-wisata.jpg"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-gray-700 mb-1.5">Deskripsi Singkat</label>
                <textarea id="description" name="description" rows="4" required
                          class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">{{ old('description', $destination->description) }}</textarea>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('wisata.show', $destination) }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection