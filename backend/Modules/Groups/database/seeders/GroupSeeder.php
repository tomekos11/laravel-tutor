<?php

namespace Modules\Groups\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Models\Group;
use Modules\Courses\Models\Course;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $courseIds = Course::query()->pluck('id')->all();

        if (empty($courseIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Group::create([
                'course_id' => $courseIds[array_rand($courseIds)],
                'name'      => 'Group ' . $i,
            ]);
        }
    }
}
