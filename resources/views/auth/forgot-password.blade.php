@extends('layouts.app')
@section('title', 'Lupa Password')

@section('bare')
<div class="onboarding-wrap">
    <div class="card" style="width:100%;max-width:440px;padding:32px;border-radius:20px">
        <div style="text-align:center;margin-bottom:18px">
            <span style="display:inline-flex;width:52px;height:52px;border-radius:50%;background:var(--pink-soft);align-items:center;justify-content:center;margin-bottom:10px">
                <span class="material-icons" style="font-size:28px;color:var(--primary)">lock_reset</span>
            </span>
            <h1 style="margin:0;font-size:22px">Lupa Password?</h1>
            <p style="color:var(--ink-mid);font-size:13.5px;margin:8px 0 0;line-height:1.6">
                Tenang, masalah kecil! Masukkan email akunmu dan kami akan kirim tautan untuk mengatur ulang password.
            </p>
        </div>

        @if (session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="flash" style="background:#FEF7F9;border-color:var(--pink-line);color:var(--primary-deep)">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label class="label" for="email" style="margin-top:0">Email Akun</label>
            <input id="email" type="email" name="email" class="input" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>

            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:16px">Kirim Tautan Reset</button>
        </form>

        <p style="text-align:center;font-size:13.5px;margin:18px 0 0;color:var(--ink-mid)">
            <a href="{{ route('login') }}" style="font-weight:700">← Kembali ke halaman masuk</a>
        </p>
    </div>
</div>
@endsection
