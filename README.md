# Dolan Semarang — Review & Rekomendasi Tempat Asyik Dikunjungi

Website komunitas review tempat — bukan cuma tempat wisata, tapi semua tempat asyik dikunjungi: kuliner legendaris, kafe, alun-alun, kampung foto, sampai air terjun. Cakupan **Semarang dan sekitarnya** (Kota & Kab. Semarang, Ungaran, Bandungan, Ambarawa, Kendal, Demak, Salatiga). Layout terinspirasi Female Daily.
Dibangun dengan **Laravel 13 · PHP 8.3+ · Filament v4** (panel admin) · database **SQLite (default, tanpa setup)** atau PostgreSQL/MySQL.

## Setup dari Nol (5 langkah)

```bash
composer install          # atau: unzip lalu langsung jalankan — vendor sudah disertakan
cp .env.example .env      # sesuaikan bila perlu (SQLite sudah default, tidak perlu apa-apa)
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve         # buka http://localhost:8000
```

> Zip ini **sudah menyertakan `vendor/`** — jika Anda memakai zip lengkap, `composer install` bisa dilewati.
> Butuh PHP 8.3+ dengan ekstensi umum (openssl, pdo, mbstring, dll.) — Laravel 13 standar.

## Konfigurasi Database (.env)

Default **SQLite** — langsung jalan tanpa membuat database:

```
DB_CONNECTION=sqlite
# database/database.sqlite dibuat otomatis saat migrate
```

Kalau lebih nyaman pakai PostgreSQL:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=dolan_semarang    # buat dulu: CREATE DATABASE dolan_semarang;
DB_USERNAME=postgres
DB_PASSWORD=secret
```

## Akun Demo (password: `password`)

- **admin@dolan-smg.id — Admin Dolan (akses panel Filament `/admin`)**
- dina@dolan-smg.id — Dina Rahma (Legenda Dolan, 26 review)
- andi@dolan-smg.id — Andi Saputra (Jago Dolan, 22 review)
- putri@dolan-smg.id, bima@dolan-smg.id, rani@dolan-smg.id

## Halaman

| Route | Halaman |
|---|---|
| `/` | Homepage (rekomendasi, panduan editor, komunitas teraktif) |
| `/destinasi/kategori/{slug}` | Grid kategori (Kuliner, Sejarah & Budaya, Nongkrong & Santai, Kafe & Kedai, Alam & Bukit, …) |
| `/destinasi/{slug}` | Detail tempat: rating 4 aspek, review, info praktis |
| `/destinasi/{slug}/review` | Semua review + ringkasan rating + distribusi bintang |
| `/destinasi/{slug}/tulis-review` | Form tulis review (bintang interaktif) |
| `/panduan` + `/panduan/{slug}` | Artikel editorial |
| `/komunitas` | Forum thread + top contributor + lencana |
| `/peta` | Peta Leaflet semua tempat (pusat: Semarang) |
| `/u/{username}` | Profil user: review, koleksi, statistik |
| `/onboarding` | Pilih minat (login dulu) → homepage personal |
| `/masuk`, `/daftar` | Auth |

## Panel Admin — Filament v4 (`/admin`)

Panel back-office untuk mengelola seluruh konten tanpa menyentuh database manual.

| Menu | Resource | Kemampuan |
|---|---|---|
| Dashboard | — | Widget statistik: total tempat, review + rating rata-rata, pengguna, thread |
| Konten → Destinasi | `DestinationResource` | CRUD penuh, thumbnail, filter kategori/unggulan, toggle unggulan, relation manager review |
| Konten → Review | `ReviewResource` | CRUD, skor 4 aspek, filter per destinasi |
| Konten → Artikel Panduan | `ArticleResource` | CRUD, tag, penulis, unggulan |
| Konten → Forum Komunitas | `ThreadResource` | CRUD, jenis tanya/cerita |
| Konten → Kategori | `CategoryResource` | CRUD + jumlah tempat |
| Pengelolaan → Pengguna | `UserResource` | CRUD, toggle akses admin, jumlah review |

- Akses dikontrol kolom `users.is_admin` + `FilamentUser::canAccessPanel()`.
- Warna panel mengikuti brand utama `#DB284E`, label & bahasa Indonesia.
- Login admin: `admin@dolan-smg.id` / `password` (satu guard `web` dengan situs publik).

## Catatan

- 27 gambar tempat di `public/images/` adalah **placeholder dari picsum.photos** (bukan foto asli tempat) — ganti dengan foto sendiri lewat panel admin atau langsung ke folder tersebut dengan nama yang sama.
- Data destinasi (nama, lokasi, HTM, jam buka) disusun sebagai data demo yang wajar untuk Semarang Raya; angka pasti bisa berbeda dengan kondisi terkini — sunting lewat panel admin.
- Seeder membuat data lengkap: 10 kategori, 27 tempat, 5 user + 1 admin, ±80 review, 5 artikel, 3 thread, minat, koleksi. Seeder idempoten — aman dijalankan ulang.
- Struktur DB: categories, destinations, reviews (skor 4 aspek), articles, threads, interests, collections (+pivot).
- Rating agregat = rata-rata 4 aspek (pemandangan/suasana, akses, kebersihan, fasilitas).
- Lencana: Pemula Dolan (≥1) → Rutin Dolan (≥5) → Jago Dolan (≥15) → Legenda Dolan (≥25).
