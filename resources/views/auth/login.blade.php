@extends('layouts.app')

@section('title', 'Masuk - Dolan Semarang')

@section('content')
    <div class="max-w-md mx-auto bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm mt-6">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Masuk ke DolanSemarang</h1>
        <p class="text-sm text-gray-500 mb-6">Masuk untuk menulis review, menambah wisata, atau ikut diskusi.</p>

        @if ($errors->any())
            <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="nama@email.com"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 mb-1.5">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400">
            </div>

            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm py-2.5 rounded-xl shadow-sm transition">
                Masuk
            </button>
        </form>

        <p class="text-center text-xs text-gray-500 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-rose-600 font-bold hover:underline">Daftar sekarang</a>
        </p>
    </div>
@endsection