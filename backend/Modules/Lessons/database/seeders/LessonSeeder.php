<?php

namespace Modules\Lessons\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Lessons\Models\Lesson;
use Modules\Groups\Models\Group;
use Carbon\Carbon;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::all();

        if ($groups->isEmpty()) {
            return;
        }

        foreach ($groups as $group) {
            for ($i = 0; $i < 5; $i++) {
                $start = Carbon::now()
                    ->next('monday')
                    ->addWeeks($i % 4)
                    ->setTime(rand(8, 17), [0, 15, 30, 45][array_rand([0,1,2,3])]);

                $durationMinutes = [45, 60, 90][array_rand([0, 1, 2])];
                $end             = (clone $start)->addMinutes($durationMinutes);

                Lesson::create([
                    'group_id'      => $group->id,
                    'start_time'    => $start,
                    'end_time'      => $end,
                    'real_end_time' => rand(0, 1)
                        ? (clone $end)->addMinutes(rand(0, 20))
                        : null,
                    'remote'        => (bool) rand(0, 1),
                    'shared'        => (bool) rand(0, 1),
                    'notes'         => rand(0, 1)
                        ? 'Przykładowe zajęcia modułu Lessons dla grupy '.$group->id
                        : null,
                ]);
            }
        }
    }
}
