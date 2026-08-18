<?php

namespace Modules\Tasks\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tasks\Models\Task;
use Modules\Tasks\Models\TaskRating;
use Modules\Users\Models\User;

class TaskRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taskIds = Task::pluck('id')->all();
        $userIds = User::pluck('id')->all();

        if (empty($taskIds) || empty($userIds)) {
            return;
        }

        foreach ($taskIds as $taskId) {
            $reviewers = (array) array_rand(array_flip($userIds), min(count($userIds), random_int(0, 6)) ?: 1);

            foreach ($reviewers as $userId) {
                if (random_int(0, 4) === 0) {
                    continue; // not everyone rates every task
                }

                TaskRating::updateOrCreate(
                    ['task_id' => $taskId, 'user_id' => $userId],
                    ['rating' => random_int(1, 5)]
                );
            }
        }
    }
}
