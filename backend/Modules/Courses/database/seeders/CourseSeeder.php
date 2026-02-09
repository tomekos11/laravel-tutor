<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Course;
use Modules\Courses\Models\Level;
use Modules\Courses\Models\Field;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $levelIds = Level::pluck('id')->all();
        $fieldIds = Field::pluck('id')->all();

        if (empty($levelIds) || empty($fieldIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Course::create([
                'level_id' => $levelIds[array_rand($levelIds)],
                'field_id' => $fieldIds[array_rand($fieldIds)],
            ]);
        }
    }
}
