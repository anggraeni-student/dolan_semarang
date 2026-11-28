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
                <a href="{{ route('categories.show', 'kuliner') }}" class="{{ request()->routeIs('categories.show') ? 'active' : '' }}">Tempat</a>
                <a href="{{ route('reviews.index', 'kota-lama') }}" class="{{ request()->routeIs('reviews.*') && !request()->routeIs('reviews.create') ? 'active' : '' }}">Review</a>
                <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'active' : '' }}">Panduan</a>
                <a href="{{ route('community.index') }}" class="{{ request()->routeIs('community.*') ? 'active' : '' }}">Komunitas</a>
            </div>
            <div class="nav-search">
                <span class="material-icons" style="font-size:17px">search</span>
                Cari destinasi…
            </div>
            <div class="nav-user">
                @auth
                    <a href="{{ route('wishlist.index') }}" class="btn btn-ghost {{ request()->routeIs('wishlist.*') ? 'active' : '' }}" style="padding:8px 10px;display:inline-flex;align-items:center;gap:4px">
                        <span class="material-icons" style="font-size:17px;color:var(--primary)">favorite_border</span> Wishlist
                    </a>
                    <a href="{{ route('itineraries.index') }}" class="btn btn-ghost {{ request()->routeIs('itineraries.*') ? 'active' : '' }}" style="padding:8px 10px;display:inline-flex;align-items:center;gap:4px">
                        <span class="material-icons" style="font-size:17px;color:var(--primary)">event_note</span> Trip Planner
                    </a>
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

    <div class="cat-strip">
        <div class="container inner" style="padding:0">
            <div class="inner" style="padding:10px 0;overflow-x:auto">
                @foreach ($categories ?? \App\Models\Category::orderBy('position')->get() as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}"
                       class="cat-chip {{ request()->routeIs('categories.show') && request()->route('slug') === $cat->slug ? 'active' : '' }}">
                        <span class="material-icons" style="font-size:15px">{{ $cat->icon }}</span>{{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

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
                <a href="{{ route('reviews.create', 'kota-lama') }}">Tulis Review</a>
                <a href="{{ route('login') }}">Masuk / Daftar</a>
            </div>
        </div>
        <div class="footer-bottom">© 2026 Dolan Semarang — Dibuat untuk warga & pengunjung Semarang Raya.</div>
    </footer>
@endif

</body>
</html>