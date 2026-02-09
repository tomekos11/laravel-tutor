<?php

namespace Modules\Groups\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Models\UserGroup;
use Illuminate\Support\Carbon;
use Modules\Groups\Models\AssignmentDate;

class AssignmentDateSeeder extends Seeder
{
    public function run(): void
    {
        $userGroupIds = UserGroup::query()->pluck('id')->all();

        if (empty($userGroupIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $open = Carbon::now()->subDays(random_int(0, 10));
            $finish = (clone $open)->addDays(random_int(1, 5));

            AssignmentDate::create([
                'user_group_id' => $userGroupIds[array_rand($userGroupIds)],
                'open_date'     => $open,
                'finish_date'   => $finish,
            ]);
        }
    }
}
