@extends('layouts.app')
@section('title', 'Pilih Minat')

@section('bare')
    <div class="onboarding-wrap">
        <div class="card" style="width:100%;max-width:560px;padding:40px;border-radius:24px">

            {{-- Logo --}}
            <div style="text-align:center;margin-bottom:16px">
                <a href="{{ route('home') }}" class="logo" style="justify-content:center">
                    <span class="logo-mark"><span class="material-icons" style="font-size:17px">explore</span></span>
                    Dolan Semarang
                </a>
            </div>

            {{-- Header --}}
            <div style="display:flex;align-items:center;gap:12px;justify-content:center;margin-bottom:10px">
                <span style="width:44px;height:44px;border-radius:50%;background:var(--pink-soft);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0">
                    <span class="material-icons" style="font-size:26px;color:var(--primary)">waving_hand</span>
                </span>
                <h2 style="margin:0;font-size:28px">Halo, dolaner!</h2>
            </div>
            <p style="text-align:center;font-size:14px;color:var(--ink-mid);line-height:1.5;margin-bottom:18px">
                Pilih 3 hal yang paling kamu suka — homepage-mu akan menyesuaikan rekomendasi tempat di Semarang & sekitarnya.
            </p>

            <form method="POST" action="{{ route('onboarding.store') }}">
                @csrf

                <div class="interest-grid">
                    @foreach ($interests as $interest)
                        @php ($isSelected = in_array($interest->slug, ['kuliner', 'sejarah', 'nongkrong']))
                        <div class="interest-tile {{ $isSelected ? 'selected' : '' }}" data-id="{{ $interest->id }}">
                            <input type="checkbox" name="interests[]" value="{{ $interest->id }}" {{ $isSelected ? 'checked' : '' }} style="display:none">
                            <span class="material-icons">{{ $interest->icon }}</span>
                            <span>{{ $interest->name }}</span>
                        </div>
                    @endforeach
                </div>

                @error('interests')
                    <div class="field-error" style="text-align:center;margin-top:10px">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:20px">Selesai — Buka Homepage-ku →</button>
            </form>

            <p style="text-align:center;margin:12px 0 0">
                <a href="{{ route('home') }}" style="font-size:13px;font-weight:600;color:var(--ink-mid)">Lewati dulu</a>
            </p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.interest-tile').forEach(function (tile) {
            tile.addEventListener('click', function () {
                var checkbox = tile.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                tile.classList.toggle('selected', checkbox.checked);
            });
        });
    </script>
@endsection

{{-- Layout saat ini me-yield 'content' di cabang bare; cermin ini membuat
    halaman tetap tampil utuh apa pun implementasi layout (bare/content). --}}
@section('content')
    @yield('bare')
@endsection
