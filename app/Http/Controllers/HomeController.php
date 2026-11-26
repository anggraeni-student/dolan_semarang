<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $destinations = Destination::with('category', 'reviews')
            ->featured()
            ->get()
            ->sortByDesc(fn ($d) => $d->rating);

        // Rekomendasi personal jika user sudah login & punya minat
        $forYou = null;
        if ($request->user() && $request->user()->interests->isNotEmpty()) {
            $slugs = $request->user()->interests->pluck('slug');
            $forYou = Destination::with('category', 'reviews')
                ->whereHas('category', fn ($q) => $q->whereIn('slug', $slugs))
                ->get()
                ->sortByDesc(fn ($d) => $d->rating)
                ->take(3);
        }

        return view('home', [
            'categories' => Category::orderBy('position')->get(),
            'destinations' => $destinations->take(6),
            'forYou' => $forYou,
            'articles' => Article::with('author')->latest()->take(2)->get(),
            'topUsers' => User::withCount('reviews')->orderByDesc('reviews_count')->take(3)->get(),
        ]);
    }
}
