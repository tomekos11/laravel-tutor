<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Beginner',
            'Elementary',
            'Pre-Intermediate',
            'Intermediate',
            'Upper-Intermediate',
            'Advanced',
            'Expert',
            'A1',
            'B1',
            'C1',
        ];

        foreach ($names as $name) {
            Level::firstOrCreate(['name' => $name]);
        }
    }
}
