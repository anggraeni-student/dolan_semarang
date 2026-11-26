<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use Illuminate\View\View;

class MapController extends Controller
{
    public function index(): View
    {
        $destinations = Destination::with('category', 'reviews')->get();

        return view('map.index', [
            'destinations' => $destinations,
            'categories' => Category::orderBy('position')->get(),
        ]);
    }
}
