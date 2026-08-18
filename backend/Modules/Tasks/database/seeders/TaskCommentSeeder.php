<?php

namespace Modules\Tasks\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tasks\Models\Task;
use Modules\Tasks\Models\TaskComment;
use Modules\Users\Models\User;

class TaskCommentSeeder extends Seeder
{
    private const COMMENTS = [
        'Bardzo pomocne zadanie, dziękuję!',
        'Czy da się dostać przykładowe rozwiązanie?',
        'Trudniejsze niż się spodziewałem, ale ciekawe.',
        'Świetnie wyjaśnia trudny temat.',
        'Można prosić o dodatkowe wskazówki?',
        'Zrobiłem to zadanie ze swoimi uczniami, poszło dobrze.',
        'Przydałby się dodatkowy przykład.',
        'Super, dokładnie to czego szukałem.',
        'Trochę za proste jak na tę kategorię.',
        'Polecam, dobrze skonstruowane zadanie.',
    ];

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
            $commentsCount = random_int(0, 4);

            for ($i = 0; $i < $commentsCount; $i++) {
                TaskComment::create([
                    'task_id' => $taskId,
                    'user_id' => $userIds[array_rand($userIds)],
                    'content' => self::COMMENTS[array_rand(self::COMMENTS)],
                ]);
            }
        }
    }
}
