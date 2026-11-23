<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function show(string $slug): View
    {
        $destination = Destination::with('category', 'reviews.user')
            ->where('slug', $slug)
            ->firstOrFail();

        $reviews = $destination->reviews()
            ->with('user')
            ->orderByDesc('helpful_count')
            ->get();

        // distribusi bintang utk panel ringkasan
        $dist = collect(range(5, 1))->mapWithKeys(function ($star) use ($reviews) {
            $count = $reviews->filter(fn ($r) => $r->score == $star)->count();
            return [$star => $count];
        });

        return view('destinations.show', [
            'destination' => $destination,
            'categories' => Category::orderBy('position')->get(),
            'reviews' => $reviews,
            'dist' => $dist,
            'topReviews' => $reviews->take(2),
        ]);
    }
}
