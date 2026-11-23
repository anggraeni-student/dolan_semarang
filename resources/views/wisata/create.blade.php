@extends('layouts.app')

@section('title', 'Tambah Wisata Baru - Dolan Semarang')

@section('content')
    <nav class="text-xs text-gray-400 mb-5 flex items-center gap-2">
        <a href="{{ route('wisata.index') }}" class="hover:text-rose-600">Home</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">Tambah Destinasi</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Form Utama (Kiri) --}}
        <div class="lg:col-span-8 bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm">
            <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Tambah Destinasi Wisata</h1>
            <p class="text-sm text-gray-500 mb-6">Bagikan rekomendasi tempat wisata seru di Semarang.</p>

            @if ($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('wisata.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-gray-700 mb-1.5">Nama Tempat Wisata</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Lawang Sewu" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block text-xs font-bold text-gray-700 mb-1.5">Kategori</label>
                        <select id="category" name="category" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                            @foreach (['Sejarah', 'Alam', 'Kuliner', 'Religi', 'Edukasi'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="price_range" class="block text-xs font-bold text-gray-700 mb-1.5">Estimasi Harga Tiket</label>
                        <input type="text" id="price_range" name="price_range" value="{{ old('price_range') }}" placeholder="Contoh: Rp10.000 - Rp20.000" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold text-gray-700 mb-1.5">Alamat (Opsional)</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Contoh: Jl. Pemuda No.160, Semarang Tengah"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                </div>

                <div>
                    <label for="image_url" class="block text-xs font-bold text-gray-700 mb-1.5">Link Foto Wisata (Opsional)</label>
                    <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://contoh.com/foto-wisata.jpg"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-gray-700 mb-1.5">Deskripsi Singkat</label>
                    <textarea id="description" name="description" rows="4" placeholder="Ceritakan daya tarik utama, jam buka, atau tips berkunjung..." required
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm px-6 py-2.5 rounded-xl shadow-sm transition">
                        Simpan Destinasi
                    </button>
                    <a href="{{ route('wisata.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Kotak Tips (Kanan - Pink Lembut) --}}
        <aside class="lg:col-span-4 bg-[#FFF5F7] rounded-2xl p-6 border border-rose-100/70">
            <h2 class="font-bold text-gray-900 text-sm flex items-center gap-2 mb-4">
                <span>💡</span> Tips Mengisi Data
            </h2>
            <ul class="space-y-3 text-xs text-gray-600 leading-relaxed">
                <li class="flex gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-rose-600 text-white font-bold flex items-center justify-center shrink-0 text-[10px]">1</span>
                    <span>Tulis estimasi harga tiket atau parkir agar pengunjung mudah menyiapkan budget.</span>
                </li>
                <li class="flex gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-rose-600 text-white font-bold flex items-center justify-center shrink-0 text-[10px]">2</span>
                    <span>Sertakan link foto (URL gambar) agar kartu wisata di halaman depan tampil lebih menarik.</span>
                </li>
                <li class="flex gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-rose-600 text-white font-bold flex items-center justify-center shrink-0 text-[10px]">3</span>
                    <span>Tambahkan info kecamatan seperti Tembalang atau Semarang Tengah di kolom alamat.</span>
                </li>
            </ul>
        </aside>
    </div>
@endsection