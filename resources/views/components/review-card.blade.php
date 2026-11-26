{{-- Kartu review komunitas --}}
<div class="card" style="padding:16px;margin-bottom:14px">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap">
        <a href="{{ route('profiles.show', $review->user->username) }}" class="avatar" style="width:34px;height:34px;font-size:12px" title="Lihat profil {{ $review->user->name }}">
            {{ $review->user->avatar_initials }}
        </a>
        <div>
            <div style="font-weight:700;font-size:13.5px">
                <a href="{{ route('profiles.show', $review->user->username) }}" style="color:var(--ink)">{{ $review->user->name }}</a>
                @if ($review->user->badge['name'] === 'Legenda Dolan')
                    <span class="badge-lime" style="margin-left:6px">{{ $review->user->badge['name'] }}</span>
                @endif
            </div>
        </div>
        <div style="margin-left:auto;display:flex;align-items:center;gap:8px">
            <span class="stars">@for ($i = 1; $i <= 5; $i++)@if ($i <= round($review->score))★@else<span class="off">★</span>@endif @endfor</span>
            <span class="card-meta">{{ $review->visited_at?->translatedFormat('M Y') ?? $review->created_at->translatedFormat('M Y') }}</span>
        </div>
    </div>
    <p style="font-size:14px;color:var(--ink-mid);margin:0 0 8px">{{ $review->body }}</p>
    <div class="card-meta">
        <span class="material-icons" style="font-size:13px;vertical-align:-2px;color:var(--primary)">thumb_up</span>
        <b style="color:var(--ink)">{{ $review->helpful_count }}</b> terbantu
        @if ($review->photo_count)
            · {{ $review->photo_count }} foto
        @endif
    </div>
</div>
