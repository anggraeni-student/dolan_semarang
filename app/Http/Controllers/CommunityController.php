<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use App\Models\ThreadReply;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(): View
    {
        return view('community.index', [
            'threads' => Thread::with('user')->withCount('replies')->orderByDesc('last_activity_at')->get(),
            'topUsers' => User::withCount('reviews')->orderByDesc('reviews_count')->take(3)->get(),
            'categories' => Category::orderBy('position')->get(),
        ]);
    }

    /** Halaman detail thread — sekarang bisa diklik */
    public function show(Thread $thread): View
    {
        $thread->load('user');

        return view('community.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->with('user')->oldest()->get(),
            'topUsers' => User::withCount('reviews')->orderByDesc('reviews_count')->take(3)->get(),
            'categories' => Category::orderBy('position')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|min:10|max:150',
            'type' => 'required|in:tanya,cerita',
            'body' => 'nullable|string|max:2000',
        ], [
            'title.min' => 'Judul minimal 10 karakter biar jelas.',
        ]);

        $thread = Thread::create($validated + [
            'user_id' => $request->user()->id,
            'reply_count' => 0,
            'last_activity_at' => now(),
        ]);

        return redirect()
            ->route('community.show', $thread)
            ->with('success', 'Thread baru terbit! Selamat berdiskusi.');
    }

    /** Balas thread — memperbarui aktivitas & jumlah balasan */
    public function reply(Request $request, Thread $thread): RedirectResponse
    {
        $validated = $request->validate([
            'body' => 'required|string|min:3|max:2000',
        ], [
            'body.required' => 'Tulis balasanmu dulu ya.',
            'body.min' => 'Balasan minimal 3 karakter.',
        ]);

        $thread->replies()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        $thread->update([
            'reply_count' => $thread->replies()->count(),
            'last_activity_at' => now(),
        ]);

        return back()->with('success', 'Balasan terkirim!');
    }
}
