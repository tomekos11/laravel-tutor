<?php

namespace Modules\Groups\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Models\UserGroup;
use Modules\Groups\Models\Group;
use Modules\Users\Models\User;

class UserGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groupIds = Group::query()->pluck('id')->all();
        $userIds  = User::query()->pluck('id')->all();

        if (empty($groupIds) || empty($userIds)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            UserGroup::create([
                'user_id'  => $userIds[array_rand($userIds)],
                'group_id' => $groupIds[array_rand($groupIds)],
                'is_owner' => (bool) random_int(0, 1),
            ]);
        }
    }
}
