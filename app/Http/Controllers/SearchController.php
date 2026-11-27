<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /** Pencarian global: destinasi, artikel, dan thread komunitas */
    public function __invoke(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $results = collect();

        if ($q !== '') {
            $like = "%{$q}%";

            $destinations = Destination::with('category', 'reviews')
                ->where(fn ($w) => $w
                    ->where('name', 'like', $like)
                    ->orWhere('location', 'like', $like)
                    ->orWhere('description', 'like', $like))
                ->get()
                ->sortByDesc(fn ($d) => [$d->rating, $d->review_count]);

            $articles = Article::with('author')
                ->where(fn ($w) => $w
                    ->where('title', 'like', $like)
                    ->orWhere('excerpt', 'like', $like))
                ->latest()
                ->get();

            $threads = Thread::with('user')
                ->where(fn ($w) => $w
                    ->where('title', 'like', $like)
                    ->orWhere('body', 'like', $like))
                ->orderByDesc('last_activity_at')
                ->get();

            $results = collect([
                ['key' => 'destinasi', 'label' => 'Tempat', 'icon' => 'place', 'items' => $destinations],
                ['key' => 'artikel', 'label' => 'Artikel Panduan', 'icon' => 'menu_book', 'items' => $articles],
                ['key' => 'thread', 'label' => 'Diskusi Komunitas', 'icon' => 'forum', 'items' => $threads],
            ]);
        }

        return view('search.results', [
            'q' => $q,
            'results' => $results,
            'total' => $results->sum(fn ($group) => $group['items']->count()),
            'categories' => Category::orderBy('position')->get(),
        ]);
    }
}
