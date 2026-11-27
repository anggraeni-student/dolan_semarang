@extends('layouts.app')
@section('title', 'Hasil Pencarian' . ($q !== '' ? ' "' . $q . '"' : ''))

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        Pencarian
    </div>

    <h1 style="margin-bottom:6px">Hasil Pencarian</h1>

    @if ($q === '')
        <p style="color:var(--ink-mid)">Ketik kata kunci di kolom pencarian di atas (mis. nama tempat, lokasi, atau tema).</p>
        <form method="GET" action="{{ route('search') }}" style="display:flex;gap:10px;max-width:480px;margin-top:14px">
            <input type="search" name="q" class="input" placeholder="Cari destinasi, artikel, diskusi…" aria-label="Kata kunci" autofocus>
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    @elseif ($total === 0)
        <div class="empty-state" style="margin-top:24px">
            <span class="material-icons" style="font-size:44px;color:var(--ink-soft)">search_off</span>
            <h2 style="margin:10px 0 6px;font-size:18px">Tidak ada hasil untuk "{{ $q }}"</h2>
            <p style="color:var(--ink-mid);margin:0">Coba kata kunci lain — mis. "Lawang Sewu", "kuliner", atau "pantai".</p>
            <div style="display:flex;gap:10px;justify-content:center;margin-top:16px;flex-wrap:wrap">
                <a href="{{ route('destinations.index') }}" class="btn btn-outline">Jelajah Semua Destinasi</a>
                <a href="{{ route('reviews.all') }}" class="btn btn-ghost">Lihat Review</a>
            </div>
        </div>
    @else
        <p style="color:var(--ink-mid);margin:0 0 18px">Ditemukan <b style="color:var(--ink)">{{ $total }}</b> hasil untuk "<b>{{ $q }}</b>"</p>

        @foreach ($results as $group)
            @if ($group['items']->isNotEmpty())
                <div class="section" style="margin-top:8px">
                    <div class="section-title">
                        <h2 style="display:flex;align-items:center;gap:8px;font-size:18px">
                            <span class="material-icons" style="font-size:20px;color:var(--primary)">{{ $group['icon'] }}</span>
                            {{ $group['label'] }}
                            <span class="card-meta" style="font-weight:600">({{ $group['items']->count() }})</span>
                        </h2>
                    </div>

                    @if ($group['key'] === 'destinasi')
                        <div class="grid-cards">
                            @foreach ($group['items'] as $destination)
                                <x-destination-card :destination="$destination" />
                            @endforeach
                        </div>
                    @elseif ($group['key'] === 'artikel')
                        <div style="display:flex;flex-direction:column;gap:12px">
                            @foreach ($group['items'] as $article)
                                <a href="{{ route('articles.show', $article->slug) }}" class="card card-link" style="display:flex;gap:14px;padding:12px;align-items:center">
                                    <img src="{{ asset($article->cover_image) }}" alt="{{ $article->title }}" style="width:96px;height:72px;object-fit:cover;border-radius:10px;flex-shrink:0" loading="lazy">
                                    <div style="min-width:0">
                                        <span class="tag" style="background:var(--pink-soft)">{{ $article->tag }}</span>
                                        <div style="font-weight:800;font-size:14.5px;margin-top:6px">{{ $article->title }}</div>
                                        <div class="card-meta">{{ $article->read_minutes }} menit baca · ★ {{ number_format($article->likes) }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div style="display:flex;flex-direction:column;gap:12px">
                            @foreach ($group['items'] as $thread)
                                <a href="{{ route('community.show', $thread) }}" class="card card-link" style="display:block;padding:14px 16px">
                                    <span class="tag" style="font-size:10px">{{ $thread->type === 'tanya' ? 'TANYA' : 'CERITA' }}</span>
                                    <div style="font-weight:800;font-size:15px;margin-top:6px">{{ $thread->title }}</div>
                                    <div class="card-meta" style="margin-top:4px">oleh {{ $thread->user->name }} · {{ $thread->reply_count }} balasan</div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    @endif
@endsection
