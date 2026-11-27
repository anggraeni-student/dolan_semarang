@extends('layouts.app')
@section('title', 'Daftar')

@section('bare')
<div class="onboarding-wrap">
    <div class="card" style="width:100%;max-width:420px;padding:32px;border-radius:20px">
        <div style="text-align:center;margin-bottom:20px">
            <a href="{{ route('home') }}" class="logo" style="justify-content:center">
                <span class="logo-mark"><span class="material-icons" style="font-size:17px">explore</span></span>
                Dolan Semarang
            </a>
            <p style="color:var(--ink-soft);font-size:13.5px;margin:8px 0 0">Gabung komunitas Dolan Semarang</p>
        </div>

        @if ($errors->any())
            <div class="flash" style="background:#FEF7F9;border-color:var(--pink-line);color:var(--primary-deep)">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <label class="label" for="name">Nama lengkap</label>
            <input id="name" type="text" name="name" class="input" value="{{ old('name') }}" required autofocus>

            <label class="label" for="username">Username</label>
            <input id="username" type="text" name="username" class="input" value="{{ old('username') }}" placeholder="nama-kamu" required>

            <label class="label" for="email">Email</label>
            <input id="email" type="email" name="email" class="input" value="{{ old('email') }}" required>

            <label class="label" for="password">Password (min. 8 karakter)</label>
            <input id="password" type="password" name="password" class="input" required>

            <label class="label" for="password_confirmation">Ulangi password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="input" required>

            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:18px">Daftar</button>
        </form>

        <p style="text-align:center;font-size:13.5px;margin:18px 0 0;color:var(--ink-mid)">
            Sudah punya akun? <a href="{{ route('login') }}" style="font-weight:700">Masuk</a>
        </p>
    </div>
</div>
@endsection
