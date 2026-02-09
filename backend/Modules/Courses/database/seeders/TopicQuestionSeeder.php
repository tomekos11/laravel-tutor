<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Question;
use Modules\Courses\Models\Topic;
use Modules\Courses\Models\TopicQuestion;

class TopicQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questionIds = Question::pluck('id')->all();
        $topicIds    = Topic::pluck('id')->all();

        if (empty($questionIds) || empty($topicIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            TopicQuestion::create([
                'question_id' => $questionIds[array_rand($questionIds)],
                'topic_id'    => $topicIds[array_rand($topicIds)],
            ]);
        }
    }
}
