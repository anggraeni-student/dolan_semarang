<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
<<<<<<< HEAD
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

        // filter HTM (harga tiket masuk)
        $htm = (string) $request->query('htm', '');
        $query->when($htm === 'gratis', fn ($q) => $q->where('htm', 0))
            ->when($htm === 'murah', fn ($q) => $q->whereBetween('htm', [1, 10000]))
            ->when($htm === 'sedang', fn ($q) => $q->whereBetween('htm', [10001, 50000]))
            ->when($htm === 'mahal', fn ($q) => $q->where('htm', '>', 50000));

        $sort = $request->query('sort', 'rating');
        $destinations = match ($sort) {
            'recent' => $query->latest()->get(),
            'review' => $query->get()->sortByDesc(fn ($d) => $d->review_count),
            default => $query->get()->sortByDesc(fn ($d) => [$d->rating, $d->review_count]),
        };

        // filter jam buka (string jam seperti "06.00-19.00" diparsing)
        $jam = (string) $request->query('jam', '');
        if ($jam !== '') {
            $destinations = $destinations->filter(fn ($d) => $this->matchesJamFilter($d, $jam))->values();
        }

        return view('destinations.index', [
            'categories' => Category::orderBy('position')->get(),
            'destinations' => $destinations,
            'sort' => $sort,
            'q' => trim((string) $request->query('q')),
            'htm' => $htm,
            'jam' => $jam,
        ]);
    }

    /** Apakah destinasi cocok dengan filter jam buka (pagi / malam / 24 jam) */
    private function matchesJamFilter(Destination $destination, string $jam): bool
    {
        $span = $this->openingHoursSpan($destination->opening_hours);
        if ($span === null) {
            return false;
        }

        [$open, $close] = $span;

        return match ($jam) {
            'pagi' => $open <= 9.0,          // buka lebih awal / pagi
            'malam' => $close >= 18.0,       // masih buka sampai malam
            '24jam' => $close - $open >= 23.5, // buka (hampir) 24 jam
            default => true,
        };
    }

    /** Parsing "06.00-19.00" → [6.0, 19.0] (jam desimal). Null kalau format tak dikenal. */
    private function openingHoursSpan(?string $str): ?array
    {
        if (!$str) {
            return null;
        }

        if (preg_match('/24\s*jam/i', $str)) {
            return [0.0, 24.0];
        }

        if (preg_match('/(\d{1,2})[.:]?(\d{2})?\s*(?:-|–|—|s\.?d\.?|sampai)\s*(\d{1,2})[.:]?(\d{2})?/i', $str, $m)) {
            $open = (int) $m[1] + (int) ($m[2] ?? 0) / 60;
            $close = (int) $m[3] + (int) ($m[4] ?? 0) / 60;
            if ($open < 0 || $open > 24 || $close < 0 || $close > 24) {
                return null;
            }
            if ($close <= $open) {
                $close += 24; // buka melewati tengah malam
            }
            return [$open, $close];
        }

        return null;
    }

    public function show(string $slug): View
    {
        $destination = Destination::with('category', 'reviews.user', 'creator')
            ->where('slug', $slug)
            ->firstOrFail();
=======

class DestinationController extends Controller
{
    public array $budgetOptions = [
        'Gratis / Parkir Aja',
        'Di Bawah Rp 25.000',
        'Rp 25.000 – Rp 75.000',
        'Di Atas Rp 75.000',
    ];

    public array $areaOptions = [
        'Semarang Tengah / Kota Lama',
        'Semarang Barat',
        'Banyumanik / Tembalang',
        'Semarang Selatan / Gombel',
        'Ungaran / Bandungan / Sekitarnya',
    ];

    public array $timeOptions = [
        'Pagi / Sunrise',
        'Sore / Sunset',
        'Malam / Kulineran',
        'Anti-Hujan / Indoor',
    ];

    public function index(Request $request)
    {
        $query = Destination::with('reviews');
>>>>>>> d86df827b9e5ffa48123c8563af38becbc92aa26

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('address', 'ilike', '%' . $request->search . '%')
                  ->orWhere('description', 'ilike', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('budget')) {
            $query->where('budget_tier', $request->budget);
        }

        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        if ($request->filled('waktu')) {
            $query->where('best_time', $request->waktu);
        }

        $destinations = $query->latest()->get();
        $popularDestinations = Destination::with('reviews')->take(4)->get();

        return view('home', [
            'destinations' => $destinations,
            'popularDestinations' => $popularDestinations,
            'budgetOptions' => $this->budgetOptions,
            'areaOptions' => $this->areaOptions,
            'timeOptions' => $this->timeOptions,
        ]);
    }

<<<<<<< HEAD
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
=======
    public function show($slugOrId)
    {
        $destination = Destination::with(['reviews' => function ($q) {
            $q->latest();
        }])
            ->where('slug', $slugOrId)
            ->orWhere('id', is_numeric($slugOrId) ? (int) $slugOrId : 0)
            ->firstOrFail();

        $avgRating = $destination->reviews->avg('rating') ?? 0;

        return view('destinasi.show', [
            'destination' => $destination,
            'avgRating' => $avgRating,
            'timeOptions' => $this->timeOptions,
        ]);
    }

    public function storeReview(Request $request, $slugOrId)
    {
        $destination = Destination::where('slug', $slugOrId)
            ->orWhere('id', is_numeric($slugOrId) ? (int) $slugOrId : 0)
            ->firstOrFail();

        $validated = $request->validate([
            'user_name' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'visit_time_tag' => 'nullable|string|max:100',
            'comment' => 'required|string',
            'photo_url' => 'nullable|url',
        ]);

        $validated['destination_id'] = $destination->id;
        Review::create($validated);

        return back()->with('success', 'Ulasanmu berhasil dikirim!');
    }
}
>>>>>>> d86df827b9e5ffa48123c8563af38becbc92aa26
