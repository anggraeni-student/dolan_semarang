@extends('layouts.app')
@section('title', $profileUser->name . ' — Profil Dolan Semarang')

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('profiles.show', $profileUser->username) }}">Profil</a> /
        {{ $profileUser->name }}
    </div>

    {{-- HEADER PROFIL --}}
    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <span class="avatar" style="width:72px;height:72px;font-size:24px">{{ $profileUser->avatar_initials }}</span>
        <div style="min-width:0">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                <h1 style="margin:0;font-size:1.7rem">{{ $profileUser->name }}</h1>
                <span class="badge-lime"><span class="material-icons" style="font-size:13px">verified</span>{{ $profileUser->badge['name'] }}</span>
                <span class="card-meta" style="font-weight:700">Level {{ $profileUser->badge['level'] }}</span>
            </div>
            <div class="card-meta" style="margin-top:3px">@{{ $profileUser->username }}</div>
            @if ($profileUser->bio)
                <p style="margin:4px 0 0;color:var(--ink-mid);font-size:13.5px">{{ $profileUser->bio }}</p>
            @endif
        </div>
    </div>

    {{-- STAT STRIP --}}
    <div class="card" style="display:flex;align-items:center;justify-content:space-around;gap:0;padding:14px 24px;margin-top:16px;flex-wrap:wrap">
        <div style="text-align:center;padding:6px 10px">
            <div style="font-weight:800;font-size:22px;color:var(--primary)">{{ $reviews->count() }}</div>
            <div style="font-size:11.5px;color:var(--ink-mid)">review</div>
        </div>
        <div style="width:1px;height:34px;background:var(--pink-line)"></div>
        <div style="text-align:center;padding:6px 10px">
            <div style="font-weight:800;font-size:22px;color:var(--primary)">{{ number_format($profileUser->helpful_received) }}</div>
            <div style="font-size:11.5px;color:var(--ink-mid)">terbantu</div>
        </div>
        <div style="width:1px;height:34px;background:var(--pink-line)"></div>
        <div style="text-align:center;padding:6px 10px">
            <div style="font-weight:800;font-size:22px;color:var(--primary)">{{ $profileUser->provinces_visited }}</div>
            <div style="font-size:11.5px;color:var(--ink-mid)">tempat<br>dikunjungi</div>
        </div>
        <div style="width:1px;height:34px;background:var(--pink-line)"></div>
        <div style="text-align:center;padding:6px 10px">
            <div style="font-weight:800;font-size:22px;color:var(--primary)">Level {{ $profileUser->badge['level'] }}</div>
            <div style="font-size:11.5px;color:var(--ink-mid)">{{ $profileUser->badge['name'] }}</div>
        </div>
    </div>

    {{-- REVIEW + KOLEKSI --}}
    <div style="display:flex;gap:20px;align-items:flex-start;margin-top:24px" class="profile-cols">
        {{-- KIRI: REVIEW --}}
        <div style="flex:1;min-width:0">
            <div class="section-title">
                <h2>Review</h2>
            </div>
            @forelse ($reviews as $review)
                <div class="card" style="display:flex;gap:14px;padding:15px 16px;align-items:center;margin-bottom:12px">
                    <img src="{{ asset($review->destination->hero_image) }}" alt="{{ $review->destination->name }}" style="width:86px;height:86px;object-fit:cover;border-radius:12px;flex-shrink:0" loading="lazy">
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                            <a href="{{ route('destinations.show', $review->destination->slug) }}" style="font-weight:800;font-size:14.5px;color:var(--ink)">{{ $review->destination->name }}</a>
                            <span class="stars">@for ($i = 1; $i <= 5; $i++)@if ($i <= round($review->score))★@else<span class="off">★</span>@endif @endfor</span>
                            <span class="rating-num" style="font-size:14px">{{ number_format($review->score, 1) }}</span>
                        </div>
                        <p style="font-size:13px;color:var(--ink-mid);font-style:italic;line-height:1.5;margin:4px 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ $review->body }}</p>
                        <div class="card-meta">👍 {{ $review->helpful_count }} terbantu · {{ $review->visited_at?->translatedFormat('M Y') ?? $review->created_at->translatedFormat('M Y') }}</div>
                    </div>
                </div>
            @empty
                <div class="empty-state">Belum ada review.</div>
            @endforelse
        </div>

        {{-- KANAN: EDIT PROFIL + PREFERENSI + KOLEKSI --}}
        <div style="flex:0 0 320px;min-width:0" class="profile-side">
            <div style="display:flex;flex-direction:column;gap:16px">

                @if ($isOwnProfile)
                    {{-- EDIT PROFIL & PREFERENSI (hanya pemilik akun) --}}
                    <div class="card" style="padding:0;overflow:visible">
                        <button type="button" onclick="var f=document.getElementById('edit-profile-form');f.style.display=f.style.display==='none'?'block':'none';this.querySelector('.toggle-icon').textContent=f.style.display==='none'?'expand_more':'expand_less'"
                                style="width:100%;display:flex;align-items:center;gap:10px;padding:16px 18px;background:none;border:none;cursor:pointer;font-family:var(--font-heading);font-weight:800;font-size:14.5px;color:var(--ink)">
                            <span class="material-icons" style="font-size:19px;color:var(--primary)">tune</span>
                            Edit Profil &amp; Preferensi
                            <span class="material-icons toggle-icon" style="margin-left:auto">expand_more</span>
                        </button>

                        <form id="edit-profile-form" method="POST" action="{{ route('profiles.update', $profileUser->username) }}" style="display:none;padding:0 18px 18px">
                            @csrf

                            <label class="label" for="pf-name" style="margin-top:0">Nama Tampilan</label>
                            <input id="pf-name" type="text" name="name" class="input" value="{{ old('name', $profileUser->name) }}" required maxlength="120">
                            @error('name')
                                <div class="field-error">{{ $message }}</div>
                            @enderror

                            <label class="label" for="pf-bio">Bio</label>
                            <textarea id="pf-bio" name="bio" rows="2" class="input" maxlength="255" placeholder="Ceritakan singkat soal dirimu…">{{ old('bio', $profileUser->bio) }}</textarea>

                            <label class="label" for="pf-prov">Jumlah Tempat Dikunjungi</label>
                            <input id="pf-prov" type="number" name="provinces_visited" class="input" min="0" max="1000" value="{{ old('provinces_visited', $profileUser->provinces_visited) }}">

                            <div class="label" style="margin-bottom:7px">Preferensi Minat <span class="card-meta" style="display:inline;font-weight:500">(mengatur rekomendasi homepage-mu)</span></div>
                            <div style="display:flex;flex-wrap:wrap;gap:8px">
                                @php
                                    $selectedIds = old('interests', $profileUser->interests->pluck('id')->map(fn ($id) => (string) $id)->all());
                                @endphp
                                @foreach ($interests as $interest)
                                    <label class="cat-chip {{ in_array((string) $interest->id, $selectedIds, true) ? 'active' : '' }}" style="cursor:pointer;user-select:none">
                                        <input type="checkbox" name="interests[]" value="{{ $interest->id }}"
                                               {{ in_array((string) $interest->id, $selectedIds, true) ? 'checked' : '' }}
                                               onchange="this.closest('label').classList.toggle('active', this.checked)" style="display:none">
                                        <span class="material-icons" style="font-size:14px">{{ $interest->icon }}</span>{{ $interest->name }}
                                    </label>
                                @endforeach
                            </div>
                            @error('interests')
                                <div class="field-error">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="btn btn-primary" style="margin-top:16px">Simpan Perubahan</button>
                        </form>
                    </div>
                @endif

                {{-- KOLEKSI --}}
                <div class="card" style="padding:16px 18px">
                    <div style="display:flex;align-items:center;gap:6px;font-weight:800;font-size:14px;margin-bottom:12px">
                        <span class="material-icons" style="font-size:17px;color:var(--primary)">bookmarks</span>
                        Koleksi
                    </div>
                    @if ($collections->isEmpty())
                        <div class="card-meta">Belum ada koleksi.</div>
                    @else
                        <div style="display:flex;flex-direction:column;gap:10px">
                            @foreach ($collections as $collection)
                                <span class="cat-chip" style="justify-content:space-between">
                                    <span style="display:inline-flex;align-items:center;gap:6px;min-width:0">
                                        <span class="material-icons" style="font-size:14px;color:var(--primary)">{{ $collection->icon ?? 'bookmark' }}</span>
                                        {{ $collection->name }}
                                    </span>
                                    <b>({{ $collection->destinations_count }})</b>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if ($isOwnProfile)
                    <div class="surface" style="padding:16px 18px">
                        <div style="display:flex;align-items:center;gap:6px;font-weight:800;font-size:14px;margin-bottom:8px">
                            <span class="material-icons" style="font-size:17px;color:var(--primary)">add_location_alt</span>
                            Kontribusi Tempat
                        </div>
                        <p style="font-size:12.5px;color:var(--ink-mid);line-height:1.55;margin:0 0 10px">Tahu tempat asyik yang belum ada di Dolan Semarang? Tambahkan sendiri — tanpa perlu admin.</p>
                        <a href="{{ route('destinations.create') }}" class="btn btn-outline" style="width:100%;text-align:center">+ Tambah Tempat Baru</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .profile-cols { flex-direction: column !important; }
            .profile-side { flex: 1 1 auto !important; width: 100%; }
        }
    </style>
@endsection
