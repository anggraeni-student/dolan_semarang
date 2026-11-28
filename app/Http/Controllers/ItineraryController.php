<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ItineraryController extends Controller
{
    // Daftar semua rencana trip milik user
    public function index(): View
    {
        $itineraries = Itinerary::with('items.destination')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('itineraries.index', compact('itineraries'));
    }

    // Simpan rencana trip baru
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'trip_date' => 'nullable|date',
            'description' => 'nullable|string|max:500',
        ]);

        $itinerary = Itinerary::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'trip_date' => $validated['trip_date'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('itineraries.show', $itinerary)
            ->with('success', 'Rencana trip berhasil dibuat! Silakan tambahkan destinasi kunjungan.');
    }

    // Detail jadwal dalam 1 rencana trip
    public function show(Itinerary $itinerary): View
    {
        abort_if($itinerary->user_id !== Auth::id(), 403);

        $itinerary->load('items.destination.category');

        // Ambil destinasi dari wishlist user & semua destinasi untuk pilihan dropdown
        $wishlistedDestinations = Wishlist::with('destination')
            ->where('user_id', Auth::id())
            ->get()
            ->pluck('destination')
            ->filter();

        $allDestinations = Destination::orderBy('name')->get();

        return view('itineraries.show', compact('itinerary', 'wishlistedDestinations', 'allDestinations'));
    }

    // Tambahkan destinasi ke dalam jadwal trip
    public function storeItem(Request $request, Itinerary $itinerary): RedirectResponse
    {
        abort_if($itinerary->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'visit_time' => 'nullable|date_format:H:i',
            'estimated_cost' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        ItineraryItem::create([
            'itinerary_id' => $itinerary->id,
            'destination_id' => $validated['destination_id'],
            'visit_time' => $validated['visit_time'] ?? null,
            'estimated_cost' => $validated['estimated_cost'] ?? 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Destinasi berhasil ditambahkan ke jadwal trip!');
    }

    // Hapus 1 destinasi dari jadwal trip
    public function destroyItem(Itinerary $itinerary, ItineraryItem $item): RedirectResponse
    {
        abort_if($itinerary->user_id !== Auth::id() || $item->itinerary_id !== $itinerary->id, 403);

        $item->delete();

        return back()->with('success', 'Jadwal kunjungan dihapus.');
    }

    // Hapus keseluruhan rencana trip
    public function destroy(Itinerary $itinerary): RedirectResponse
    {
        abort_if($itinerary->user_id !== Auth::id(), 403);

        $itinerary->delete();

        return redirect()->route('itineraries.index')
            ->with('success', 'Rencana trip berhasil dihapus.');
    }
}