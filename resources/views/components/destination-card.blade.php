{{-- Kartu destinasi reusable (gaya kartu prototype) --}}
<a href="{{ route('destinations.show', $destination->slug) }}" class="card card-link">
    <div class="card-img">
        <img src="{{ asset($destination->hero_image) }}" alt="{{ $destination->name }}" loading="lazy">
        <span class="tag card-cat">{{ $destination->category->name }}</span>
    </div>
    <div class="card-body">
        <h3 class="card-title">{{ $destination->name }}</h3>
        <div class="card-meta">{{ $destination->location }}</div>
        <div class="rating-line" style="margin-top:6px">
            <span class="stars">@for ($i = 1; $i <= 5; $i++)@if ($i <= round($destination->rating))★@else<span class="off">★</span>@endif @endfor</span>
            <span class="rating-num">{{ number_format($destination->rating, 1) }}</span>
            <span class="card-meta">· {{ $destination->review_count }} review</span>
        </div>
    </div>
</a>
