<?php

namespace Modules\Groups\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Models\Assignment;
use Modules\Groups\Models\AssignmentAnswerCorrectness;
use Modules\Courses\Models\Question;

class AssignmentAnswerCorrectnessSeeder extends Seeder
{
    public function run(): void
    {
        $assignmentIds = Assignment::query()->pluck('id')->all();
        $questionIds   = Question::query()->pluck('id')->all();

        if (empty($assignmentIds) || empty($questionIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            AssignmentAnswerCorrectness::create([
                'assignment_id' => $assignmentIds[array_rand($assignmentIds)],
                'question_id'   => $questionIds[array_rand($questionIds)],
                'is_correct'    => (bool) random_int(0, 1),
            ]);
        }
    }
}
