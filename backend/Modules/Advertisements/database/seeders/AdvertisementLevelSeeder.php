<?php

namespace Modules\Advertisements\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Advertisements\Models\Advertisement;
use Modules\Advertisements\Models\AdvertisementLevel;
use Modules\Courses\Models\Level;

class AdvertisementLevelSeeder extends Seeder
{
    public function run(): void
    {
        $ads    = Advertisement::orderBy('id')->get();
        $levels = Level::orderBy('id')->get();

        if ($ads->isEmpty() || $levels->isEmpty()) {
            return;
        }

        $levelIds    = $levels->pluck('id')->values()->all();
        $levelsCount = count($levelIds);

        foreach ($ads as $index => $ad) {
            // 1) Gwarantowane minimum: jeden level na ogłoszenie
            $primaryLevelId = $levelIds[$index % $levelsCount];

            AdvertisementLevel::firstOrCreate([
                'advertisement_id' => $ad->id,
                'level_id'         => $primaryLevelId,
            ]);

            // 2) Dodatkowy level dla co trzeciego ogłoszenia (jeśli jest z czego)
            if ($index % 3 === 0 && $levelsCount > 1) {
                $secondaryLevelId = $levelIds[($index + 1) % $levelsCount];

                AdvertisementLevel::firstOrCreate([
                    'advertisement_id' => $ad->id,
                    'level_id'         => $secondaryLevelId,
                ]);
            }
        }
    }
}
