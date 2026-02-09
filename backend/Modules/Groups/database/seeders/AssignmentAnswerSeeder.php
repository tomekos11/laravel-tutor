<?php

namespace Modules\Groups\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Models\AssignmentAnswer;
use Modules\Groups\Models\AssignmentAnswerCorrectness;

class AssignmentAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $correctnessIds = AssignmentAnswerCorrectness::query()->pluck('id')->all();

        if (empty($correctnessIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            AssignmentAnswer::create([
                'answer_correctness_id' => $correctnessIds[array_rand($correctnessIds)],
                'is_correct'            => (bool) random_int(0, 1),
            ]);
        }
    }
}
