<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dolan Semarang') — Dolan Semarang</title>
    <meta name="description" content="@yield('meta-desc', 'Review & rekomendasi tempat asyik dikunjungi di Semarang dan sekitarnya.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>

@hasSection('bare') {{-- halaman tanpa nav (auth/onboarding) --}}
    @yield('bare')
@else
    <nav class="site-nav">
        <div class="container inner">
            <a href="{{ route('home') }}" class="logo" title="Ke halaman utama">
                <span class="logo-mark"><span class="material-icons" style="font-size:17px">explore</span></span>
                Dolan Semarang
            </a>
            <div class="nav-links">
                <a href="{{ route('destinations.index') }}" class="{{ request()->routeIs('destinations.index', 'destinations.show', 'categories.show', 'destinations.create') ? 'active' : '' }}">Tempat</a>
                <a href="{{ route('reviews.all') }}" class="{{ request()->routeIs('reviews.*') ? 'active' : '' }}">Review</a>
                <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'active' : '' }}">Panduan</a>
                <a href="{{ route('community.index') }}" class="{{ request()->routeIs('community.*') ? 'active' : '' }}">Komunitas</a>
            </div>
            <form class="nav-search" method="GET" action="{{ route('search') }}" role="search">
                <span class="material-icons" style="font-size:17px">search</span>
                <input type="search" name="q" value="{{ request()->query('q') }}" placeholder="Cari destinasi…" aria-label="Cari destinasi" autocomplete="off" style="border:none;background:none;outline:none;flex:1;min-width:0;font:inherit;color:var(--ink);font-size:13px">
            </form>
            <div class="nav-user">
                @auth
                    <a href="{{ route('profiles.show', auth()->user()->username) }}" class="avatar" style="width:36px;height:36px;font-size:13px" title="{{ auth()->user()->name }}">
                        {{ auth()->user()->avatar_initials }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost" style="padding:8px 10px">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-nav">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Strip kategori: HANYA di halaman yang memang tentang destinasi
         (index semua destinasi, kategori, detail, form tambah). Halaman
         review/artikel/komunitas/peta/profil tidak lagi menampilkan
         filter kategori yang tidak relevan. --}}
    @php
        $showCatStrip = request()->routeIs('destinations.index', 'categories.show', 'destinations.show', 'destinations.create');
    @endphp
    @if ($showCatStrip)
        <div class="cat-strip">
            <div class="container inner" style="padding:0">
                <div class="inner" style="padding:10px 0;overflow-x:auto">
                    @foreach ($categories ?? \App\Models\Category::orderBy('position')->get() as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}"
                           class="cat-chip {{ request()->routeIs('categories.show') && request()->route('slug') === $cat->slug ? 'active' : '' }}">
                            <span class="material-icons" style="font-size:15px">{{ $cat->icon }}</span>{{ $cat->name }}
                        </a>
                    @endforeach
                    @auth
                        <a href="{{ route('destinations.create') }}" class="cat-chip" style="border-style:dashed;font-weight:800">
                            <span class="material-icons" style="font-size:15px">add_location_alt</span>Tambah Tempat
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    @endif

    <main class="container page-body">
        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container inner" style="padding-left:0;padding-right:0">
            <div style="max-width:280px">
                <div class="logo" style="color:#fff;margin-bottom:10px">
                    <span class="logo-mark"><span class="material-icons" style="font-size:17px">explore</span></span>
                    Dolan Semarang
                </div>
                <p style="color:#C9CFD6;font-size:13.5px">Review tempat sejujur teman sendiri — dari kuliner legendaris sampai sudut foto estetik, semua di Semarang dan sekitarnya.</p>
            </div>
            <div>
                <h4>Dolan Semarang</h4>
                <a href="{{ route('home') }}">Homepage</a>
                <a href="{{ route('destinations.index') }}">Semua Destinasi</a>
                <a href="{{ route('map.index') }}">Peta Destinasi</a>
                <a href="{{ route('articles.index') }}">Panduan Perjalanan</a>
                <a href="{{ route('community.index') }}">Komunitas</a>
            </div>
            <div>
                <h4>Kategori</h4>
                @foreach (\App\Models\Category::orderBy('position')->limit(5)->get() as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a>
                @endforeach
            </div>
            <div>
                <h4>Komunitas</h4>
                <a href="{{ route('profiles.show', 'dina-rahma') }}">Reviewer Teratas</a>
                <a href="{{ route('reviews.all') }}">Jelajah Review</a>
                <a href="{{ route('destinations.create') }}">+ Tambah Tempat</a>
                <a href="{{ route('login') }}">Masuk / Daftar</a>
            </div>
        </div>
        <div class="footer-bottom">© 2026 Dolan Semarang — Dibuat untuk warga & pengunjung Semarang Raya.</div>
    </footer>
@endif

</body>
</html>
