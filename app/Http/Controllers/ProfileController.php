<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(string $username): View
    {
        $user = User::where('username', $username)->firstOrFail();
        $reviews = $user->reviews()->with('destination.category')->orderByDesc('helpful_count')->get();

        return view('profiles.show', [
            'profileUser' => $user,
            'reviews' => $reviews,
            'collections' => $user->collections()->withCount('destinations')->get(),
            'categories' => Category::orderBy('position')->get(),
        ]);
    }
}
