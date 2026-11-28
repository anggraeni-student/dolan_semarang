@extends('layouts.app')
@section('title', 'Chat dengan Tim Dolan')

@section('content')
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        Chat
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:6px">
        <div>
            <h1 style="margin:0">Kotak Chat</h1>
            <p style="color:var(--ink-mid);font-size:13.5px;margin:5px 0 0">
                Ngobrol langsung sama tim admin — tanya apa saja, laporan masukmu juga dibalas dari sini.
            </p>
        </div>
    </div>

    <div style="display:flex;gap:20px;align-items:flex-start;margin-top:18px" class="chat-cols">

        {{-- SIDEBAR: daftar utas (admin melihat semua member) --}}
        @if (auth()->user()->is_admin)
            <div style="flex:0 0 270px;min-width:0" class="chat-side">
                <div class="card" style="padding:14px 16px">
                    <div style="font-weight:800;font-size:13.5px;margin-bottom:10px;display:flex;align-items:center;gap:6px">
                        <span class="material-icons" style="font-size:17px;color:var(--primary)">forum</span>
                        Chat Member
                    </div>
                    @forelse ($conversations as $c)
                        <a href="{{ route('chat.index', ['c' => $c->id]) }}"
                           style="display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:10px;text-decoration:none;{{ $active && $active->id === $c->id ? 'background:var(--pink-soft)' : '' }};{{ !$loop->last ? 'margin-bottom:2px' : '' }}">
                            <span class="avatar" style="width:32px;height:32px;font-size:11px;flex-shrink:0">{{ $c->user->avatar_initials }}</span>
                            <span style="min-width:0;flex:1">
                                <span style="display:block;font-weight:700;font-size:13px;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $c->user->name }}</span>
                                <span style="display:block;font-size:11px;color:var(--ink-soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    @if ($c->messages_count)
                                        {{ $c->messages_count }} pesan
                                    @else
                                        belum ada pesan
                                    @endif
                                </span>
                            </span>
                            @if (($unreadCounts[$c->id] ?? 0) > 0)
                                <span style="background:#E23A4E;color:#fff;font-size:10px;font-weight:800;border-radius:99px;padding:1px 7px;flex-shrink:0">{{ $unreadCounts[$c->id] }}</span>
                            @endif
                        </a>
                    @empty
                        <div class="card-meta" style="padding:8px 0">Belum ada member yang memulai chat.</div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- PANEL UTAMA: isi obrolan --}}
        <div style="flex:1;min-width:0">
            @if ($active)
                <div class="card" style="padding:0;overflow:hidden">
                    {{-- header utas --}}
                    <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--line);flex-wrap:wrap">
                        @if (auth()->user()->is_admin)
                            <span class="avatar" style="width:34px;height:34px;font-size:12px">{{ $active->user->avatar_initials }}</span>
                            <div style="min-width:0;flex:1">
                                <div style="font-weight:800;font-size:14px">{{ $active->user->name }}
                                    <x-admin-badge :user="$active->user" />
                                </div>
                                <div class="card-meta">@{{ $active->user->username }} · member sejak {{ $active->user->created_at->translatedFormat('M Y') }}</div>
                            </div>
                        @else
                            <span style="display:inline-flex;width:34px;height:34px;border-radius:50%;background:#2B2432;color:#fff;align-items:center;justify-content:center;flex-shrink:0">
                                <span class="material-icons" style="font-size:18px">support_agent</span>
                            </span>
                            <div style="min-width:0;flex:1">
                                <div style="font-weight:800;font-size:14px">Tim Dolan Semarang</div>
                                <div class="card-meta">biasanya balas dalam 1×24 jam</div>
                            </div>
                        @endif
                        <span class="tag" style="background:var(--pink-soft)">{{ $active->messages->count() }} pesan</span>
                    </div>

                    {{-- daftar pesan --}}
                    <div style="max-height:420px;overflow-y:auto;padding:18px;display:flex;flex-direction:column;gap:12px;background:#FAFAFB" class="chat-scroll">
                        @forelse ($active->messages as $m)
                            @if ($m->is_warning)
                                {{-- PERINGATAN RESMI ADMIN --}}
                                <div style="align-self:{{ $m->sender_id === auth()->id() ? 'flex-end' : 'flex-start' }};max-width:88%;background:#FDECEF;border:1.5px solid #F4C2CC;border-radius:14px;padding:12px 14px">
                                    <div style="display:flex;align-items:center;gap:6px;font-size:11px;font-weight:800;color:#B3213B;letter-spacing:.4px;margin-bottom:6px">
                                        <span class="material-icons" style="font-size:14px">gpp_maybe</span>
                                        PERINGATAN RESMI DARI ADMIN
                                    </div>
                                    <div style="font-size:13.5px;color:#7A1E30;line-height:1.6;white-space:pre-line">{{ $m->body }}</div>
                                    <div style="font-size:10.5px;color:#C77A8A;margin-top:6px">{{ $m->created_at->translatedFormat('d M Y, H.i') }}</div>
                                </div>
                            @else
                                <div style="align-self:{{ $m->sender_id === auth()->id() ? 'flex-end' : 'flex-start' }};max-width:88%;display:flex;flex-direction:column;gap:3px">
                                    @if ($m->sender_id !== auth()->id())
                                        <div style="font-size:11px;color:var(--ink-soft);display:flex;align-items:center;gap:5px">
                                            <span class="avatar" style="width:20px;height:20px;font-size:8px;padding:0">{{ $m->sender->avatar_initials }}</span>
                                            <b style="color:var(--ink-mid)">{{ $m->sender->name }}</b>
                                            <x-admin-badge :user="$m->sender" />
                                        </div>
                                    @endif
                                    <div style="background:{{ $m->sender_id === auth()->id() ? 'var(--primary)' : '#FFFFFF' }};color:{{ $m->sender_id === auth()->id() ? '#fff' : 'var(--ink)' }};border:1px solid {{ $m->sender_id === auth()->id() ? 'var(--primary)' : 'var(--line)' }};border-radius:14px;padding:10px 14px;font-size:14px;line-height:1.6;white-space:pre-line">{{ $m->body }}</div>
                                    <div style="font-size:10.5px;color:var(--ink-soft);text-align:{{ $m->sender_id === auth()->id() ? 'right' : 'left' }}">{{ $m->created_at->translatedFormat('d M, H.i') }}{{ $m->read_at ? ' · dibaca' : '' }}</div>
                                </div>
                            @endif
                        @empty
                            <div style="text-align:center;color:var(--ink-soft);font-size:13.5px;padding:34px 0">
                                <span class="material-icons" style="font-size:34px">chat_bubble_outline</span>
                                <p style="margin:8px 0 0">Belum ada pesan. Sapa duluan, sapa malu-malu — semua santai kok 😊</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- form kirim pesan --}}
                    <form method="POST" action="{{ route('chat.send', $active) }}" style="border-top:1px solid var(--line);padding:14px 18px">
                        @csrf
                        <textarea name="body" rows="2" class="input" style="margin:0" placeholder="Tulis pesanmu…" required>{{ old('body') }}</textarea>
                        @error('body')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                        <div style="display:flex;align-items:center;gap:12px;margin-top:10px;flex-wrap:wrap">
                            @if (auth()->user()->is_admin)
                                <label style="display:flex;align-items:center;gap:7px;font-size:12.5px;color:#B3213B;font-weight:700;cursor:pointer">
                                    <input type="checkbox" name="is_warning" value="1" style="accent-color:#E23A4E">
                                    <span class="material-icons" style="font-size:16px">gpp_maybe</span>
                                    Kirim sebagai peringatan resmi
                                </label>
                            @endif
                            <button type="submit" class="btn btn-primary" style="margin-left:auto;padding:9px 22px">Kirim</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="card empty-state">
                    <span class="material-icons" style="font-size:40px;color:var(--ink-soft)">forum</span>
                    <p style="margin-top:8px">Pilih chat member di samping untuk mulai membalas.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .chat-cols { flex-direction: column !important; }
            .chat-side { flex: 1 1 auto !important; width: 100%; }
            .chat-scroll { max-height: 320px !important; }
        }
    </style>

    <script>
        // scroll ke pesan terbaru saat halaman dibuka
        window.addEventListener('DOMContentLoaded', function () {
            var box = document.querySelector('.chat-scroll');
            if (box) box.scrollTop = box.scrollHeight;
        });
    </script>
@endsection
