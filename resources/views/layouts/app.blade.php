<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dolan Semarang')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- Navbar Atas --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            {{-- Logo --}}
            <a href="{{ route('wisata.index') }}" class="flex items-center gap-2 shrink-0">
                <span class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                    📍
                </span>
                <span class="font-extrabold text-lg tracking-tight text-gray-900">
                    Dolan<span class="text-rose-600">Semarang</span>
                </span>
            </a>

            {{-- Menu Navigasi --}}
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="{{ route('wisata.index') }}" class="{{ request()->routeIs('wisata.index') || request()->routeIs('home') ? 'text-rose-600 font-semibold' : 'hover:text-rose-600' }} transition">
                    Destinasi
                </a>
                <a href="{{ route('wisata.create') }}" class="{{ request()->routeIs('wisata.create') ? 'text-rose-600 font-semibold' : 'hover:text-rose-600' }} transition">
                    Tambah Wisata
                </a>
            </nav>

            {{-- Kolom Pencarian --}}
            <form action="{{ route('wisata.index') }}" method="GET" class="flex-1 max-w-xs hidden sm:block">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari destinasi Semarang..."
                        class="w-full bg-gray-100 text-sm rounded-full pl-9 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:bg-white border border-transparent focus:border-rose-200 transition"
                    >
                    <span class="absolute left-3 top-2 text-gray-400 text-sm">🔍</span>
                </div>
            </form>

            {{-- Tombol Aksi Kanan --}}
            <a href="{{ route('wisata.create') }}" class="w-9 h-9 rounded-full bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center font-bold text-lg shadow-sm transition shrink-0" title="Tambah Wisata">
                +
            </a>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        @yield('content')
    </main>

    <footer class="border-t border-gray-100 mt-16 py-8 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} Dolan Semarang &mdash; Direktori Wisata & Ulasan Lokal.
    </footer>

</body>
</html>