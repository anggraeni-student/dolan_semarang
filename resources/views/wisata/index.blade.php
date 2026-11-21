@extends('layouts.app')

@section('title', 'Daftar Wisata - Dolan Semarang')

@section('content')
    <h1>Dolan Semarang</h1>
    <p>Direktori tempat wisata lokal Semarang.</p>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($destinations->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Nama Wisata</th>
                    <th>Kategori</th>
                    <th>Estimasi Harga</th>
                    <th>Alamat</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($destinations as $item)
                    <tr>
                        <td>
                            <a href="{{ route('wisata.show', $item) }}">
                                <strong>{{ $item->name }}</strong>
                            </a>
                        </td>
                        <td>{{ $item->category }}</td>
                        <td>{{ $item->price_range }}</td>
                        <td>{{ $item->address ?? '-' }}</td>
                        <td>{{ $item->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Belum ada data tempat wisata. Silakan klik <strong>+ Tambah Wisata</strong> di atas.</p>
    @endif
@endsection