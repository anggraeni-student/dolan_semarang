<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DestinationController extends Controller
{
    /** Halaman "Semua Destinasi" — sebelum difilter per kategori */
    public function index(Request $request): View
    {
        $query = Destination::with('category', 'reviews');

        // pencarian sederhana di halaman ini
        if ($search = trim((string) $request->query('q'))) {
            $like = "%{$search}%";
            $query->where(fn ($w) => $w
                ->where('name', 'like', $like)
                ->orWhere('location', 'like', $like)
                ->orWhere('description', 'like', $like));
        }

        $destinations = match ($request->query('sort', 'rating')) {
            'recent' => $query->latest()->get(),
            'review' => $query->get()->sortByDesc(fn ($d) => $d->review_count),
            default => $query->get()->sortByDesc(fn ($d) => [$d->rating, $d->review_count]),
        };

        return view('destinations.index', [
            'categories' => Category::orderBy('position')->get(),
            'destinations' => $destinations,
            'sort' => $request->query('sort', 'rating'),
            'q' => trim((string) $request->query('q')),
        ]);
    }

    public function show(string $slug): View
    {
        $destination = Destination::with('category', 'reviews.user', 'creator')
            ->where('slug', $slug)
            ->firstOrFail();

        $reviews = $destination->reviews()
            ->with('user')
            ->orderByDesc('helpful_count')
            ->get();

        // distribusi bintang utk panel ringkasan
        $dist = collect(range(5, 1))->mapWithKeys(function ($star) use ($reviews) {
            $count = $reviews->filter(fn ($r) => $r->score == $star)->count();
            return [$star => $count];
        });

        return view('destinations.show', [
            'destination' => $destination,
            'categories' => Category::orderBy('position')->get(),
            'reviews' => $reviews,
            'dist' => $dist,
            'topReviews' => $reviews->take(2),
        ]);
    }

    /** Form tambah destinasi oleh user (komunitas, bukan hanya admin) */
    public function create(): View
    {
        return view('destinations.create', [
            'categories' => Category::orderBy('position')->get(),
            'imageFiles' => $this->availableImages(),
        ]);
    }

    /** Simpan destinasi baru dari user */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:150',
            'description' => 'required|string|min:20|max:2000',
            'hero_image' => 'nullable|string|max:150',
            'custom_image' => 'nullable|string|max:200|regex:/^images\/[A-Za-z0-9_.\/-]+$/',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'htm' => 'nullable|integer|min:0|max:100000000',
            'opening_hours' => 'nullable|string|max:100',
            'best_time' => 'nullable|string|max:100',
            'access_info' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama tempat wajib diisi.',
            'category_id.required' => 'Pilih kategori tempatnya dulu ya.',
            'location.required' => 'Lokasi wajib diisi (mis. Kota Semarang).',
            'description.min' => 'Deskripsi minimal 20 karakter agar membantu traveler lain.',
            'custom_image.regex' => 'Format path gambar: images/nama-file.jpg',
        ]);

        // gambar hero: path custom menang, kalau tidak pakai file yang tersedia
        $hero = $validated['custom_image']
            ?? ($validated['hero_image'] ? 'images/' . $validated['hero_image'] : null)
            ?? 'images/kota-lama.jpg';

        // slug unik dari nama tempat
        $base = Str::slug($validated['name']) ?: 'tempat-' . now()->format('His');
        $slug = $base;
        $i = 2;
        while (Destination::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        $destination = Destination::create($validated + [
            'slug' => $slug,
            'hero_image' => $hero,
            'htm' => $validated['htm'] ?? 0,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'opening_hours' => $validated['opening_hours'] ?? '–',
            'best_time' => $validated['best_time'] ?? '–',
            'access_info' => $validated['access_info'] ?? '–',
            'is_featured' => false, // unggulan di beranda tetap dikontrol admin
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('destinations.show', $destination->slug)
            ->with('success', 'Tempat baru berhasil ditambahkan! Terima kasih sudah memperkaya peta Dolan Semarang.');
    }

    /** Daftar file gambar di public/images untuk pilihan hero */
    private function availableImages(): array
    {
        $files = [];
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            $files = array_merge($files, glob(public_path("images/*.{$ext}")) ?: []);
        }
        sort($files);

        return array_map(fn ($path) => basename($path), $files);
    }
}
