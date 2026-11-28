{{-- Kartu destinasi reusable (gaya kartu prototype) --}}
<a href="{{ route('destinations.show', $destination->slug) }}" class="card card-link">
    <div class="card-img">
        <img src="{{ asset($destination->hero_image) }}" alt="{{ $destination->name }}" loading="lazy">
        @if($destination->category?->name)
            <span class="tag card-cat">{{ $destination->category->name }}</span>
        @endif
        @if(!empty($destination->best_time))
            <span class="tag" style="position:absolute; top:10px; right:10px; background:#fff1f2; color:#e11d48; border:1px solid #fecdd3; font-size:11px; padding:3px 8px; border-radius:999px;">
                ⏰ {{ $destination->best_time }}
            </span>
        @endif
    </div>
    <div class="card-body">
        <h3 class="card-title">{{ $destination->name }}</h3>
        <div class="card-meta">{{ $destination->area ?: $destination->location }}</div>

        <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px;">
            @if(!empty($destination->budget_tier))
                <span style="font-size:11px; padding:2px 8px; border-radius:999px; background:#ecfdf5; color:#047857; border:1px solid #a7f3d0;">
                    💰 {{ $destination->budget_tier }}
                </span>
            @endif
            @if(!empty($destination->opening_hours))
                <span style="font-size:11px; padding:2px 8px; border-radius:999px; background:#fffbeb; color:#b45309; border:1px solid #fde68a;">
                    🕒 {{ $destination->opening_hours }}
                </span>
            @endif
        </div>

        <div class="rating-line" style="margin-top:8px">
            <span class="stars">@for ($i = 1; $i <= 5; $i++)@if ($i <= round($destination->rating ?? 0))★@else☆@endif @endfor</span>
            <span class="rating-num">{{ number_format($destination->rating ?? 0, 1) }}</span>
            <span class="card-meta">· {{ $destination->review_count ?? 0 }} review</span>
        </div>
    </div>
</a>