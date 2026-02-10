<?php

namespace Modules\Advertisements\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Advertisements\Models\Advertisement;
use Modules\Users\Models\User;
use Modules\Courses\Models\Field;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $userIds  = User::pluck('id')->all();
        $fieldIds = Field::pluck('id')->all();

        if (empty($userIds) || empty($fieldIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Advertisement::create([
                'user_id'     => $userIds[array_rand($userIds)],
                'field_id'    => $fieldIds[array_rand($fieldIds)],
                'price'       => random_int(50, 200), // zł
                'description' => 'Oferta korepetycji nr ' . $i,
                'address'     => 'Rzeszów, ul. Przykładowa ' . $i,
            ]);
        }
    }
}
