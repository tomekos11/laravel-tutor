<?php

namespace Modules\Advertisements\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Database\Seeders\GroupNoteSeeder;

class AdvertisementsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AdvertisementSeeder::class,
            LocationSeeder::class,
            AdvertisementLocationSeeder::class,
            AdvertisementLevelSeeder::class,
            // Uruchamiane tutaj, bo dopiero teraz istnieją wszystkie grupy
            // (te "samodzielne" z GroupsDatabaseSeeder oraz te podpięte pod ogłoszenia).
            GroupNoteSeeder::class,
        ]);
    }
}
