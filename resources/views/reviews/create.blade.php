@extends('layouts.app')
@section('title', 'Tulis Review — ' . $destination->name)

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('destinations.index') }}">Tempat</a> /
        <a href="{{ route('destinations.show', $destination->slug) }}">{{ $destination->name }}</a> /
        Tulis Review
    </div>

    @php
        $aspects = [
            ['field' => 'score_view', 'label' => 'Pemandangan', 'default' => 5],
            ['field' => 'score_access', 'label' => 'Akses', 'default' => 3],
            ['field' => 'score_cleanliness', 'label' => 'Kebersihan', 'default' => 4],
            ['field' => 'score_facility', 'label' => 'Fasilitas', 'default' => 4],
        ];
        $tips = [
            'Tulis detail praktis — jam kunjungan, harga tiket, dan rute yang kamu ambil.',
            'Sertakan foto asli hasil jepretanmu sendiri, bukan stok dari internet.',
            'Hindari copy-paste — review unik jauh lebih membantu traveler lain.',
        ];
    @endphp

    <div style="display:grid;grid-template-columns:70fr 30fr;gap:24px;margin-top:6px" class="review-form-cols">

        {{-- KIRI: form review --}}
        <div style="min-width:0">
            <h1 style="font-size:1.7rem">Bagikan pengalamanmu di {{ $destination->name }}</h1>

            <form method="POST" action="{{ route('reviews.store', $destination->slug) }}" class="card" style="padding:18px 20px;margin-top:12px">
                @csrf

                {{-- Verifikasi kunjungan --}}
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    <span class="badge-lime" style="background:#EDF7E8;color:#2F6B1F;border-radius:999px;padding:4px 12px">
                        <span class="material-icons" style="font-size:14px">verified</span>Kunjungan terverifikasi
                    </span>
                </div>

                {{-- Skor 4 aspek --}}
                <div style="margin-top:16px">
                    <div style="font-weight:800;font-size:14px;margin-bottom:10px">Skor 4 aspek</div>
                    <div style="display:flex;flex-direction:column;gap:10px">
                        @foreach ($aspects as $aspect)
                            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                                <span style="width:110px;font-size:13px;color:var(--ink-mid);font-weight:600">{{ $aspect['label'] }}</span>
                                <div class="star-input" data-field="{{ $aspect['field'] }}">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" data-value="{{ $i }}" class="{{ $i <= old($aspect['field'], $aspect['default']) ? 'on' : '' }}" aria-label="{{ $aspect['label'] }} {{ $i }} bintang">★</button>
                                    @endfor
                                </div>
                                <input type="hidden" name="{{ $aspect['field'] }}" value="{{ old($aspect['field'], $aspect['default']) }}">
                                @error($aspect['field'])
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Cerita --}}
                <div style="margin-top:16px">
                    <label class="label" for="body" style="margin-top:0">Ceritakan pengalamannya</label>
                    <textarea id="body" name="body" class="input" rows="5" placeholder="Pemandangan, rute, tips, biaya…">{{ old('body') }}</textarea>
                    @error('body')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Foto (demo) --}}
                <div style="margin-top:16px">
                    <div class="label" style="margin:0 0 7px">Foto perjalanan (maks 10)</div>
                    <div style="border:2px dashed var(--line);border-radius:12px;padding:28px;text-align:center;color:var(--ink-soft)">
                        <span class="material-icons" style="font-size:22px">add_photo_alternate</span>
                        <div style="font-size:13px;margin-top:4px">Tarik &amp; lepas foto di sini (demo: fitur upload menyusul)</div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div style="display:flex;align-items:center;gap:14px;margin-top:18px;flex-wrap:wrap">
                    <button type="submit" class="btn btn-primary">Kirim Review</button>
                    <a href="{{ route('destinations.show', $destination->slug) }}" class="btn btn-ghost">Batal</a>
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:11.5px;color:var(--ink-mid);margin-left:auto">
                        <span class="material-icons" style="font-size:15px;color:var(--primary)">celebration</span>
                        Setelah dikirim, review tampil di halaman destinasi (dan kamu dapat <b>50</b> poin)
                    </span>
                </div>
            </form>
        </div>

        {{-- KANAN: tips --}}
        <div>
            <div class="card" style="padding:18px 20px">
                <div style="display:flex;align-items:center;gap:8px">
                    <span class="material-icons" style="color:var(--primary);font-size:20px">lightbulb</span>
                    <span style="font-weight:800;font-size:16px">Tips review bermanfaat</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:14px;margin-top:14px">
                    @foreach ($tips as $i => $tip)
                        <div style="display:flex;gap:10px;align-items:flex-start">
                            <span style="width:22px;height:22px;border-radius:50%;background:var(--primary);color:#FFFFFF;font-size:11px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0">{{ $i + 1 }}</span>
                            <div style="font-size:12.5px;color:var(--ink-mid);line-height:1.5">{{ $tip }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .review-form-cols { grid-template-columns: 1fr !important; }
        }
    </style>

    <script>
        document.querySelectorAll('.star-input').forEach(function (input) {
            input.querySelectorAll('button').forEach(function (button) {
                button.addEventListener('click', function () {
                    var value = parseInt(button.dataset.value, 10);
                    input.parentElement.querySelector('input[type="hidden"]').value = value;
                    input.querySelectorAll('button').forEach(function (star) {
                        star.classList.toggle('on', parseInt(star.dataset.value, 10) <= value);
                    });
                });
            });
        });
    </script>
@endsection
