<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $lawangSewu = Destination::create([
            'name' => 'Lawang Sewu',
            'category' => 'Sejarah',
            'price_range' => 'Rp20.000 - Rp30.000',
            'description' => 'Bangunan bersejarah ikonik peninggalan Belanda dengan ratusan pintu dan arsitektur megah di pusat Kota Semarang.',
            'address' => 'Jl. Pemuda No.160, Sekayu, Semarang Tengah',
            'image_url' => null,
        ]);

        $kotaLama = Destination::create([
            'name' => 'Kota Lama Semarang',
            'category' => 'Sejarah',
            'price_range' => 'Gratis',
            'description' => 'Kawasan cagar budaya bergaya arsitektur Eropa klasik lengkap dengan Gereja Blenduk, kafe estetik, dan spot foto.',
            'address' => 'Jl. Letjen Suprapto, Tanjung Mas, Semarang Utara',
            'image_url' => null,
        ]);

        $samPooKong = Destination::create([
            'name' => 'Klenteng Sam Poo Kong',
            'category' => 'Religi',
            'price_range' => 'Rp15.000 - Rp40.000',
            'description' => 'Kompleks klenteng bersejarah tempat persinggahan Laksamana Cheng Ho dengan arsitektur khas Tiongkok yang megah.',
            'address' => 'Jl. Simongan No.129, Bongsari, Semarang Barat',
            'image_url' => null,
        ]);

        $brownCanyon = Destination::create([
            'name' => 'Brown Canyon',
            'category' => 'Alam',
            'price_range' => 'Rp5.000 - Rp10.000',
            'description' => 'Tebing-tebing bekas galian yang membentuk panorama eksotis mirip Grand Canyon, populer untuk berburu foto saat sore hari.',
            'address' => 'Rowosari, Tembalang, Kota Semarang',
            'image_url' => null,
        ]);

        Review::factory(4)->create(['destination_id' => $lawangSewu->id]);
        Review::factory(3)->create(['destination_id' => $kotaLama->id]);
        Review::factory(3)->create(['destination_id' => $samPooKong->id]);
        Review::factory(2)->create(['destination_id' => $brownCanyon->id]);
    }
}