<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Destination;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $hasFilter = $request->filled('budget') || $request->filled('area') || $request->filled('waktu');

        $relations = [];
        if (method_exists(Destination::class, 'category')) {
            $relations[] = 'category';
        }
        if (method_exists(Destination::class, 'reviews')) {
            $relations[] = 'reviews';
        }

        try {
            $destinations = Destination::with($relations)->get();
        } catch (\Throwable $e) {
            $destinations = new Collection();
        }

        if ($request->filled('budget')) {
            $destinations = $destinations->filter(
                fn ($d) => ($d->budget_tier ?? null) === $request->budget
            );
        }

        if ($request->filled('area')) {
            $destinations = $destinations->filter(
                fn ($d) => ($d->area ?? null) === $request->area
            );
        }

        if ($request->filled('waktu')) {
            $destinations = $destinations->filter(
                fn ($d) => ($d->best_time ?? null) === $request->waktu
            );
        }

        $destinations = $destinations->sortByDesc(fn ($d) => $d->rating ?? 0)->values();

        if (!$hasFilter) {
            $destinations = $destinations->take(6);
        }

        // Rekomendasi personal jika user sudah login & punya minat
        $forYou = null;
        try {
            if ($request->user() && $request->user()->interests->isNotEmpty()) {
                $slugs = $request->user()->interests->pluck('slug');
                $forYou = Destination::with($relations)
                    ->whereHas('category', fn ($q) => $q->whereIn('slug', $slugs))
                    ->get()
                    ->sortByDesc(fn ($d) => $d->rating ?? 0)
                    ->take(3);
            }
        } catch (\Throwable $e) {
            $forYou = null;
        }

        return view('home', [
            'categories' => Category::orderBy('position')->get(),
            'destinations' => $destinations,
            'forYou' => $forYou,
            'articles' => Article::with('author')->latest()->take(2)->get(),
            'topUsers' => User::withCount('reviews')->orderByDesc('reviews_count')->take(3)->get(),
        ]);
    }
}