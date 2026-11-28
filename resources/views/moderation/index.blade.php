@extends('layouts.app')
@section('title', 'Moderasi Konten — Dolan Semarang')

@section('content')
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        Moderasi
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:6px">
        <div>
            <h1 style="margin:0">Moderasi Konten</h1>
            <p style="color:var(--ink-mid);font-size:13.5px;margin:5px 0 0">
                Tinjau laporan dari user — hapus konten, kirim peringatan lewat chat, atau abaikan. Semua dari dashboard biasa, tanpa buka panel admin.
            </p>
        </div>
        <a href="/admin" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px">
            <span class="material-icons" style="font-size:16px">admin_panel_settings</span>Panel Admin
        </a>
    </div>

    {{-- Filter status --}}
    <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;align-items:center">
        @foreach (['open' => 'Menunggu', 'warned' => 'Diperingatkan', 'actioned' => 'Ditindak', 'dismissed' => 'Diabaikan', 'all' => 'Semua'] as $key => $label)
            <a href="{{ route('moderation.index', ['status' => $key]) }}"
               class="cat-chip {{ $status === $key ? 'active' : '' }}">
                {{ $label }}
                @if ($key !== 'all' && ($counts[$key] ?? 0) > 0)
                    <b style="margin-left:3px">{{ $counts[$key] }}</b>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Daftar laporan --}}
    <div style="display:flex;flex-direction:column;gap:14px;margin-top:16px">
        @forelse ($reports as $report)
            <div class="card" style="padding:16px 18px">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    <a href="{{ route('profiles.show', $report->reporter->username) }}" class="avatar" style="width:30px;height:30px;font-size:11px" title="Pelapor: {{ $report->reporter->name }}">
                        {{ $report->reporter->avatar_initials }}
                    </a>
                    <div style="font-size:13px">
                        <a href="{{ route('profiles.show', $report->reporter->username) }}" style="font-weight:700;color:var(--ink)">{{ $report->reporter->name }}</a>
                        <span style="color:var(--ink-soft)"> melaporkan · {{ $report->created_at->diffForHumans() }}</span>
                    </div>
                    <span class="tag" style="background:var(--pink-soft);color:var(--primary-deep)">{{ $report->reasonLabel }}</span>
                    <span class="tag" style="background:{{ ['open' => '#FFF4E5', 'warned' => '#FDECEF', 'actioned' => '#EDEDED', 'dismissed' => '#EDEDED'][$report->status] ?? '#EDEDED' }};color:#4A4450">
                        {{ \App\Models\Report::STATUSES[$report->status] ?? $report->status }}
                    </span>
                    <span class="card-meta" style="margin-left:auto">#{{ $report->id }}</span>
                </div>

                {{-- Preview konten yang dilaporkan --}}
                <div style="margin-top:10px;background:#FAFAFB;border:1px solid var(--line);border-radius:12px;padding:12px 14px">
                    <div class="card-meta" style="font-weight:700;color:var(--primary-deep)">{{ $report->targetSummary }}</div>
                    @if ($report->reportable && $report->targetAuthor)
                        <div class="card-meta" style="margin-top:4px">
                            Ditulis oleh
                            <a href="{{ route('profiles.show', $report->targetAuthor->username) }}" style="font-weight:700;color:var(--ink)">{{ $report->targetAuthor->name }}</a>
                            <x-admin-badge :user="$report->targetAuthor" />
                        </div>
                    @elseif ($report->reportable)
                        <div class="card-meta" style="margin-top:4px">Tanpa penulis (konten sistem)</div>
                    @endif
                    @if ($report->note)
                        <div class="card-meta" style="margin-top:6px;font-style:italic">Catatan pelapor: “{{ $report->note }}”</div>
                    @endif
                </div>

                {{-- Aksi --}}
                @if ($report->status === 'open')
                    <div style="display:flex;gap:10px;margin-top:12px;flex-wrap:wrap;align-items:center">
                        <form method="POST" action="{{ route('moderation.action', $report) }}" style="display:inline-flex"
                              onsubmit="return confirm('Hapus konten ini? Tindakan tidak bisa dibatalkan.')">
                            @csrf
                            <input type="hidden" name="do" value="delete">
                            <button type="submit" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;font-size:13px">
                                <span class="material-icons" style="font-size:15px">delete</span>Hapus Konten
                            </button>
                        </form>
                        <form method="POST" action="{{ route('moderation.action', $report) }}" style="display:flex;gap:8px;flex:1;min-width:260px;flex-wrap:wrap"
                              onsubmit="return confirm('Hapus konten DAN kirim peringatan ke penulisnya via chat?')">
                            @csrf
                            <input type="hidden" name="do" value="warn">
                            <input type="text" name="warning" class="input" style="flex:1;min-width:200px;font-size:12.5px;padding:8px 12px"
                                   placeholder="Isi peringatan (kosongkan = pakai teks bawaan)"
                                   value="Konten &#8220;{{ $report->targetSummary }}&#8221; kamu telah dihapus karena melanggar panduan komunitas (kategori laporan: {{ $report->reasonLabel }}). Mohon jaga konten selanjutnya tetap sesuai panduan, ya!">
                            <button type="submit" style="display:inline-flex;align-items:center;gap:5px;background:#2B2432;color:#fff;font-weight:700;font-size:13px;padding:8px 16px;border:none;border-radius:10px;cursor:pointer;font-family:inherit">
                                <span class="material-icons" style="font-size:15px">gpp_maybe</span>Hapus + Peringatan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('moderation.action', $report) }}" style="display:inline-flex">
                            @csrf
                            <input type="hidden" name="do" value="dismiss">
                            <button type="submit" class="btn btn-ghost" style="padding:8px 14px;font-size:13px">Abaikan</button>
                        </form>
                    </div>
                @else
                    <div class="card-meta" style="margin-top:10px">
                        Ditangani oleh
                        @if ($report->handler)
                            <b>{{ $report->handler->name }}</b> · {{ $report->handled_at?->translatedFormat('d M Y, H.i') ?? '–' }}
                        @else
                            sistem
                        @endif
                        · <a href="{{ route('moderation.index', ['status' => 'open']) }}" style="color:var(--primary)">kembali ke antrean</a>
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state card" style="padding:40px 20px">
                <span class="material-icons" style="font-size:42px;color:var(--ink-soft)">verified</span>
                <p style="margin-top:8px;color:var(--ink-mid)">{{ $status === 'open' ? 'Antrean bersih — tidak ada laporan menunggu 🎉' : 'Belum ada laporan dengan status ini.' }}</p>
                <a href="{{ route('home') }}" class="btn btn-outline" style="margin-top:8px">Kembali ke Beranda</a>
            </div>
        @endforelse
    </div>
@endsection
