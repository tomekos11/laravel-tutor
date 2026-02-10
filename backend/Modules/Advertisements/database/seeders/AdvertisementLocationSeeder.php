<?php

namespace Modules\Advertisements\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Advertisements\Models\Advertisement;
use Modules\Advertisements\Models\AdvertisementLocation;
use Modules\Advertisements\Models\Location;

class AdvertisementLocationSeeder extends Seeder
{
    public function run(): void
    {
        $ads       = Advertisement::orderBy('id')->get();
        $locations = Location::orderBy('id')->get();

        if ($ads->isEmpty() || $locations->isEmpty()) {
            return;
        }

        $locationIds = $locations->pluck('id')->values()->all();
        $locationCount = count($locationIds);

        foreach ($ads as $index => $ad) {
            // 1) Gwarantowane minimum: jedna lokalizacja na ogłoszenie
            $primaryLocationId = $locationIds[$index % $locationCount];

            AdvertisementLocation::firstOrCreate([
                'advertisement_id' => $ad->id,
                'location_id'      => $primaryLocationId,
            ]);

            // 2) Dodatkowo: dla co drugiego ogłoszenia dodaj drugą lokalizację
            if ($index % 2 === 0 && $locationCount > 1) {
                $secondaryLocationId = $locationIds[($index + 1) % $locationCount];

                AdvertisementLocation::firstOrCreate([
                    'advertisement_id' => $ad->id,
                    'location_id'      => $secondaryLocationId,
                ]);
            }
        }
    }
}
