<?php

namespace Modules\Ratings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Answer;
use Modules\Courses\Models\AnswerRating;
use Modules\Users\Models\User;

class AnswerRatingSeeder extends Seeder
{
    public function run(): void
    {
        $answerIds = Answer::pluck('id')->all();
        $userIds   = User::pluck('id')->all();

        if (empty($answerIds) || empty($userIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            AnswerRating::create([
                'answer_id'   => $answerIds[array_rand($answerIds)],
                'reviewer_id' => $userIds[array_rand($userIds)],
                'mark'        => random_int(1, 5),
            ]);
        }
    }
}
