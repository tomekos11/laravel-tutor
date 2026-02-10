<?php

namespace Modules\Advertisements\Database\Seeders;

use Illuminate\Database\Seeder;

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
        ]);
    }
}
