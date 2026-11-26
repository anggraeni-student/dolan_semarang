<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(): View
    {
        return view('community.index', [
            'threads' => Thread::with('user')->orderByDesc('last_activity_at')->get(),
            'topUsers' => User::withCount('reviews')->orderByDesc('reviews_count')->take(3)->get(),
            'categories' => Category::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:10|max:150',
            'type' => 'required|in:tanya,cerita',
            'body' => 'nullable|string|max:2000',
        ], [
            'title.min' => 'Judul minimal 10 karakter biar jelas.',
        ]);

        Thread::create($validated + [
            'user_id' => $request->user()->id,
            'reply_count' => 0,
            'last_activity_at' => now(),
        ]);

        return redirect()->route('community.index')->with('success', 'Thread baru terbit! Selamat berdiskusi.');
    }
}
