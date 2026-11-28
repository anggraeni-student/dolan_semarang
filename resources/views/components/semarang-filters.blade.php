@props(['action' => url()->current()])

@php
    $budgets = [
        'gratis' => 'Gratis / Rp 0',
        'hemat' => 'Hemat (< Rp 25.000)',
        'menengah' => 'Menengah (Rp 25.000 - Rp 50.000)',
        'premium' => 'Premium (> Rp 50.000)',
    ];

    $areas = [
        'kota-lama' => 'Kota Lama',
        'simpang-lima' => 'Simpang Lima & Tengah',
        'tembalang' => 'Tembalang & Banyumanik',
        'semarang-barat' => 'Semarang Barat & Pantai',
        'ungaran-bandungan' => 'Ungaran & Bandungan',
        'gunungpati' => 'Gunungpati & Mijen',
    ];

    $times = [
        'pagi' => 'Pagi / Sunrise',
        'sore' => 'Sore / Sunset',
        'malam' => 'Malam / Kulineran',
        'indoor' => 'Anti-Hujan / Indoor',
    ];

    $hasActiveFilter = request()->anyFilled(['budget', 'area', 'best_time']);
@endphp

@once
<style>
    .smg-filter-card {
        background: #ffffff;
        border: 1px solid #eceef2;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        margin: 24px 0 32px;
    }
    .smg-filter-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr) minmax(170px, 0.9fr);
        gap: 14px;
        align-items: end;
    }
    .smg-filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .smg-filter-label {
        font-size: 12.5px;
        font-weight: 500;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .smg-filter-select {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background-color: #ffffff;
        color: #111827;
        font-size: 14px;
        outline: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .smg-filter-select:focus {
        border-color: #df1c4c;
        box-shadow: 0 0 0 3px rgba(223, 28, 76, 0.12);
    }
    .smg-filter-btn {
        height: 44px;
        padding: 0 22px;
        border: none;
        border-radius: 10px;
        background-color: #df1c4c;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .smg-filter-btn:hover {
        background-color: #c41440;
    }
    .smg-quick-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid #f3f4f6;
    }
    .smg-quick-label {
        font-size: 12.5px;
        color: #9ca3af;
        margin-right: 4px;
    }
    .smg-quick-pill {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #4b5563;
        font-size: 12.5px;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .smg-quick-pill:hover {
        border-color: #df1c4c;
        color: #df1c4c;
    }
    .smg-quick-pill.is-active {
        border-color: #df1c4c;
        background: #fff1f4;
        color: #df1c4c;
        font-weight: 600;
    }
    .smg-reset-link {
        margin-left: auto;
        font-size: 12.5px;
        color: #df1c4c;
        text-decoration: none;
        font-weight: 500;
    }
    .smg-reset-link:hover {
        text-decoration: underline;
    }
    @media (max-width: 768px) {
        .smg-filter-grid {
            grid-template-columns: 1fr;
        }
        .smg-reset-link {
            margin-left: 0;
        }
    }
</style>
@endonce

<div class="smg-filter-card">
    <form method="GET" action="{{ $action }}" class="smg-filter-grid">
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif

        <div class="smg-filter-group">
            <label for="filter-budget" class="smg-filter-label">💰 Budget / HTM</label>
            <select id="filter-budget" name="budget" class="smg-filter-select">
                <option value="">Semua Budget</option>
                @foreach($budgets as $key => $label)
                    <option value="{{ $key }}" @selected(request('budget') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="smg-filter-group">
            <label for="filter-area" class="smg-filter-label">📍 Area Semarang</label>
            <select id="filter-area" name="area" class="smg-filter-select">
                <option value="">Semua Area</option>
                @foreach($areas as $key => $label)
                    <option value="{{ $key }}" @selected(request('area') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="smg-filter-group">
            <label for="filter-time" class="smg-filter-label">⏰ Waktu Terbaik</label>
            <select id="filter-time" name="best_time" class="smg-filter-select">
                <option value="">Kapan Saja</option>
                @foreach($times as $key => $label)
                    <option value="{{ $key }}" @selected(request('best_time') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="smg-filter-btn">Terapkan Filter</button>
    </form>

    <div class="smg-quick-row">
        <span class="smg-quick-label">Cepat pilih waktu:</span>

        @foreach($times as $key => $label)
            @php
                $isActive = request('best_time') === $key;
                $pillUrl = request()->fullUrlWithQuery([
                    'best_time' => $isActive ? null : $key,
                ]);
            @endphp
            <a href="{{ $pillUrl }}" class="smg-quick-pill {{ $isActive ? 'is-active' : '' }}">
                {{ $label }}
            </a>
        @endforeach

        @if($hasActiveFilter)
            <a href="{{ $action }}" class="smg-reset-link">Reset Filter</a>
        @endif
    </div>
</div>