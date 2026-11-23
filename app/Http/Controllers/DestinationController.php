<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Destination::query()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $destinations = $query->latest()->get();
        $categories = Destination::select('category')->distinct()->pluck('category');

        return view('wisata.index', compact('destinations', 'categories'));
    }

    public function create(): View
    {
        return view('wisata.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'price_range' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        Destination::create($data);

        return redirect()
            ->route('wisata.index')
            ->with('success', "Destinasi {$data['name']} berhasil ditambahkan!");
    }

    public function show(Destination $destination): View
    {
        $destination->load(['reviews' => fn ($query) => $query->latest()]);
        return view('wisata.show', compact('destination'));
    }

    public function edit(Destination $destination): View
    {
        return view('wisata.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'price_range' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $destination->update($data);

        return redirect()
            ->route('wisata.show', $destination)
            ->with('success', "Destinasi {$data['name']} berhasil diperbarui!");
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $nama = $destination->name;
        $destination->delete();

        return redirect()
            ->route('wisata.index')
            ->with('success', "Destinasi {$nama} berhasil dihapus.");
    }

    public function storeReview(Request $request, Destination $destination): RedirectResponse
    {
        $data = $request->validate([
            'user_name' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string'],
        ]);

        $destination->reviews()->create($data);

        return redirect()
            ->route('wisata.show', $destination)
            ->with('success', 'Terima kasih! Ulasan kamu berhasil dikirim.');
    }
}