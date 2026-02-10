<?php

namespace Modules\Advertisements\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Advertisements\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Online',
            'U ucznia',
            'U korepetytora',
            'Biblioteka',
            'Coworking',
            'Kawiarnia',
            'Rzeszów',
            'Warszawa',
            'Kraków',
            'Gdańsk',
        ];

        foreach ($names as $name) {
            Location::firstOrCreate(['name' => $name]);
        }
    }
}
