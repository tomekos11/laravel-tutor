<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Answer;
use Modules\Courses\Models\Question;
use Modules\Users\Models\User;

class AnswerSeeder extends Seeder
{
    public function run(): void
    {
        $questionIds = Question::pluck('id')->all();
        $userIds     = User::pluck('id')->all();

        if (empty($questionIds) || empty($userIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Answer::create([
                'creator_id'  => $userIds[array_rand($userIds)],
                'question_id' => $questionIds[array_rand($questionIds)],
            ]);
        }
    }
}
