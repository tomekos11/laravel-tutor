<?php

namespace Modules\Ratings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Question;
use Modules\Courses\Models\QuestionRating;
use Modules\Users\Models\User;

class QuestionRatingSeeder extends Seeder
{
    public function run(): void
    {
        $questionIds = Question::pluck('id')->all();
        $userIds     = User::pluck('id')->all();

        if (empty($questionIds) || empty($userIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            QuestionRating::create([
                'question_id' => $questionIds[array_rand($questionIds)],
                'reviewer_id' => $userIds[array_rand($userIds)],
                'mark'        => random_int(1, 5),
            ]);
        }
    }
}
