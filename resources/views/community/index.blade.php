@extends('layouts.app')
@section('title', 'Komunitas Dolan Semarang')

@section('content')
    @php \Carbon\Carbon::setLocale('id'); @endphp

    {{-- FORM BUAT THREAD (collapsible, hanya untuk yang login) --}}
    @auth
        <div class="card" style="padding:0;margin-bottom:20px;overflow:visible">
            <button type="button" onclick="var f=document.getElementById('new-thread-form');f.style.display=f.style.display==='none'?'block':'none';this.querySelector('.toggle-icon').textContent=f.style.display==='none'?'expand_more':'expand_less'"
                    style="width:100%;display:flex;align-items:center;gap:10px;padding:16px 20px;background:none;border:none;cursor:pointer;font-family:var(--font-heading);font-weight:800;font-size:15.5px;color:var(--ink)">
                <span class="material-icons" style="font-size:20px;color:var(--primary)">add_circle</span>
                Buat Thread Baru
                <span class="material-icons toggle-icon" style="margin-left:auto">expand_more</span>
            </button>
            <div id="new-thread-form" style="display:none;padding:0 20px 20px">
                <form method="POST" action="{{ route('community.store') }}">
                    @csrf
                    <label class="label" for="type" style="margin-top:0">Jenis Thread</label>
                    <select id="type" name="type" class="select">
                        <option value="tanya" @selected(old('type') === 'tanya')>Tanya Destinasi</option>
                        <option value="cerita" @selected(old('type') === 'cerita')>Cerita Perjalanan</option>
                    </select>

                    <label class="label" for="title">Judul</label>
                    <input id="title" type="text" name="title" class="input" value="{{ old('title') }}" placeholder="Judul pertanyaanmu…">
                    @error('title')
                        <div class="field-error">{{ $message }}</div>
                    @enderror

                    <label class="label" for="body">Ceritakan sedikit detailnya</label>
                    <textarea id="body" name="body" rows="3" class="input" placeholder="Tambahkan konteks biar jawaban komunitas lebih tepat…">{{ old('body') }}</textarea>
                    @error('body')
                        <div class="field-error">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn btn-primary" style="margin-top:16px">Publikasikan Thread</button>
                </form>
            </div>
        </div>
    @endauth

    {{-- DUA KOLOM: THREAD + SIDEBAR --}}
    <div style="display:flex;gap:24px;align-items:flex-start" class="community-cols">
        {{-- KIRI --}}
        <div style="flex:0 0 65%;min-width:0">
            <div class="crumb">
                <a href="{{ route('home') }}">Home</a> / Komunitas
            </div>
            <div style="margin-bottom:16px">
                <h1 style="margin-bottom:6px">Komunitas Dolan Semarang</h1>
                <p style="color:var(--ink-mid);margin:0">Tanya apa saja ke traveler yang lebih dulu ke sana.</p>
            </div>

            @if ($threads->isEmpty())
                <div class="empty-state">Belum ada thread. Jadilah yang pertama bertanya!</div>
            @endif

            @foreach ($threads as $thread)
                {{-- Kartu sekarang bisa DIKLIK menuju halaman thread --}}
                <a href="{{ route('community.show', $thread) }}" class="card card-link" style="display:block;padding:16px;margin-bottom:14px">
                    <div><span class="tag" style="font-size:10px;letter-spacing:.6px">{{ $thread->type === 'tanya' ? 'TANYA DESTINASI' : 'CERITA PERJALANAN' }}</span></div>
                    <h3 style="font-weight:800;font-size:17px;line-height:1.35;margin:7px 0 8px">{{ $thread->title }}</h3>
                    <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--ink-soft);flex-wrap:wrap">
                        <span class="avatar" style="width:28px;height:28px;font-size:11px">{{ $thread->user->avatar_initials }}</span>
                        <span style="font-weight:700;color:var(--ink-mid)">{{ $thread->user->name }}</span>
                        · {{ $thread->replies_count }} balasan · terakhir {{ $thread->last_activity_at->diffForHumans() }}
                        <span class="material-icons" style="font-size:15px;color:var(--primary);margin-left:auto" title="Buka thread">arrow_forward</span>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- KANAN: SIDEBAR --}}
        <div style="flex:1;min-width:0;display:flex;flex-direction:column;gap:16px">
            <div class="surface" style="padding:18px 20px">
                <div style="display:flex;align-items:center;gap:6px;font-weight:800;font-size:14px;margin-bottom:12px">
                    <span class="material-icons" style="font-size:17px;color:var(--primary)">local_fire_department</span>
                    Top Contributor Minggu Ini
                </div>
                @foreach ($topUsers as $i => $u)
                    <div style="display:flex;align-items:center;gap:10px;padding:10px 0;{{ $i < count($topUsers) - 1 ? 'border-bottom:1px solid var(--pink-line)' : '' }}">
                        <a href="{{ route('profiles.show', $u->username) }}" class="avatar" style="width:36px;height:36px;font-size:11px" title="Lihat profil {{ $u->name }}">
                            {{ $u->avatar_initials }}
                        </a>
                        <div style="min-width:0;flex:1">
                            <a href="{{ route('profiles.show', $u->username) }}" style="font-weight:700;color:var(--ink);font-size:13px">{{ $u->name }}</a>
                        </div>
                        <span style="font-size:13px;font-weight:700">{{ $u->reviews_count }} review</span>
                    </div>
                @endforeach
            </div>

            <div class="card" style="padding:18px 20px">
                <div style="display:flex;align-items:center;gap:6px;font-weight:800;font-size:14px;margin-bottom:12px">
                    <span class="material-icons" style="font-size:17px;color:var(--primary)">military_tech</span>
                    Papan Lencana
                </div>
                @php
                    $badges = [
                        ['name' => 'Pemula Dolan', 'desc' => 'review pertama', 'bg' => 'var(--pink-soft)', 'color' => 'var(--primary)'],
                        ['name' => 'Rutin Dolan', 'desc' => '5 review terverifikasi', 'bg' => 'var(--pink-soft)', 'color' => 'var(--primary)'],
                        ['name' => 'Legenda Dolan', 'desc' => '25+ review', 'bg' => 'var(--lime)', 'color' => '#1F2A12'],
                    ];
                @endphp
                @foreach ($badges as $i => $b)
                    <div style="display:flex;align-items:center;gap:10px;{{ $i < count($badges) - 1 ? 'padding:0 0 11px;margin-bottom:11px;border-bottom:1px solid var(--line)' : '' }}">
                        <span style="width:32px;height:32px;border-radius:8px;background:{{ $b['bg'] }};display:inline-flex;align-items:center;justify-content:center;flex-shrink:0">
                            <span class="material-icons" style="font-size:16px;color:{{ $b['color'] }}">workspace_premium</span>
                        </span>
                        <div style="min-width:0">
                            <div style="font-size:13px;font-weight:700">{{ $b['name'] }}</div>
                            <div style="font-size:11.5px;color:var(--ink-soft)">{{ $b['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @guest
        <div class="surface" style="margin-top:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
            <span class="material-icons" style="font-size:34px;color:var(--primary)">forum</span>
            <div style="flex:1;min-width:200px">
                <b>Masuk dulu untuk ikut diskusi.</b>
                <div style="font-size:13.5px;color:var(--ink-mid)">Buat thread, balas pertanyaan sesama traveler, dan bagikan ceritamu di sini.</div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-outline">Daftar</a>
            </div>
        </div>
    @endguest

    <style>
        @media (max-width: 900px) {
            .community-cols { flex-direction: column !important; }
            .community-cols > div:first-child { flex: 1 1 auto !important; width: 100%; }
        }
    </style>
@endsection
