@extends('layouts.app')
@section('title', 'Masuk')

@section('bare')
<div class="onboarding-wrap">
    <div class="card" style="width:100%;max-width:420px;padding:32px;border-radius:20px">
        <div style="text-align:center;margin-bottom:20px">
            <a href="{{ route('home') }}" class="logo" style="justify-content:center">
                <span class="logo-mark"><span class="material-icons" style="font-size:17px">explore</span></span>
                Dolan Semarang
            </a>
            <p style="color:var(--ink-soft);font-size:13.5px;margin:8px 0 0">Selamat datang kembali, pengunjung!</p>
        </div>

        @if ($errors->any())
            <div class="flash" style="background:#FEF7F9;border-color:var(--pink-line);color:var(--primary-deep)">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <label class="label" for="email">Email</label>
            <input id="email" type="email" name="email" class="input" value="{{ old('email') }}" required autofocus>

            <label class="label" for="password">Password</label>
            <input id="password" type="password" name="password" class="input" required>

            <label style="display:flex;align-items:center;gap:8px;margin:14px 0;font-size:13.5px;color:var(--ink-mid)">
                <input type="checkbox" name="remember" value="1" style="accent-color:var(--primary)"> Ingat saya
            </label>

            <button type="submit" class="btn btn-primary" style="width:100%">Masuk</button>
        </form>

        <p style="text-align:center;font-size:13.5px;margin:18px 0 0;color:var(--ink-mid)">
            Belum punya akun? <a href="{{ route('register') }}" style="font-weight:700">Daftar</a>
        </p>
        <p style="text-align:center;font-size:12px;color:var(--ink-soft);margin:10px 0 0">
            Demo: dina@dolan-smg.id / password
        </p>
    </div>
</div>
@endsection
