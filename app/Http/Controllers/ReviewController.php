<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /** Halaman "Semua Review" komunitas (lintas destinasi) */
    public function all(Request $request): View
    {
        $sort = $request->query('sort', 'helpful');

        $query = Review::with('user', 'destination.category');

        // filter kategori destinasi (opsional)
        $activeCategory = null;
        if ($catSlug = $request->query('kategori')) {
            $activeCategory = Category::where('slug', $catSlug)->first();
            if ($activeCategory) {
                $query->whereHas('destination.category', fn ($q) => $q->where('slug', $catSlug));
            }
        }

        $reviews = match ($sort) {
            'recent' => $query->latest()->get(),
            'rating' => $query->get()->sortByDesc(fn ($r) => $r->score),
            default => $query->orderByDesc('helpful_count')->get(),
        };

        return view('reviews.all', [
            'categories' => Category::orderBy('position')->get(),
            'reviews' => $reviews,
            'sort' => $sort,
            'activeCategory' => $activeCategory,
            'topDestinations' => Destination::with('category', 'reviews')
                ->get()
                ->sortByDesc(fn ($d) => $d->rating)
                ->take(5),
        ]);
    }

    /** Halaman "Semua Review" untuk satu destinasi */
    public function index(string $slug): View
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();
        $sort = request('sort', 'helpful');

        $reviews = $destination->reviews()->with('user');
        $reviews = match ($sort) {
            'recent' => $reviews->latest(),
            'rating' => $reviews->orderByDesc('score_view'),
            default => $reviews->orderByDesc('helpful_count'),
        };
        $reviews = $reviews->get();

        $dist = collect(range(5, 1))->mapWithKeys(function ($star) use ($destination) {
            $count = $destination->reviews->filter(fn ($r) => $r->score == $star)->count();
            return [$star => $count];
        });

        return view('reviews.index', [
            'destination' => $destination,
            'categories' => Category::orderBy('position')->get(),
            'reviews' => $reviews,
            'dist' => $dist,
            'sort' => $sort,
        ]);
    }

    /** Form tulis review */
    public function create(string $slug): View
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();

        return view('reviews.create', [
            'destination' => $destination,
            'categories' => Category::orderBy('position')->get(),
        ]);
    }

    /** Simpan review baru */
    public function store(Request $request, string $slug)
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'score_view' => 'required|integer|min:1|max:5',
            'score_access' => 'required|integer|min:1|max:5',
            'score_cleanliness' => 'required|integer|min:1|max:5',
            'score_facility' => 'required|integer|min:1|max:5',
            'body' => 'required|string|min:20|max:2000',
            'photo_count' => 'nullable|integer|min:0|max:10',
        ], [
            'body.min' => 'Ceritakan setidaknya 20 karakter agar review-mu membantu.',
            'body.required' => 'Ceritakan pengalamanmu dulu ya.',
        ]);

        // satu review per user per destinasi
        if ($destination->reviews()->where('user_id', $request->user()->id)->exists()) {
            return back()->withErrors(['body' => 'Kamu sudah menulis review untuk destinasi ini.']);
        }

        $review = $destination->reviews()->create($validated + [
            'user_id' => $request->user()->id,
            'helpful_count' => 0,
            'visited_at' => now()->toDateString(),
        ]);

        return redirect()
            ->route('destinations.show', $destination->slug)
            ->with('success', 'Review terkirim! Kamu dapat 50 poin. Terima kasih sudah berbagi.');
    }
}
