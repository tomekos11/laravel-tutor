<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Topic;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Algebra',
            'Geometry',
            'Statistics',
            'Programming Basics',
            'Data Structures',
            'Networks',
            'Literature',
            'Grammar',
            'World History',
            'Machine Learning',
        ];

        foreach ($names as $name) {
            Topic::firstOrCreate(['name' => $name]);
        }
    }
}
