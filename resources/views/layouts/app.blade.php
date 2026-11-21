<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dolan Semarang')</title>
    <style>
        body { font-family: sans-serif; max-width: 960px; margin: 2rem auto; padding: 0 1rem; color: #1f2937; }
        nav { display: flex; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb; }
        nav a { text-decoration: none; color: #2563eb; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #d1d5db; padding: 0.75rem; text-align: left; }
        th { background: #f3f4f6; }
        .success { padding: 0.75rem; background: #dcfce7; color: #166534; border-radius: 6px; margin-bottom: 1rem; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
        label { display: block; margin-top: 1rem; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 0.5rem; margin-top: 0.25rem; border: 1px solid #d1d5db; border-radius: 4px; box-sizing: border-box; }
        button { margin-top: 1.5rem; padding: 0.6rem 1.2rem; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('wisata.index') }}">Daftar Wisata</a>
        <a href="{{ route('wisata.create') }}">+ Tambah Wisata</a>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>