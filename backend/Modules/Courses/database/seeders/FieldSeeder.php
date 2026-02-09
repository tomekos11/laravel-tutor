<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Field;

class FieldSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'History',
            'Geography',
            'Computer Science',
            'English',
            'Economics',
            'Philosophy',
        ];

        foreach ($names as $name) {
            Field::firstOrCreate(['name' => $name]);
        }
    }
}
