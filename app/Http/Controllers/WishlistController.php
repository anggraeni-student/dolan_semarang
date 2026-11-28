<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    // Menampilkan semua destinasi yang disimpan user
    public function index(): View
    {
        $wishlists = Wishlist::with('destination.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    // Simpan atau hapus destinasi dari wishlist (Toggle)
    public function toggle(Destination $destination): RedirectResponse
    {
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('destination_id', $destination->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', $destination->name . ' dihapus dari Wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'destination_id' => $destination->id,
        ]);

        return back()->with('success', $destination->name . ' berhasil disimpan ke Wishlist!');
    }
}