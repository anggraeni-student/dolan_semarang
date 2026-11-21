<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(): View
    {
        $destinations = Destination::latest()->get();
        return view('wisata.index', compact('destinations'));
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

    public function storeReview(Request $request, Destination $destination): RedirectResponse
    {
        $data = $request->validate([
            'reviewer_name' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string'],
        ]);

        $destination->reviews()->create($data);

        return redirect()
            ->route('wisata.show', $destination)
            ->with('success', 'Terima kasih! Ulasan kamu berhasil dikirim.');
    }
}