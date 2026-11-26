@extends('layouts.app')

@section('title', 'Daftar Akun - Dolan Semarang')

@section('content')
    <div class="max-w-md mx-auto bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm mt-6">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Buat Akun Baru</h1>
        <p class="text-sm text-gray-500 mb-6">Cukup isi nama, email, dan password untuk mulai berkontribusi.</p>

        @if ($errors->any())
            <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-gray-700 mb-1.5">Nama / Username</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                       placeholder="Contoh: Zizi"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="nama@email.com"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 mb-1.5">Password (min. 6 karakter)</label>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-1.5">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
            </div>

            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl shadow-sm transition">
                Daftar & Masuk
            </button>
        </form>

        <p class="text-center text-xs text-gray-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-rose-600 font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>
@endsection
