<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Course;
use Modules\Courses\Models\Topic;
use Modules\Courses\Models\CourseTopic;

class CourseTopicSeeder extends Seeder
{
    public function run(): void
    {
        $courseIds = Course::pluck('id')->all();
        $topicIds  = Topic::pluck('id')->all();

        if (empty($courseIds) || empty($topicIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            CourseTopic::create([
                'course_id' => $courseIds[array_rand($courseIds)],
                'topic_id'  => $topicIds[array_rand($topicIds)],
            ]);
        }
    }
}
