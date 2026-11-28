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

        <form action="{{ route('wisata.update', $destination) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
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
                <label for="image" class="block text-xs font-bold text-gray-700 mb-1.5">Ganti Foto Wisata (Opsional, maks 2MB)</label>
                @if ($destination->image_url)
                    <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" class="h-28 w-44 object-cover rounded-xl mb-2.5 border border-gray-200">
                @endif
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm bg-white file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100">
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