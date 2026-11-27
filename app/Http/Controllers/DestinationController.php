<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public array $budgetOptions = [
        'Gratis / Parkir Aja',
        'Di Bawah Rp 25.000',
        'Rp 25.000 – Rp 75.000',
        'Di Atas Rp 75.000',
    ];

    public array $areaOptions = [
        'Semarang Tengah / Kota Lama',
        'Semarang Barat',
        'Banyumanik / Tembalang',
        'Semarang Selatan / Gombel',
        'Ungaran / Bandungan / Sekitarnya',
    ];

    public array $timeOptions = [
        'Pagi / Sunrise',
        'Sore / Sunset',
        'Malam / Kulineran',
        'Anti-Hujan / Indoor',
    ];

    public function index(Request $request)
    {
        $query = Destination::with('reviews');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('address', 'ilike', '%' . $request->search . '%')
                  ->orWhere('description', 'ilike', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('budget')) {
            $query->where('budget_tier', $request->budget);
        }

        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        if ($request->filled('waktu')) {
            $query->where('best_time', $request->waktu);
        }

        $destinations = $query->latest()->get();
        $popularDestinations = Destination::with('reviews')->take(4)->get();

        return view('home', [
            'destinations' => $destinations,
            'popularDestinations' => $popularDestinations,
            'budgetOptions' => $this->budgetOptions,
            'areaOptions' => $this->areaOptions,
            'timeOptions' => $this->timeOptions,
        ]);
    }

    public function show($slugOrId)
    {
        $destination = Destination::with(['reviews' => function ($q) {
            $q->latest();
        }])
            ->where('slug', $slugOrId)
            ->orWhere('id', is_numeric($slugOrId) ? (int) $slugOrId : 0)
            ->firstOrFail();

        $avgRating = $destination->reviews->avg('rating') ?? 0;

        return view('destinasi.show', [
            'destination' => $destination,
            'avgRating' => $avgRating,
            'timeOptions' => $this->timeOptions,
        ]);
    }

    public function storeReview(Request $request, $slugOrId)
    {
        $destination = Destination::where('slug', $slugOrId)
            ->orWhere('id', is_numeric($slugOrId) ? (int) $slugOrId : 0)
            ->firstOrFail();

        $validated = $request->validate([
            'user_name' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'visit_time_tag' => 'nullable|string|max:100',
            'comment' => 'required|string',
            'photo_url' => 'nullable|url',
        ]);

        $validated['destination_id'] = $destination->id;
        Review::create($validated);

        return back()->with('success', 'Ulasanmu berhasil dikirim!');
    }
}