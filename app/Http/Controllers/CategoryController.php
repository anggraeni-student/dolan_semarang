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
        $query = Destination::with('category', 'reviews')->where('category_id', $category->id);

        // sub-filter sederhana (pantai/gunung/air terjun/pulau) seperti di prototype
        if ($sub = $request->query('filter')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $sub));
        }

        $destinations = $query->get()
            ->sortByDesc(fn ($d) => [$d->rating, $d->review_count]);

        return view('categories.show', [
            'category' => $category,
            'categories' => Category::orderBy('position')->get(),
            'destinations' => $destinations,
            'activeFilter' => $request->query('filter'),
        ]);
    }
}
