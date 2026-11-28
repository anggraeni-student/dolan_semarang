{{-- Tombol aksi cepat pada konten:
     - Admin  : tombol HAPUS konten langsung dari halaman biasa (moderasi inline)
     - User   : form LAPORKAN konten ke moderator
     - Pemilik konten / guest: tidak menampilkan apa pun
     Pemakaian: <x-report-button :model="$review" /> --}}
@props(['model'])

@php
    $type = match (class_basename($model)) {
        'Review' => 'review',
        'Thread' => 'thread',
        'ThreadReply' => 'reply',
        'Destination' => 'destination',
        default => null,
    };
    // setelah destinasi dihapus, halaman detailnya ikut hilang → kembali ke daftar destinasi
    $backUrl = $type === 'destination' ? route('destinations.index') : url()->current();
@endphp

@if ($type && auth()->check())
    @if (auth()->user()->is_admin)
        {{-- Admin: hapus langsung --}}
        <form method="POST" action="{{ route('moderation.destroy') }}"
              onsubmit="return confirm('Hapus konten ini secara permanen?')"
              style="display:inline-flex">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <input type="hidden" name="back" value="{{ $backUrl }}">
            <button type="submit"
                    title="Hapus konten (admin)"
                    style="display:inline-flex;align-items:center;gap:4px;background:#FDECEF;color:#B3213B;border:1px solid #F4C2CC;font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;cursor:pointer;font-family:inherit">
                <span class="material-icons" style="font-size:13px">delete</span>Hapus
            </button>
        </form>
    @elseif (($model->authorId() ?? null) !== auth()->id())
        {{-- User biasa: laporkan --}}
        <form method="POST" action="{{ route('reports.store') }}" style="display:inline-flex;gap:6px;align-items:center">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <select name="reason" title="Alasan laporan"
                    style="font-size:11px;border:1px solid var(--line);border-radius:99px;padding:3px 8px;background:#fff;color:var(--ink-mid);font-family:inherit">
                <option value="spam">Spam</option>
                <option value="ofensif">Ofensif</option>
                <option value="tidak-relevan">Tidak relevan</option>
                <option value="data-salah">Data salah</option>
                <option value="lainnya">Lainnya</option>
            </select>
            <button type="submit"
                    title="Laporkan konten ini ke moderator"
                    style="display:inline-flex;align-items:center;gap:4px;background:var(--pink-soft);color:var(--primary-deep);border:1px solid var(--pink-line);font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;cursor:pointer;font-family:inherit">
                <span class="material-icons" style="font-size:13px">flag</span>Laporkan
            </button>
        </form>
    @endif
@endif
