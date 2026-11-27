@props(['item'])

<div class="flex flex-wrap gap-1.5 mt-2">
    @if(!empty($item->best_time))
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-rose-50 text-rose-600 border border-rose-200">
            {{ $item->best_time }}
        </span>
    @endif
    @if(!empty($item->budget_tier))
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
            {{ $item->budget_tier }}
        </span>
    @endif
    @if(!empty($item->area))
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
            {{ $item->area }}
        </span>
    @endif
    @if(!empty($item->opening_hours))
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
            Buka: {{ $item->opening_hours }}
        </span>
    @endif
</div>