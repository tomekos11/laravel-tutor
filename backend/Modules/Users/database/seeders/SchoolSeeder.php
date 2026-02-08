<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        School::create([
            'name'        => 'Liceum Ogólnokształcące nr 1',
            'type'        => 'high_school',
            'coordinates' => '50.0413,21.9990',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);

        School::create([
            'name'        => 'Szkoła Podstawowa nr 5',
            'type'        => 'primary_school',
            'coordinates' => '50.0421,22.0055',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);

        School::create([
            'name'        => 'Zespół Szkół Elektronicznych',
            'type'        => 'technical_school',
            'coordinates' => '50.0398,22.0042',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);

        School::create([
            'name'        => 'Politechnika Rzeszowska',
            'type'        => 'university',
            'coordinates' => '50.0206,21.9889',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);

        School::create([
            'name'        => 'Uniwersytet Rzeszowski',
            'type'        => 'university',
            'coordinates' => '50.0301,22.0022',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);

        School::create([
            'name'        => 'Szkoła Muzyczna I stopnia',
            'type'        => 'music_school',
            'coordinates' => '50.0384,22.0071',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);

        School::create([
            'name'        => 'Liceum Plastyczne',
            'type'        => 'art_school',
            'coordinates' => '50.0372,22.0003',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);

        School::create([
            'name'        => 'Technikum Informatyczne',
            'type'        => 'technical_school',
            'coordinates' => '50.0440,21.9965',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);

        School::create([
            'name'        => 'Szkoła Podstawowa nr 12',
            'type'        => 'primary_school',
            'coordinates' => '50.0349,22.0130',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);

        School::create([
            'name'        => 'Liceum Językowe',
            'type'        => 'high_school',
            'coordinates' => '50.0481,22.0047',
            'city'        => 'Rzeszów',
            'country'     => 'Poland',
        ]);
    }
}
