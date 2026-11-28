@extends('layouts.app')
@section('title', $thread->title . ' — Komunitas Dolan Semarang')

@section('content')
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('community.index') }}">Komunitas</a> /
        {{ \Illuminate\Support\Str::limit($thread->title, 40) }}
    </div>

    <div style="display:flex;gap:24px;align-items:flex-start" class="community-cols">

        {{-- KIRI: thread + balasan --}}
        <div style="flex:0 0 65%;min-width:0">

            {{-- Kartu thread --}}
            <div class="card" style="padding:18px 20px">
                <div style="display:flex;align-items:flex-start;gap:10px;flex-wrap:wrap">
                    <span class="tag" style="font-size:10px;letter-spacing:.6px">{{ $thread->type === 'tanya' ? 'TANYA DESTINASI' : 'CERITA PERJALANAN' }}</span>
                    @auth
                        <span style="margin-left:auto"><x-report-button :model="$thread" /></span>
                    @endauth
                </div>
                <h1 style="font-weight:800;font-size:22px;line-height:1.35;margin:10px 0 12px">{{ $thread->title }}</h1>
                <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--ink-soft);flex-wrap:wrap">
                    <a href="{{ route('profiles.show', $thread->user->username) }}" class="avatar" style="width:30px;height:30px;font-size:11px" title="Lihat profil {{ $thread->user->name }}">
                        {{ $thread->user->avatar_initials }}
                    </a>
                    <a href="{{ route('profiles.show', $thread->user->username) }}" style="font-weight:700;color:var(--ink-mid)">{{ $thread->user->name }}</a>
                    <x-admin-badge :user="$thread->user" />
                    · {{ $thread->created_at->translatedFormat('d M Y') }}
                    · {{ $thread->replies->count() ?? $thread->reply_count }} balasan
                </div>
                @if ($thread->body)
                    <p style="font-size:14.5px;color:var(--ink-mid);line-height:1.65;margin:14px 0 0;white-space:pre-line">{{ $thread->body }}</p>
                @endif
            </div>

            {{-- Balasan --}}
            <div class="section-title" style="margin-top:22px">
                <h2>{{ $replies->count() }} Balasan</h2>
            </div>

            @forelse ($replies as $reply)
                <div class="card" style="display:flex;gap:12px;padding:14px 16px;margin-bottom:12px">
                    <a href="{{ route('profiles.show', $reply->user->username) }}" class="avatar" style="width:34px;height:34px;font-size:12px;flex-shrink:0" title="Lihat profil {{ $reply->user->name }}">
                        {{ $reply->user->avatar_initials }}
                    </a>
                    <div style="min-width:0;flex:1">
                        <div style="display:flex;align-items:baseline;gap:8px;flex-wrap:wrap">
                            <a href="{{ route('profiles.show', $reply->user->username) }}" style="font-weight:700;font-size:13.5px;color:var(--ink)">{{ $reply->user->name }}</a>
                            <x-admin-badge :user="$reply->user" />
                            <span class="card-meta">{{ $reply->created_at->diffForHumans() }}</span>
                            @auth
                                <span style="margin-left:auto"><x-report-button :model="$reply" /></span>
                            @endauth
                        </div>
                        <p style="font-size:14px;color:var(--ink-mid);line-height:1.6;margin:6px 0 0;white-space:pre-line">{{ $reply->body }}</p>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding:28px 0">
                    <span class="material-icons" style="font-size:34px;color:var(--ink-soft)">chat_bubble_outline</span>
                    <p style="margin-top:6px">Belum ada balasan. Jadilah yang pertama membantu!</p>
                </div>
            @endforelse

            {{-- Form balas --}}
            @auth
                <form method="POST" action="{{ route('community.reply', $thread) }}" class="card" style="padding:16px 18px;margin-top:14px">
                    @csrf
                    <div style="display:flex;align-items:center;gap:8px;font-weight:800;font-size:14px;margin-bottom:10px">
                        <span class="material-icons" style="font-size:17px;color:var(--primary)">reply</span>
                        Tulis Balasan
                    </div>
                    <textarea name="body" rows="3" class="input" placeholder="Bagikan jawaban atau pengalamanmu…" required>{{ old('body') }}</textarea>
                    @error('body')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-primary" style="margin-top:12px">Kirim Balasan</button>
                </form>
            @else
                <div class="surface" style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-top:14px">
                    <span class="material-icons" style="font-size:26px;color:var(--primary)">lock</span>
                    <div style="flex:1;min-width:180px;font-size:13.5px;color:var(--ink-mid)"><b>Masuk dulu</b> untuk ikut menjawab thread ini.</div>
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                </div>
            @endauth
        </div>

        {{-- KANAN: sidebar --}}
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
                <div style="font-weight:800;font-size:14px;margin-bottom:10px">Butuh jawaban cepat?</div>
                <p style="font-size:13px;color:var(--ink-mid);line-height:1.6;margin:0 0 12px">Thread dengan balasan terbanyak biasanya paling membantu. Cek diskusi lain yang serupa:</p>
                <a href="{{ route('community.index') }}" class="btn btn-outline" style="width:100%;text-align:center">← Semua Thread</a>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .community-cols { flex-direction: column !important; }
            .community-cols > div:first-child { flex: 1 1 auto !important; width: 100%; }
        }
    </style>
@endsection
