<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Interest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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
            'interests' => Interest::orderBy('id')->get(),
            'isOwnProfile' => auth()->check() && auth()->user()->id === $user->id,
        ]);
    }

    /** Update profil sendiri: data diri + preferensi minat */
    public function update(Request $request, string $username): RedirectResponse
    {
        $user = User::where('username', $username)->firstOrFail();

        // hanya pemilik profil yang boleh mengubah
        abort_unless($request->user() && $request->user()->id === $user->id, 403);

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'bio' => 'nullable|string|max:255',
            'provinces_visited' => 'nullable|integer|min:0|max:1000',
            'interests' => 'nullable|array|max:10',
            'interests.*' => 'exists:interests,id',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?? '',
            'provinces_visited' => $validated['provinces_visited'] ?? 0,
        ]);

        // preferensi minat: kosong = hapus semua
        $user->interests()->sync($validated['interests'] ?? []);

        return back()->with('success', 'Profil & preferensimu sudah diperbarui.');
    }
}
