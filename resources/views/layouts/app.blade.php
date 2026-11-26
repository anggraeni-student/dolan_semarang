<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dolan Semarang')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FAFAFA] text-gray-800 antialiased min-h-screen flex flex-col">

    {{-- Navbar --}}
    <header class="bg-white border-b border-gray-100 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            {{-- Logo --}}
            <a href="{{ route('wisata.index') }}" class="flex items-center gap-2.5 shrink-0">
                <span class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center text-sm shadow-sm">
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
                <a href="{{ route('reviews.index') }}" class="{{ request()->routeIs('reviews.index') ? 'text-rose-600 font-semibold' : 'hover:text-rose-600' }} transition">
                    Review
                </a>
                @auth
                    <a href="{{ route('wisata.create') }}" class="{{ request()->routeIs('wisata.create') ? 'text-rose-600 font-semibold' : 'hover:text-rose-600' }} transition">
                        Tambah Wisata
                    </a>
                @else
                    <button type="button" onclick="openAuthModal('login')" class="hover:text-rose-600 transition">
                        Tambah Wisata
                    </button>
                @endauth
            </nav>

            {{-- Search Bar --}}
            <form action="{{ route('wisata.index') }}" method="GET" class="flex-1 max-w-xs hidden sm:block">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400 text-xs">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari destinasi Semarang..."
                           class="w-full bg-gray-100/80 border border-transparent focus:bg-white focus:border-rose-300 rounded-full pl-9 pr-4 py-1.5 text-xs focus:outline-none transition">
                </div>
            </form>

            {{-- Tombol Kanan (Auth + Tambah) --}}
            <div class="flex items-center gap-3 shrink-0">
                @auth
                    <span class="hidden lg:inline text-xs font-bold text-gray-700">
                        Halo, {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-gray-500 hover:text-rose-600 transition">
                            Keluar
                        </button>
                    </form>
                    <a href="{{ route('wisata.create') }}" title="Tambah Wisata"
                       class="w-8 h-8 rounded-full bg-rose-600 hover:bg-rose-700 text-white font-bold flex items-center justify-center shadow-sm transition">
                        +
                    </a>
                @else
                    <button type="button" onclick="openAuthModal('login')" class="text-xs font-bold text-gray-600 hover:text-rose-600 transition">
                        Masuk
                    </button>
                    <button type="button" onclick="openAuthModal('register')" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-full shadow-sm transition">
                        Daftar
                    </button>
                @endauth
            </div>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-100 bg-white py-6 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} Dolan Semarang — Direktori Wisata &amp; Ulasan Lokal.
    </footer>

    {{-- POP-UP MODAL LOGIN & DAFTAR --}}
    @guest
        <div id="auth-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
            <div class="bg-white w-full max-w-md rounded-2xl p-6 sm:p-7 shadow-xl border border-gray-100 relative animate-[fadeIn_0.15s_ease-out]">
                {{-- Tombol Tutup --}}
                <button type="button" onclick="closeAuthModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-700 flex items-center justify-center text-sm font-bold">
                    ✕
                </button>

                {{-- Tab Switcher Masuk / Daftar --}}
                <div class="flex gap-2 bg-rose-50/70 p-1 rounded-xl mb-5 mr-8">
                    <button type="button" id="tab-login" onclick="switchAuthTab('login')"
                            class="flex-1 py-2 text-xs font-bold rounded-lg bg-white text-rose-600 shadow-xs transition">
                        Masuk
                    </button>
                    <button type="button" id="tab-register" onclick="switchAuthTab('register')"
                            class="flex-1 py-2 text-xs font-bold rounded-lg text-gray-500 hover:text-gray-800 transition">
                        Daftar Akun Baru
                    </button>
                </div>

                @if ($errors->any())
                    <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-3.5 py-2.5 rounded-xl text-xs">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Form Login --}}
                <form id="form-modal-login" method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_auth_tab" value="login">
                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Masuk ke DolanSemarang</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Masuk dulu untuk tulis review, tambah wisata, atau diskusi.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                               class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                    </div>

                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl shadow-sm transition">
                        Masuk Sekarang
                    </button>
                </form>

                {{-- Form Register --}}
                <form id="form-modal-register" method="POST" action="{{ route('register.post') }}" class="hidden space-y-3.5">
                    @csrf
                    <input type="hidden" name="_auth_tab" value="register">
                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Buat Akun Kontributor</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Isi nama &amp; email untuk mulai berbagi info wisata Semarang.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama / Username</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Zizi"
                               class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                               class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Password (min. 6 karakter)</label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                               class="w-full border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
                    </div>

                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl shadow-sm transition">
                        Daftar &amp; Masuk
                    </button>
                </form>
            </div>
        </div>

        <script>
            const authModal = document.getElementById('auth-modal');
            const formLogin = document.getElementById('form-modal-login');
            const formRegister = document.getElementById('form-modal-register');
            const tabLogin = document.getElementById('tab-login');
            const tabRegister = document.getElementById('tab-register');

            function switchAuthTab(tab) {
                if (tab === 'register') {
                    formLogin.classList.add('hidden');
                    formRegister.classList.remove('hidden');
                    tabRegister.className = 'flex-1 py-2 text-xs font-bold rounded-lg bg-white text-rose-600 shadow-xs transition';
                    tabLogin.className = 'flex-1 py-2 text-xs font-bold rounded-lg text-gray-500 hover:text-gray-800 transition';
                } else {
                    formRegister.classList.add('hidden');
                    formLogin.classList.remove('hidden');
                    tabLogin.className = 'flex-1 py-2 text-xs font-bold rounded-lg bg-white text-rose-600 shadow-xs transition';
                    tabRegister.className = 'flex-1 py-2 text-xs font-bold rounded-lg text-gray-500 hover:text-gray-800 transition';
                }
            }

            function openAuthModal(tab = 'login') {
                switchAuthTab(tab);
                authModal.classList.remove('hidden');
            }

            function closeAuthModal() {
                authModal.classList.add('hidden');
            }

            // Tutup modal kalau klik area gelap di luar kotak
            authModal.addEventListener('click', (e) => {
                if (e.target === authModal) closeAuthModal();
            });

            // Buka otomatis kalau ada error validasi login/register
            @if ($errors->any())
                openAuthModal(@json(old('_auth_tab', 'login')));
            @endif
        </script>
    @endguest

</body>
</html>