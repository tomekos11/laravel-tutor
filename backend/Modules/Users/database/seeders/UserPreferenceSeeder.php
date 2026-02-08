<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\Preference;
use Modules\Users\Models\UserRole;

class UserPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        $tutorIds = UserRole::where('role_id', 2)
            ->pluck('user_id')
            ->all();

        $extraUsers = [1];
        $userIds    = array_unique(array_merge($tutorIds, $extraUsers));

        foreach ($userIds as $index => $userId) {
            Preference::create([
                'user_id'         => $userId,
                'tutoring_format' => $this->formatForIndex($index),
                'hourly_price'    => $this->priceForIndex($index),
            ]);
        }
    }

    private function formatForIndex(int $i): string
    {
        $formats = ['online', 'offline', 'hybrid'];

        return $formats[$i % count($formats)];
    }

    private function priceForIndex(int $i): string
    {
        $prices = ['50.00', '75.00', '100.00', '120.00'];

        return $prices[$i % count($prices)];
    }
}
