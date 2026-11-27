<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Destination;
use App\Models\Interest;
use App\Models\Review;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== KATEGORI (nav atas) — kriteria longgar: tempat asyik dikunjungi =====
        $categories = [
            ['slug' => 'kuliner', 'name' => 'Kuliner', 'icon' => 'restaurant', 'position' => 1],
            ['slug' => 'sejarah', 'name' => 'Sejarah & Budaya', 'icon' => 'account_balance', 'position' => 2],
            ['slug' => 'nongkrong', 'name' => 'Nongkrong & Santai', 'icon' => 'storefront', 'position' => 3],
            ['slug' => 'kafe', 'name' => 'Kafe & Kedai', 'icon' => 'local_cafe', 'position' => 4],
            ['slug' => 'alam', 'name' => 'Alam & Bukit', 'icon' => 'landscape', 'position' => 5],
            ['slug' => 'belanja', 'name' => 'Belanja & Pasar', 'icon' => 'shopping_bag', 'position' => 6],
            ['slug' => 'keluarga', 'name' => 'Keluarga', 'icon' => 'family_restroom', 'position' => 7],
            ['slug' => 'seni', 'name' => 'Seni & Fotogenik', 'icon' => 'palette', 'position' => 8],
            ['slug' => 'religi', 'name' => 'Religi', 'icon' => 'mosque', 'position' => 9],
            ['slug' => 'air', 'name' => 'Air & Wahana', 'icon' => 'pool', 'position' => 10],
        ];
        foreach ($categories as $c) {
            Category::updateOrCreate(['slug' => $c['slug']], $c);
        }

        // ===== USERS =====
        $users = [
            ['name' => 'Dina Rahma', 'username' => 'dina-rahma', 'email' => 'dina@dolan-smg.id',
             'bio' => 'Warga Semarang yang hobi kulineran dan hunting spot estetik.',
             'avatar_initials' => 'DR', 'provinces_visited' => 0, 'password' => 'password'],
            ['name' => 'Andi Saputra', 'username' => 'andi-saputra', 'email' => 'andi@dolan-smg.id',
             'bio' => 'Penggemar kopi dan sejarah kota. Akhir pekan harus keluar rumah.',
             'avatar_initials' => 'AS', 'provinces_visited' => 0, 'password' => 'password'],
            ['name' => 'Putri Puspita', 'username' => 'putri-puspita', 'email' => 'putri@dolan-smg.id',
             'bio' => 'Pemburu cafe hopping & tempat foto estetik se-Semarang Raya.',
             'avatar_initials' => 'PP', 'provinces_visited' => 0, 'password' => 'password'],
            ['name' => 'Bima Jaya', 'username' => 'bima-jaya', 'email' => 'bima@dolan-smg.id',
             'bio' => 'Suka gunung dan air terjun. Weekend warrior sejati.',
             'avatar_initials' => 'BJ', 'provinces_visited' => 0, 'password' => 'password'],
            ['name' => 'Rani Wijaya', 'username' => 'rani-wijaya', 'email' => 'rani@dolan-smg.id',
             'bio' => 'Kontributor editorial Dolan Semarang. Penulis itinerary keluarga.',
             'avatar_initials' => 'RW', 'provinces_visited' => 0, 'password' => 'password'],
        ];
        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }

        // ===== ADMIN (panel Filament /admin) =====
        User::updateOrCreate(
            ['email' => 'admin@dolan-smg.id'],
            [
                'name' => 'Admin Dolan',
                'username' => 'admin-dolan',
                'bio' => 'Administrator panel Dolan Semarang.',
                'avatar_initials' => 'AD',
                'provinces_visited' => 0,
                'password' => 'password',
                'is_admin' => true,
            ],
        );
        $dina = User::where('email', 'dina@dolan-smg.id')->first();
        $andi = User::where('email', 'andi@dolan-smg.id')->first();
        $putri = User::where('email', 'putri@dolan-smg.id')->first();
        $bima = User::where('email', 'bima@dolan-smg.id')->first();
        $rani = User::where('email', 'rani@dolan-smg.id')->first();

        // ===== DESTINASI (Semarang & sekitarnya: Ungaran, Bandungan, Ambarawa, Kendal, Demak, Salatiga) =====
        $destinations = [
            // --- Kota Semarang ---
            ['slug' => 'kota-lama', 'name' => 'Kota Lama', 'category' => 'sejarah',
             'location' => 'Semarang Tengah, Kota Semarang',
             'description' => 'Kawasan pusat kota era kolonial dengan gedung-gedung tua bergaya art deco. Asyik dijelajahi sore sambil foto-foto; tiap sudut punya cerita.',
             'hero_image' => 'images/kota-lama.jpg', 'lat' => -6.9663, 'lng' => 110.4259,
             'htm' => 0, 'opening_hours' => '24 jam (ramai 16.00–20.00)', 'best_time' => 'Sore, golden hour',
             'access_info' => '15 menit dari Tugu Muda; parkir memadai di sekitar Gereja Blenduk.',
             'is_featured' => true],
            ['slug' => 'lawang-sewu', 'name' => 'Lawang Sewu', 'category' => 'sejarah',
             'location' => 'Semarang Tengah, Kota Semarang',
             'description' => 'Gedung bersejarah peninggalan 1907 yang berarti "seribu pintu". Ikon kota Semarang, terkenal akan arsitektur dan kisah mistisnya.',
             'hero_image' => 'images/lawang-sewu.jpg', 'lat' => -6.9840, 'lng' => 110.4085,
             'htm' => 25000, 'opening_hours' => '07.00–21.00', 'best_time' => 'Pagi atau sore',
             'access_info' => 'Di dekat Tugu Muda, mudah dijangkau Trans Semarang.',
             'is_featured' => true],
            ['slug' => 'sam-poo-kong', 'name' => 'Kelenteng Sam Poo Kong', 'category' => 'religi',
             'location' => 'Simolawang, Kota Semarang',
             'description' => 'Kelenteng besar dengan arsitektur merah megah di gua batu, jejak pelayaran Laksamana Cheng Ho. Ramai dikunjungi lintas agama.',
             'hero_image' => 'images/sam-poo-kong.jpg', 'lat' => -6.9890, 'lng' => 110.4470,
             'htm' => 25000, 'opening_hours' => '08.00–20.00', 'best_time' => 'Sore hingga malam',
             'access_info' => '15 menit dari Kota Lama; parkir luas di kompleks.',
             'is_featured' => true],
            ['slug' => 'simpang-lima', 'name' => 'Simpang Lima', 'category' => 'nongkrong',
             'location' => 'Semarang Selatan, Kota Semarang',
             'description' => 'Alun-alun kota tempat warga berkumpul tiap malam — street food, anak sepatu roda, dan suasana khas Semarang.',
             'hero_image' => 'images/simpang-lima.jpg', 'lat' => -6.9930, 'lng' => 110.4170,
             'htm' => 0, 'opening_hours' => '24 jam (meriah 18.00–23.00)', 'best_time' => 'Malam akhir pekan',
             'access_info' => 'Lokasi sentral, mudah dijangkau dari semua penjuru kota.',
             'is_featured' => true],
            ['slug' => 'lumpia-gang-lombok', 'name' => 'Lumpia Gang Lombok', 'category' => 'kuliner',
             'location' => 'Pecinan, Kota Semarang',
             'description' => 'Lumpia legendaris yang sudah dijual sejak lama di Gang Lombok — kuliner wajib yang bikin orang balik lagi ke Semarang.',
             'hero_image' => 'images/lumpia.jpg', 'lat' => -6.9730, 'lng' => 110.4270,
             'htm' => 0, 'opening_hours' => '08.00–21.00', 'best_time' => 'Sore, masih panas',
             'access_info' => 'Di kawasan Pecinan; masuk lewat Jl. Gang Lombok, parkir motor paling mudah.',
             'is_featured' => true],
            ['slug' => 'toko-oen', 'name' => 'Toko Oen', 'category' => 'kuliner',
             'location' => 'Semarang Tengah, Kota Semarang',
             'description' => 'Restoran es krim dan masakan Eropa berdiri sejak 1936 — nuansa jadul, pelayan berdinas lengkap, rasa nostalgia di setiap suapan.',
             'hero_image' => 'images/toko-oen.jpg', 'lat' => -6.9710, 'lng' => 110.4230,
             'htm' => 0, 'opening_hours' => '11.00–21.00', 'best_time' => 'Sore hari',
             'access_info' => 'Di Jl. Pemuda, sebelah kompleks Pasar Johar.',
             'is_featured' => true],
            ['slug' => 'pasar-johar', 'name' => 'Pasar Johar', 'category' => 'belanja',
             'location' => 'Semarang Tengah, Kota Semarang',
             'description' => 'Pasar legendaris bertingkat dengan bangunan bergaya art deco karya Thomas Karsten — belanja murah, jajanan, dan oleh-oleh.',
             'hero_image' => 'images/pasar-johar.jpg', 'lat' => -6.9705, 'lng' => 110.4255,
             'htm' => 0, 'opening_hours' => '05.00–17.00', 'best_time' => 'Pagi, barang lengkap',
             'access_info' => 'Bersebelahan dengan Toko Oen; dekat halte Trans Semarang.',
             'is_featured' => false],
            ['slug' => 'kampung-pelangi', 'name' => 'Kampung Pelangi', 'category' => 'seni',
             'location' => 'Randusari, Kota Semarang',
             'description' => 'Kampung wisata dengan tangga pelangi ikonik dan mural warna-warni. Spot foto favorit yang mengubah kampung jadi destinasi.',
             'hero_image' => 'images/kampung-pelangi.jpg', 'lat' => -6.9940, 'lng' => 110.4290,
             'htm' => 0, 'opening_hours' => '24 jam', 'best_time' => 'Pagi atau sore',
             'access_info' => '10 menit dari Simpang Lima; kampung sempit, parkir motor lebih leluasa.',
             'is_featured' => true],
            ['slug' => 'masjid-agung-jawa-tengah', 'name' => 'Masjid Agung Jawa Tengah', 'category' => 'religi',
             'location' => 'Sambirejo, Kota Semarang',
             'description' => 'Masjid raya dengan arsitektur Jawa, Arab, dan Romawi lengkap dengan menara 99 meter. Wisata religi yang nyaman untuk keluarga.',
             'hero_image' => 'images/masjid-agung.jpg', 'lat' => -6.9950, 'lng' => 110.4440,
             'htm' => 0, 'opening_hours' => '04.00–21.00', 'best_time' => 'Sore, naik menara',
             'access_info' => 'Di ujung selatan kota, 20 menit dari Kota Lama.',
             'is_featured' => false],
            ['slug' => 'pantai-marina', 'name' => 'Pantai Marina', 'category' => 'air',
             'location' => 'Tugu, Kota Semarang',
             'description' => 'Pantai buatan di utara kota dengan area taman bermain dan kuliner seafood. Pilihan santai tanpa keluar jauh dari kota.',
             'hero_image' => 'images/pantai-marina.jpg', 'lat' => -6.9250, 'lng' => 110.4110,
             'htm' => 15000, 'opening_hours' => '07.00–22.00', 'best_time' => 'Sore',
             'access_info' => '25 menit dari pusat kota arah pelabuhan.',
             'is_featured' => false],
            ['slug' => 'pecinan-semarang', 'name' => 'Kawasan Pecinan', 'category' => 'kuliner',
             'location' => 'Semarang Tengah, Kota Semarang',
             'description' => 'Kawasan Pecinan dengan deretan toko kue, kelenteng, dan jajanan malam. Surga kuliner dari pagi sampai larut.',
             'hero_image' => 'images/pecinan.jpg', 'lat' => -6.9720, 'lng' => 110.4260,
             'htm' => 0, 'opening_hours' => '07.00–22.00', 'best_time' => 'Malam',
             'access_info' => '10 menit jalan kaki dari Pasar Johar.',
             'is_featured' => true],
            ['slug' => 'bundaran-bronjong', 'name' => 'Bundaran Bronjong', 'category' => 'seni',
             'location' => 'Tugu, Kota Semarang',
             'description' => 'Bundaran ikonik berupa susunan bronjong (kawat batu) yang jadi landmark kawasan Marina — favorit anak muda untuk berfoto sore.',
             'hero_image' => 'images/bronjong.jpg', 'lat' => -6.9280, 'lng' => 110.4120,
             'htm' => 0, 'opening_hours' => '24 jam', 'best_time' => 'Sore golden hour',
             'access_info' => 'Tepat di kawasan Pantai Marina.',
             'is_featured' => false],
            ['slug' => 'kelenteng-tay-kak-sie', 'name' => 'Kelenteng Tay Kak Sie', 'category' => 'religi',
             'location' => 'Pecinan, Kota Semarang',
             'description' => 'Kelenteng tua di Pecinan dengan arsitektur Tiongkok klasik. Tenang, indah, dan jadi pusat perayaan Cap Go Meh meriah.',
             'hero_image' => 'images/tay-kak-sie.jpg', 'lat' => -6.9740, 'lng' => 110.4270,
             'htm' => 0, 'opening_hours' => '06.00–20.00', 'best_time' => 'Pagi',
             'access_info' => 'Di tengah kawasan Pecinan, mudah dijangkau jalan kaki.',
             'is_featured' => false],
            ['slug' => 'spiegel-cafe', 'name' => 'Spiegel Bar & Bistro', 'category' => 'kafe',
             'location' => 'Kota Lama, Kota Semarang',
             'description' => 'Kafe dan bistro di bangunan tua Kota Lama — kopi, hidangan barat, dan musik live di sore hari; pintu masuknya ikonik.',
             'hero_image' => 'images/spiegel.jpg', 'lat' => -6.9655, 'lng' => 110.4250,
             'htm' => 0, 'opening_hours' => '11.00–24.00', 'best_time' => 'Sore hingga malam',
             'access_info' => 'Tepat di kawasan Kota Lama, seberang Gereja Blenduk.',
             'is_featured' => true],
            ['slug' => 'kopi-street-sampangan', 'name' => 'Kopi Street Sampangan', 'category' => 'kafe',
             'location' => 'Sampangan, Kota Semarang',
             'description' => 'Deretan kedai kopi dalam satu kawasan — dari kopi susu gula aren sampai manual brew, pilihan untuk penikmat kopi semua level.',
             'hero_image' => 'images/kopi-street.jpg', 'lat' => -6.9860, 'lng' => 110.4390,
             'htm' => 0, 'opening_hours' => '15.00–24.00 (tiap kedai beda)', 'best_time' => 'Malam',
             'access_info' => '15 menit dari Kota Lama, arah timur kota.',
             'is_featured' => true],
            ['slug' => 'hutan-kota-srigunting', 'name' => 'Hutan Kota Srigunting', 'category' => 'alam',
             'location' => 'Kota Semarang',
             'description' => 'Hutan kota kecil rindang tempat warga lari pagi dan anak-anak bermain; oase hijau gratis di tengah keramaian.',
             'hero_image' => 'images/hutan-srigunting.jpg', 'lat' => -6.9650, 'lng' => 110.4290,
             'htm' => 0, 'opening_hours' => '05.00–20.00', 'best_time' => 'Pagi',
             'access_info' => 'Di belakang kawasan Kota Lama.',
             'is_featured' => false],
            ['slug' => 'taman-tabanas', 'name' => 'Taman Tabanas', 'category' => 'nongkrong',
             'location' => 'Semarang Tengah, Kota Semarang',
             'description' => 'Ruang terbuka hijau di tengah kota dengan taman rapi. Cocok untuk jalan santai, senam, atau sekadar duduk-duduk sore.',
             'hero_image' => 'images/taman-tabanas.jpg', 'lat' => -6.9800, 'lng' => 110.4200,
             'htm' => 0, 'opening_hours' => '05.00–22.00', 'best_time' => 'Pagi atau sore',
             'access_info' => 'Di Jl. Pemuda, dekat kawasan Pecinan.',
             'is_featured' => false],

            // --- Kabupaten Semarang (Bandungan, Ungaran, Ambarawa) ---
            ['slug' => 'umbul-sidomukti', 'name' => 'Umbul Sidomukti', 'category' => 'alam',
             'location' => 'Bandungan, Kab. Semarang',
             'description' => 'Taman rekreasi di ketinggian Bandungan dengan kolam air panas, flying fox, dan panorama pegunungan. Favorit keluarga.',
             'hero_image' => 'images/umbul-sidomukti.jpg', 'lat' => -7.2830, 'lng' => 110.3520,
             'htm' => 30000, 'opening_hours' => '08.00–16.00', 'best_time' => 'Pagi sampai siang',
             'access_info' => '45 menit dari Kota Semarang via Ungaran, lanjut Bandungan.',
             'is_featured' => true],
            ['slug' => 'candi-gedong-songo', 'name' => 'Candi Gedong Songo', 'category' => 'sejarah',
             'location' => 'Bandungan, Kab. Semarang',
             'description' => 'Kompleks candi Hindu di lereng Gunung Ungaran dengan pemandangan lembah dan mata air belerang. Naik kuda atau jalan kaki antar candi.',
             'hero_image' => 'images/gedong-songo.jpg', 'lat' => -7.2100, 'lng' => 110.3430,
             'htm' => 20000, 'opening_hours' => '06.30–17.00', 'best_time' => 'Pagi, cerah',
             'access_info' => '50 menit dari kota via Ungaran; jalan menanjak.',
             'is_featured' => true],
            ['slug' => 'rawa-pening', 'name' => 'Rawa Pening', 'category' => 'alam',
             'location' => 'Ambarawa, Kab. Semarang',
             'description' => 'Danau alami luas dengan hamparan teratai dan perahu tradisional. Sunyi, sejuk, dan cocok untuk foto dengan latar danau.',
             'hero_image' => 'images/rawa-pening.jpg', 'lat' => -7.2620, 'lng' => 110.4470,
             'htm' => 10000, 'opening_hours' => '07.00–17.00', 'best_time' => 'Pagi, embun terlihat',
             'access_info' => '1 jam dari Semarang arah Salatiga.',
             'is_featured' => true],
            ['slug' => 'museum-kereta-ambarawa', 'name' => 'Museum Kereta Api Ambarawa', 'category' => 'sejarah',
             'location' => 'Ambarawa, Kab. Semarang',
             'description' => 'Museum kereta uap bersejarah dengan koleksi lokomotif tua — bisa naik kereta bergerigi jalur Ambarawa–Bedono.',
             'hero_image' => 'images/museum-kereta.jpg', 'lat' => -7.2640, 'lng' => 110.3960,
             'htm' => 20000, 'opening_hours' => '08.00–16.00', 'best_time' => 'Pagi',
             'access_info' => '1 jam dari Semarang, di pusat Ambarawa.',
             'is_featured' => false],
            ['slug' => 'taman-bunga-cepu', 'name' => 'Taman Bunga Cepu', 'category' => 'keluarga',
             'location' => 'Ungaran, Kab. Semarang',
             'description' => 'Taman bunga warna-warni di lereng dengan wahana permainan anak. Ramah keluarga dan fotogenik.',
             'hero_image' => 'images/taman-bunga-cepu.jpg', 'lat' => -7.1550, 'lng' => 110.4100,
             'htm' => 25000, 'opening_hours' => '08.00–17.00', 'best_time' => 'Pagi, bunga segar',
             'access_info' => '30 menit dari Kota Semarang arah Ungaran.',
             'is_featured' => false],

            // --- Kendal, Demak, Salatiga (sekitarnya) ---
            ['slug' => 'curug-kali-pancur', 'name' => 'Curug Kali Pancur', 'category' => 'alam',
             'location' => 'Limangan, Kendal',
             'description' => 'Air terjun indah dengan kolam alami berair sejuk di kaki Gunung Ungaran. Trekking ringan dengan hadiah pemandangan segar.',
             'hero_image' => 'images/kali-pancur.jpg', 'lat' => -7.1050, 'lng' => 110.3230,
             'htm' => 10000, 'opening_hours' => '07.00–17.00', 'best_time' => 'Pagi',
             'access_info' => '1 jam dari Semarang arah Boja, lanjut Limangan.',
             'is_featured' => true],
            ['slug' => 'masjid-agung-demak', 'name' => 'Masjid Agung Demak', 'category' => 'religi',
             'location' => 'Demak',
             'description' => 'Masjid tertua di Jawa, didirikan para wali — pusat ziarah religi dengan arsitektur kayu jati khas. Sakral dan bersejarah.',
             'hero_image' => 'images/masjid-demak.jpg', 'lat' => -6.8910, 'lng' => 110.6380,
             'htm' => 0, 'opening_hours' => '04.00–21.00', 'best_time' => 'Pagi',
             'access_info' => '45 menit dari Semarang arah Demak.',
             'is_featured' => false],
            ['slug' => 'alun-alun-demak', 'name' => 'Alun-Alun Demak', 'category' => 'keluarga',
             'location' => 'Demak',
             'description' => 'Alun-alun luas di samping Masjid Agung — sore hari penuh penjual mainan anak, kereta kelinci, dan jajanan keluarga.',
             'hero_image' => 'images/alun-alun-demak.jpg', 'lat' => -6.8930, 'lng' => 110.6390,
             'htm' => 0, 'opening_hours' => '24 jam (meriah 16.00–21.00)', 'best_time' => 'Sore',
             'access_info' => 'Di pusat kota Demak, sebelah Masjid Agung.',
             'is_featured' => false],
            ['slug' => 'taman-kota-salatiga', 'name' => 'Taman Kota Salatiga', 'category' => 'nongkrong',
             'location' => 'Salatiga',
             'description' => 'Alun-alun hijau dan sejuk kota kecil di kaki Gunung Merbabu — tempat warga bersantai, kulineran, dan nongkrong santai.',
             'hero_image' => 'images/taman-salatiga.jpg', 'lat' => -7.3310, 'lng' => 110.5080,
             'htm' => 0, 'opening_hours' => '24 jam', 'best_time' => 'Sore',
             'access_info' => '1 jam dari Semarang; di pusat kota Salatiga.',
             'is_featured' => false],
            ['slug' => 'taman-ampel-salatiga', 'name' => 'Taman Ampel Salatiga', 'category' => 'keluarga',
             'location' => 'Salatiga',
             'description' => 'Taman kota rapi dengan playground anak dan jogging track; sore hari ramai keluarga dan komunitas olahraga.',
             'hero_image' => 'images/taman-ampel.jpg', 'lat' => -7.3200, 'lng' => 110.5210,
             'htm' => 0, 'opening_hours' => '05.00–21.00', 'best_time' => 'Sore',
             'access_info' => '10 menit dari pusat Salatiga.',
             'is_featured' => false],
        ];
        foreach ($destinations as $d) {
            $cat = $d['category'];
            unset($d['category']);
            Destination::updateOrCreate(
                ['slug' => $d['slug']],
                $d + ['category_id' => Category::where('slug', $cat)->first()->id]
            );
        }
        $bySlug = Destination::pluck('id', 'slug');

        // ===== REVIEWS utama (bernuansa lokal Semarang) =====
        $reviews = [
            ['user' => $dina, 'dest' => 'kota-lama', 'v' => 5, 'a' => 5, 'c' => 4, 'f' => 4,
             'body' => 'Sore hari paling pas — langit jadi latar foto gedung tua. Parkir motor 3rb. Mampir ke Gereja Blenduk dari dalam, interiornya keren.',
             'helpful' => 128, 'photos' => 2, 'visited' => '2026-08-15'],
            ['user' => $andi, 'dest' => 'kota-lama', 'v' => 5, 'a' => 4, 'c' => 4, 'f' => 4,
             'body' => 'Minggu pagi paling sepi dan adem. Sore lampu gedung menyala, suasana beda lagi. Ada tur jalan kaki gratis dari komunitas lokal.',
             'helpful' => 96, 'photos' => 4, 'visited' => '2026-07-20'],
            ['user' => $putri, 'dest' => 'lawang-sewu', 'v' => 5, 'a' => 5, 'c' => 4, 'f' => 4,
             'body' => 'Tiket 25rb masuk gedung utama. Interior ruang sel identik banget di foto-foto. Datang sore biar nggak kepanasan.',
             'helpful' => 71, 'photos' => 3, 'visited' => '2026-09-05'],
            ['user' => $bima, 'dest' => 'sam-poo-kong', 'v' => 5, 'a' => 4, 'c' => 5, 'f' => 4,
             'body' => 'Kompleksnya luas dan terawat. Sore menjelang maghrib paling syahdu, lampu-lampu merah mulai menyala.',
             'helpful' => 54, 'photos' => 1, 'visited' => '2026-06-18'],
            ['user' => $dina, 'dest' => 'lumpia-gang-lombok', 'v' => 5, 'a' => 3, 'c' => 4, 'f' => 3,
             'body' => 'Antriannya cepat kok. Lumpia basah dan goreng sama-sama wajib — pesan kering juga buat oleh-oleh, tahan lama.',
             'helpful' => 89, 'photos' => 3, 'visited' => '2026-10-19'],
            ['user' => $andi, 'dest' => 'toko-oen', 'v' => 4, 'a' => 4, 'c' => 4, 'f' => 4,
             'body' => 'Es krimnya klasik, suasananya seperti masuk mesin waktu. Harga agak tinggi tapi untuk pengalamannya, worth it.',
             'helpful' => 64, 'photos' => 2, 'visited' => '2026-05-08'],
            ['user' => $putri, 'dest' => 'kampung-pelangi', 'v' => 4, 'a' => 4, 'c' => 3, 'f' => 3,
             'body' => 'Tangga pelanginya ramai — datang pagi sebelum rombongan. Mural-mural baru makin banyak, warganya ramah.',
             'helpful' => 47, 'photos' => 5, 'visited' => '2026-03-02'],
            ['user' => $bima, 'dest' => 'curug-kali-pancur', 'v' => 5, 'a' => 3, 'c' => 4, 'f' => 3,
             'body' => 'Trekking 30–45 menit lewat jembatan kecil. Airnya sejuk banget. Sandal gunung lebih nyaman dari sepatu.',
             'helpful' => 112, 'photos' => 3, 'visited' => '2026-04-21'],
            ['user' => $dina, 'dest' => 'umbul-sidomukti', 'v' => 5, 'a' => 4, 'c' => 4, 'f' => 5,
             'body' => 'Anak-anak betah di kolam air hangat. Weekend ramai — datang pagi. Jalanan menanjak, motor matik masih aman.',
             'helpful' => 73, 'photos' => 2, 'visited' => '2026-07-12'],
            ['user' => $andi, 'dest' => 'candi-gedong-songo', 'v' => 5, 'a' => 3, 'c' => 4, 'f' => 4,
             'body' => 'Naik kuda antar candi sekitar 100–150rb. Pagi ada kabut, suasana mistis. Bawa jaket — dingin!',
             'helpful' => 95, 'photos' => 4, 'visited' => '2026-08-30'],
            ['user' => $putri, 'dest' => 'rawa-pening', 'v' => 4, 'a' => 4, 'c' => 4, 'f' => 3,
             'body' => 'Perahu keliling danau 20–30rb. Pagi ada embun di teratai — surga foto. Sore anginnya kencang.',
             'helpful' => 58, 'photos' => 3, 'visited' => '2026-09-27'],
            ['user' => $bima, 'dest' => 'simpang-lima', 'v' => 4, 'a' => 5, 'c' => 3, 'f' => 4,
             'body' => 'Minggu malam paling meriah — nasi goreng, sate, dan buskers. Tempat duduk cepat penuh, datang lebih awal.',
             'helpful' => 41, 'photos' => 1, 'visited' => '2026-10-04'],
        ];
        foreach ($reviews as $r) {
            Review::updateOrCreate(
                ['user_id' => $r['user']->id, 'destination_id' => $bySlug[$r['dest']]],
                ['score_view' => $r['v'], 'score_access' => $r['a'], 'score_cleanliness' => $r['c'],
                 'score_facility' => $r['f'], 'body' => $r['body'], 'helpful_count' => $r['helpful'],
                 'photo_count' => $r['photos'], 'visited_at' => $r['visited']]
            );
        }

        // Bulk review tambahan supaya jumlah review user sesuai profil.
        // Satu review per pasangan (user, destinasi) karena unique constraint —
        // 27 destinasi cukup untuk target di bawah.
        $extraDests = Destination::all();
        $texts = [
            'Tempatnya bersih, petugas ramah. Parkir luas.',
            'Pemandangan terbaik pas sore, golden hour-nya keren.',
            'Anak-anak suka banget, area keluarga lengkap.',
            'Harga tiket masih wajar buat fasilitas sebagus ini.',
            'Datang weekday biar nggak antre lama.',
            'Jalannya enak, banyak pohon rindang.',
            'Warung sekitar murah dan enak, cobanya soto Semarang.',
            'Penjaganya ramah, banyak cerita sejarah lokal.',
        ];
        $targets = [$dina->id => 26, $andi->id => 22, $putri->id => 18, $bima->id => 15];
        $usersCycle = [$dina, $andi, $putri, $bima];
        $i = 0;
        foreach ($usersCycle as $u) {
            $have = Review::where('user_id', $u->id)->count();
            foreach ($extraDests as $j => $dest) {
                if ($have >= $targets[$u->id]) {
                    break;
                }
                if (Review::where('user_id', $u->id)->where('destination_id', $dest->id)->exists()) {
                    continue;
                }
                Review::create([
                    'user_id' => $u->id,
                    'destination_id' => $dest->id,
                    'score_view' => 4 + ($j % 2), 'score_access' => 3 + ($j % 3),
                    'score_cleanliness' => 4 + ($j % 2), 'score_facility' => 3 + ($j % 2),
                    'body' => $texts[$i++ % count($texts)],
                    'helpful_count' => random_int(3, 40), 'photo_count' => random_int(0, 3),
                    'visited_at' => now()->subDays(random_int(10, 400))->toDateString(),
                ]);
                $have++;
            }
        }

        // ===== ARTIKEL PANDUAN (tema Semarang) =====
        $articles = [
            ['slug' => 'weekend-1-hari-semarang', 'title' => 'Libur 1 Hari di Semarang: Rute Kota Lama – Kuliner – Pantai',
             'tag' => 'ITINERARY', 'cover' => 'images/kota-lama.jpg',
             'excerpt' => 'Satu hari cukup untuk jatuh cinta pada Semarang: pagi di Kota Lama, siang kuliner Pecinan, sore di Marina.',
             'author' => $rani, 'minutes' => 8, 'likes' => 2100, 'featured' => true],
            ['slug' => 'kuliner-wajib-semarang', 'title' => '10 Kuliner Wajib Semarang (dan Tempatnya)',
             'tag' => 'KULINER', 'cover' => 'images/lumpia.jpg',
             'excerpt' => 'Lumpia, tahu pong, wingko babat, bandeng presto — peta kuliner dari Pecinan sampai pinggir jalan.',
             'author' => $rani, 'minutes' => 9, 'likes' => 3200, 'featured' => false],
            ['slug' => 'semarang-atas-vs-bawah', 'title' => 'Semarang Atas vs Semarang Bawah: Bedanya Buat Dikunjungi',
             'tag' => 'TIPS', 'cover' => 'images/simpang-lima.jpg',
             'excerpt' => 'Suhu, suasana, dan jenis tempat di dua sisi kota — supaya kamu tahu harus ke mana dulu.',
             'author' => $rani, 'minutes' => 6, 'likes' => 1400, 'featured' => false],
            ['slug' => 'trip-bandungan-2-hari', 'title' => 'Bandungan–Gedong Songo 2 Hari Bersama Keluarga',
             'tag' => 'KELUARGA', 'cover' => 'images/gedong-songo.jpg',
             'excerpt' => 'Itinerary santai 2 hari: taman rekreasi, candi di lereng gunung, dan sore di tepi Rawa Pening.',
             'author' => $rani, 'minutes' => 10, 'likes' => 1800, 'featured' => false],
            ['slug' => 'cafe-hopping-semarang', 'title' => 'Cafe Hopping Semarang: Dari Kopi Street Sampai Kedai Taman',
             'tag' => 'NONGKRONG', 'cover' => 'images/kopi-street.jpg',
             'excerpt' => 'Panduan cafe hopping satu sore: kopi susu gula aren, kedai taman, sampai spot paling estetik.',
             'author' => $rani, 'minutes' => 7, 'likes' => 2600, 'featured' => false],
        ];
        foreach ($articles as $a) {
            Article::updateOrCreate(
                ['slug' => $a['slug']],
                ['title' => $a['title'], 'tag' => $a['tag'], 'cover_image' => $a['cover'],
                 'excerpt' => $a['excerpt'], 'author_id' => $a['author']->id,
                 'read_minutes' => $a['minutes'], 'likes' => $a['likes'], 'is_featured' => $a['featured']]
            );
        }

        // ===== THREADS KOMUNITAS =====
        $threads = [
            ['user' => $dina, 'type' => 'tanya', 'title' => 'Rekomendasi tempat foto estetik di Semarang buat prewedding?',
             'body' => 'Budget terbatas, cari yang gratis atau tiket murah. Ada rekomendasi outdoor?', 'replies' => 12, 'last' => 2],
            ['user' => $bima, 'type' => 'tanya', 'title' => 'Gedong Songo naik kuda atau jalan kaki?',
             'body' => 'Bawa anak 6 tahun, bingung pilih naik kuda atau jalan kaki. Aman nggak ya?', 'replies' => 8, 'last' => 5],
            ['user' => $putri, 'type' => 'cerita', 'title' => 'Lumpia Gang Lombok sold out — dan saya balik lagi besok paginya',
             'body' => 'Cerita panjang tentang lumpia Semarang yang bikin nagih sampai kembali esok hari.',
             'replies' => 34, 'last' => 1],
        ];
        foreach ($threads as $t) {
            Thread::updateOrCreate(
                ['user_id' => $t['user']->id, 'title' => $t['title']],
                ['type' => $t['type'], 'body' => $t['body'], 'reply_count' => $t['replies'],
                 'last_activity_at' => now()->subHours($t['last'])]
            );
        }

        // ===== MINAT (onboarding) =====
        $interests = [
            ['slug' => 'kuliner', 'name' => 'Kuliner', 'icon' => 'restaurant'],
            ['slug' => 'sejarah', 'name' => 'Sejarah & Budaya', 'icon' => 'account_balance'],
            ['slug' => 'nongkrong', 'name' => 'Nongkrong & Santai', 'icon' => 'storefront'],
            ['slug' => 'kafe', 'name' => 'Kafe & Kedai', 'icon' => 'local_cafe'],
            ['slug' => 'alam', 'name' => 'Alam & Bukit', 'icon' => 'landscape'],
            ['slug' => 'seni', 'name' => 'Seni & Fotogenik', 'icon' => 'palette'],
        ];
        foreach ($interests as $it) {
            Interest::updateOrCreate(['slug' => $it['slug']], $it);
        }

        // Admin sudah punya minat agar tidak diarahkan ke onboarding saat login /admin
        User::where('email', 'admin@dolan-smg.id')->first()
            ->interests()->syncWithoutDetaching(
                Interest::whereIn('slug', ['kuliner', 'sejarah'])->pluck('id')
            );

        // ===== KOLEKSI DINA =====
        $wishlist = Collection::updateOrCreate(
            ['user_id' => $dina->id, 'name' => 'Wishlist Akhir Pekan'],
            ['icon' => 'bookmark']
        );
        $wishlist->destinations()->syncWithoutDetaching([$bySlug['candi-gedong-songo'], $bySlug['curug-kali-pancur'], $bySlug['museum-kereta-ambarawa']]);
        $visited = Collection::updateOrCreate(
            ['user_id' => $dina->id, 'name' => 'Sudah Dikunjungi'],
            ['icon' => 'check_circle']
        );
        $visited->destinations()->syncWithoutDetaching([$bySlug['kota-lama'], $bySlug['lawang-sewu'], $bySlug['lumpia-gang-lombok']]);
        $trip = Collection::updateOrCreate(
            ['user_id' => $dina->id, 'name' => 'Rute Bandungan'],
            ['icon' => 'route']
        );
        $trip->destinations()->syncWithoutDetaching([$bySlug['umbul-sidomukti'], $bySlug['candi-gedong-songo']]);
    }
}
