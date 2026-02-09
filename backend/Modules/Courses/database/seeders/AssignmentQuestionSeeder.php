<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\AssignmentQuestion;
use Modules\Courses\Models\Question;
use Modules\Groups\Models\AssignmentDate;

class AssignmentQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $dateIds     = AssignmentDate::pluck('id')->all();
        $questionIds = Question::pluck('id')->all();

        if (empty($dateIds) || empty($questionIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            AssignmentQuestion::create([
                'assignment_date_id' => $dateIds[array_rand($dateIds)],
                'question_id'        => $questionIds[array_rand($questionIds)],
            ]);
        }
    }
}
