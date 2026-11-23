@extends('layouts.app')
@section('title', 'Peta Dolan Semarang')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> / Peta
    </div>

    <div style="margin-bottom:14px">
        <h1 style="margin-bottom:6px">Peta Dolan Semarang</h1>
        <p style="color:var(--ink-mid);margin:0">Temukan tempat asyik di Semarang dan sekitarnya.</p>
    </div>

    {{-- SIDEBAR + PETA --}}
    <div style="display:flex;gap:16px;align-items:flex-start" class="map-cols">
        {{-- SIDEBAR --}}
        <div style="flex:0 0 300px;min-width:0;width:100%">
            <div class="card" style="padding:14px 16px">
                <div style="display:flex;align-items:center;gap:8px;border:1px solid var(--line);border-radius:10px;padding:9px 12px;color:var(--ink-soft);font-size:13px;background:#FAFAFA;margin-bottom:12px">
                    <span class="material-icons" style="font-size:16px">search</span>
                    Cari kota atau tempat…
                </div>

                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px">
                    @foreach ($categories as $cat)
                        <span class="cat-chip" style="font-size:11.5px;padding:4px 11px;cursor:default">
                            <span class="material-icons" style="font-size:14px">{{ $cat->icon }}</span>{{ $cat->name }}
                        </span>
                    @endforeach
                </div>

                {{-- Mini-list destinasi --}}
                <div style="display:flex;flex-direction:column;gap:8px">
                    @foreach ($destinations as $i => $destination)
                        @if ($i === 0)
                            {{-- Destinasi pertama: kartu highlight --}}
                            <a href="{{ route('destinations.show', $destination->slug) }}" class="card" style="display:flex;gap:10px;align-items:center;padding:10px 12px;border:2px solid var(--primary);text-decoration:none">
                                <img src="{{ asset($destination->hero_image) }}" alt="{{ $destination->name }}" style="width:56px;height:56px;object-fit:cover;border-radius:10px;flex-shrink:0">
                                <div style="flex:1;min-width:0">
                                    <div style="font-size:14px;font-weight:800;color:var(--ink)">{{ $destination->name }}</div>
                                    <div style="font-size:12px;color:var(--ink-mid)">
                                        <span style="color:var(--star)">★</span> <b style="color:var(--ink)">{{ number_format($destination->rating, 1) }}</b> · {{ $destination->review_count }} review
                                    </div>
                                    <span style="font-size:12px;color:var(--primary);font-weight:700">buka detail ›</span>
                                </div>
                            </a>
                        @else
                            {{-- Baris biasa --}}
                            <a href="{{ route('destinations.show', $destination->slug) }}" style="display:flex;gap:10px;align-items:center;padding:8px 10px;border-radius:12px;text-decoration:none">
                                <img src="{{ asset($destination->hero_image) }}" alt="{{ $destination->name }}" style="width:56px;height:56px;object-fit:cover;border-radius:10px;flex-shrink:0">
                                <div style="flex:1;min-width:0">
                                    <div style="font-size:13.5px;font-weight:700;color:var(--ink)">{{ $destination->name }}</div>
                                    <div style="font-size:12px;color:var(--ink-soft)">
                                        <span style="color:var(--star)">★</span> {{ number_format($destination->rating, 1) }} · {{ $destination->review_count }} review
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- PETA --}}
        <div style="flex:1;min-width:0" class="map-area">
            <div class="map-wrap">
                <div id="map"></div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @php
        $mapDestinations = $destinations->map(function ($d) {
            return [
                'lat' => $d->lat,
                'lng' => $d->lng,
                'name' => $d->name,
                'image' => asset($d->hero_image),
                'rating' => number_format($d->rating, 1),
                'review_count' => $d->review_count,
                'url' => route('destinations.show', $d->slug),
            ];
        })->values();
    @endphp
    <script>
        var map = L.map('map', { center: [-7.05, 110.45], zoom: 11 });
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var destinations = @json($mapDestinations);

        destinations.forEach(function (d) {
            if (d.lat === null || d.lng === null) return;
            var popupHtml =
                '<div class="map-pin-popup">' +
                    '<img src="' + d.image + '" alt="' + d.name + '">' +
                    '<div style="font-weight:800;font-size:13px;color:#13161A;margin-bottom:2px">' + d.name + '</div>' +
                    '<div style="font-size:12px;color:#454F58">★ ' + d.rating + ' · ' + d.review_count + ' review</div>' +
                    '<a href="' + d.url + '" style="font-size:12.5px;font-weight:700">Lihat detail ›</a>' +
                '</div>';
            L.marker([d.lat, d.lng]).addTo(map).bindPopup(popupHtml);
        });
    </script>

    <style>
        @media (max-width: 900px) {
            .map-cols { flex-direction: column !important; }
            .map-cols > div:first-child { flex: 1 1 auto !important; width: 100%; }
        }
        #map img { max-width: none; }
        .leaflet-container { font-family: var(--font-body); }
    </style>
@endsection
