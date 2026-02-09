<?php

namespace Modules\Groups\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Models\Assignment;
use Modules\Groups\Models\AssignmentDate;
use Modules\Users\Models\User;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $dateIds = AssignmentDate::query()->pluck('id')->all();
        $userIds = User::query()->pluck('id')->all();

        if (empty($dateIds) || empty($userIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Assignment::create([
                'user_id'             => $userIds[array_rand($userIds)],
                'assignment_date_id'  => $dateIds[array_rand($dateIds)],
            ]);
        }
    }
}
