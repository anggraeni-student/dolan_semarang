@extends('layouts.app')
@section('title', 'Atur Ulang Password')

@section('bare')
<div class="onboarding-wrap">
    <div class="card" style="width:100%;max-width:440px;padding:32px;border-radius:20px">
        <div style="text-align:center;margin-bottom:18px">
            <span style="display:inline-flex;width:52px;height:52px;border-radius:50%;background:var(--pink-soft);align-items:center;justify-content:center;margin-bottom:10px">
                <span class="material-icons" style="font-size:28px;color:var(--primary)">vpn_key</span>
            </span>
            <h1 style="margin:0;font-size:22px">Atur Password Baru</h1>
            <p style="color:var(--ink-mid);font-size:13.5px;margin:8px 0 0;line-height:1.6">
                Buat password baru untuk akunmu.
            </p>
        </div>

        @if ($errors->any())
            <div class="flash" style="background:#FEF7F9;border-color:var(--pink-line);color:var(--primary-deep)">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            <label class="label" for="email_display" style="margin-top:0">Email</label>
            <input id="email_display" type="email" class="input" value="{{ old('email', $email) }}" disabled>

            <label class="label" for="password">Password Baru</label>
            <input id="password" type="password" name="password" class="input" minlength="8" required autofocus>

            <label class="label" for="password_confirmation">Ulangi Password Baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="input" minlength="8" required>

            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:16px">Simpan Password Baru</button>
        </form>

        <p style="text-align:center;font-size:13.5px;margin:18px 0 0;color:var(--ink-mid)">
            <a href="{{ route('login') }}" style="font-weight:700">← Kembali ke halaman masuk</a>
        </p>
    </div>
</div>
@endsection
