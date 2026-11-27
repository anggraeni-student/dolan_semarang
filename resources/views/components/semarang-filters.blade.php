@php
    $budgetList = [
        'Gratis / Parkir Aja',
        'Di Bawah Rp 25.000',
        'Rp 25.000 – Rp 75.000',
        'Di Atas Rp 75.000',
    ];
    $areaList = [
        'Semarang Tengah / Kota Lama',
        'Semarang Barat',
        'Banyumanik / Tembalang',
        'Semarang Selatan / Gombel',
        'Ungaran / Bandungan / Sekitarnya',
    ];
    $timeList = [
        'Pagi / Sunrise',
        'Sore / Sunset',
        'Malam / Kulineran',
        'Anti-Hujan / Indoor',
    ];
@endphp

<form action="{{ url()->current() }}" method="GET" style="background:#ffffff; border:1px solid #e5e7eb; border-radius:16px; padding:16px; margin:20px 0 28px 0; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
    @if(request('q'))
        <input type="hidden" name="q" value="{{ request('q') }}">
    @endif

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(190px, 1fr)); gap:12px; align-items:end;">
        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#6b7280; margin-bottom:4px;">💰 Budget / HTM</label>
            <select name="budget" style="width:100%; border-radius:10px; border:1px solid #d1d5db; background:#f9fafb; padding:9px 12px; font-size:13px;">
                <option value="">Semua Budget</option>
                @foreach($budgetList as $budget)
                    <option value="{{ $budget }}" {{ request('budget') === $budget ? 'selected' : '' }}>{{ $budget }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#6b7280; margin-bottom:4px;">📍 Area Semarang</label>
            <select name="area" style="width:100%; border-radius:10px; border:1px solid #d1d5db; background:#f9fafb; padding:9px 12px; font-size:13px;">
                <option value="">Semua Area</option>
                @foreach($areaList as $area)
                    <option value="{{ $area }}" {{ request('area') === $area ? 'selected' : '' }}>{{ $area }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#6b7280; margin-bottom:4px;">⏰ Waktu Terbaik</label>
            <select name="waktu" style="width:100%; border-radius:10px; border:1px solid #d1d5db; background:#f9fafb; padding:9px 12px; font-size:13px;">
                <option value="">Kapan Saja</option>
                @foreach($timeList as $time)
                    <option value="{{ $time }}" {{ request('waktu') === $time ? 'selected' : '' }}>{{ $time }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="submit" style="flex:1; border-radius:10px; background:#e11d48; color:#ffffff; border:none; font-weight:600; font-size:13px; padding:10px 16px; cursor:pointer;">
                Terapkan Filter
            </button>
            @if(request()->hasAny(['budget', 'area', 'waktu']))
                <a href="{{ url()->current() }}" style="border-radius:10px; border:1px solid #d1d5db; background:#f9fafb; color:#374151; font-size:13px; padding:10px 12px; text-decoration:none;">
                    Reset
                </a>
            @endif
        </div>
    </div>

    <div style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-top:12px; padding-top:12px; border-top:1px solid #f3f4f6;">
        <span style="font-size:12px; color:#9ca3af;">Cepat pilih waktu:</span>
        @foreach($timeList as $time)
            <a href="{{ request()->fullUrlWithQuery(['waktu' => request('waktu') === $time ? null : $time]) }}"
               style="font-size:12px; padding:4px 12px; border-radius:999px; text-decoration:none; border:1px solid {{ request('waktu') === $time ? '#e11d48' : '#e5e7eb' }}; background:{{ request('waktu') === $time ? '#fff1f2' : '#ffffff' }}; color:{{ request('waktu') === $time ? '#e11d48' : '#4b5563' }}; font-weight:{{ request('waktu') === $time ? '600' : '400' }};">
                {{ $time }}
            </a>
        @endforeach
    </div>
</form>