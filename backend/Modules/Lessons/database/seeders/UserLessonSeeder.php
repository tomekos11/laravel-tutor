<?php

namespace Modules\Lessons\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Modules\Lessons\Models\Lesson;
use Modules\Lessons\Models\UserLesson;
use Modules\Users\Models\User;

class UserLessonSeeder extends Seeder
{
    public function run(): void
    {
        $lessons = Lesson::all();
        $users   = User::all();

        if ($lessons->isEmpty() || $users->isEmpty()) {
            return;
        }

        foreach ($lessons as $lesson) {
            // losowo 2–8 userów na lekcję
            $count       = min($users->count(), rand(2, 8));
            $participants = $users->random($count);

            foreach ($participants as $user) {
                UserLesson::updateOrCreate(
                    [
                        'lesson_id' => $lesson->id,
                        'user_id'   => $user->id,
                    ],
                    [
                        'grade'   => rand(0, 1) ? rand(1, 6) : null,
                        'comment' => Arr::random([
                            null,
                            'Wszystko OK.',
                            'Uczeń wymaga dodatkowego wsparcia.',
                            'Świetna aktywność na zajęciach.',
                            'Nieobecny bez usprawiedliwienia.',
                        ]),
                    ]
                );
            }
        }
    }
}
