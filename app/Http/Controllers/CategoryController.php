<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Satu tempat, satu filter: halaman ini SUDAH difilter per kategori
        // (strip kategori di layout menangani pergantian kategori), jadi tidak
        // ada lagi sub-filter chips duplikat di bawah judul.
        $destinations = Destination::with('category', 'reviews')
            ->where('category_id', $category->id)
            ->get()
            ->sortByDesc(fn ($d) => [$d->rating, $d->review_count]);

        return view('categories.show', [
            'category' => $category,
            'categories' => Category::orderBy('position')->get(),
            'destinations' => $destinations,
        ]);
    }
}
