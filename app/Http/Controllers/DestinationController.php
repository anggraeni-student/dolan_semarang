<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Destination::query()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $destinations = $query->latest()->get();
        $categories = Destination::select('category')->distinct()->pluck('category');

        return view('wisata.index', compact('destinations', 'categories'));
    }

    public function create(): View
    {
        return view('wisata.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'price_range' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('destinations', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        Destination::create($data);

        return redirect()
            ->route('wisata.index')
            ->with('success', "Destinasi {$data['name']} berhasil ditambahkan!");
    }

    public function show(Destination $destination): View
    {
        $destination->load(['reviews' => fn ($query) => $query->latest()]);
        return view('wisata.show', compact('destination'));
    }

    public function edit(Destination $destination): View
    {
        return view('wisata.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'price_range' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('destinations', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $destination->update($data);

        return redirect()
            ->route('wisata.show', $destination)
            ->with('success', "Destinasi {$data['name']} berhasil diperbarui!");
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $nama = $destination->name;
        $destination->delete();

        return redirect()
            ->route('wisata.index')
            ->with('success', "Destinasi {$nama} berhasil dihapus.");
    }

    public function storeReview(Request $request, Destination $destination): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['user_name'] = $request->user()->name;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('reviews', 'public');
            $data['photo_url'] = '/storage/' . $path;
        }

        $destination->reviews()->create($data);

        if ($request->expectsJson()) {
            return $this->reviewsJson($destination);
        }

        return redirect()
            ->route('wisata.show', $destination)
            ->with('success', 'Terima kasih! Ulasan kamu berhasil dikirim.');
    }

    public function reviewsJson(Destination $destination): JsonResponse
    {
        $reviews = $destination->reviews()->latest()->get();
        $total = $reviews->count();
        $avg = $total > 0 ? $reviews->avg('rating') : 0;

        $breakdown = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $reviews->where('rating', $star)->count();
            $breakdown[] = [
                'star' => $star,
                'percent' => $total > 0 ? round(($count / $total) * 100) : 0,
            ];
        }

        return response()->json([
            'total_reviews' => $total,
            'avg_rating' => number_format($avg, 1),
            'avg_round' => (int) round($avg),
            'breakdown' => $breakdown,
            'reviews' => $reviews->map(fn ($r) => [
                'id' => $r->id,
                'user_name' => $r->user_name,
                'initials' => strtoupper(substr($r->user_name, 0, 2)),
                'rating' => $r->rating,
                'comment' => $r->comment,
                'photo_url' => $r->photo_url,
                'time_ago' => $r->created_at->diffForHumans(),
            ]),
        ]);
    }

    public function reviews(): View
    {
        $reviews = Review::with('destination')->latest()->get();
        $totalReviews = $reviews->count();
        $avgRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;

        $topContributors = Review::selectRaw('user_name, COUNT(*) as total_reviews')
            ->groupBy('user_name')
            ->orderByDesc('total_reviews')
            ->take(5)
            ->get();

        return view('reviews.index', compact('reviews', 'totalReviews', 'avgRating', 'topContributors'));
    }
}