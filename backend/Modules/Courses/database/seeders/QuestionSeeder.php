<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Courses\Models\Question;
use Modules\Users\Models\User;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->all();

        if (empty($userIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Question::create([
                'creator_id' => $userIds[array_rand($userIds)],
                'content'    => 'Sample question ' . $i,
                'type'       => 'single_choice',
            ]);
        }
    }
}
