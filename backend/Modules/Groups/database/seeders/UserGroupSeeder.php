<?php

namespace Modules\Groups\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Models\UserGroup;
use Modules\Groups\Models\Group;
use Modules\Users\Models\User;
use Modules\Users\Models\UserRole;

class UserGroupSeeder extends Seeder
{
    public function run(): void
    {
        // Grupy powiązane z ogłoszeniami dostają swojego właściciela i uczniów
        // już w AdvertisementSeeder - tutaj obsługujemy tylko "samodzielne" grupy.
        $groups = Group::query()->whereNull('advertisement_id')->get();

        if ($groups->isEmpty()) {
            return;
        }

        $tutorIds   = UserRole::where('role_id', 2)->pluck('user_id')->all();
        $studentIds = UserRole::where('role_id', 1)->pluck('user_id')->all();
        $allUserIds = User::query()->pluck('id')->all();

        if (empty($allUserIds)) {
            return;
        }

        if (empty($tutorIds)) {
            $tutorIds = $allUserIds;
        }

        if (empty($studentIds)) {
            $studentIds = $allUserIds;
        }

        foreach ($groups as $group) {
            $ownerId = $tutorIds[array_rand($tutorIds)];

            UserGroup::create([
                'user_id'  => $ownerId,
                'group_id' => $group->id,
                'is_owner' => true,
            ]);

            $membersCount = $group->max_members ?? random_int(2, 5);
            $membersCount = max($membersCount, $group->min_members ?? 1);

            $candidates = array_values(array_diff($studentIds, [$ownerId]));
            shuffle($candidates);

            $members = array_slice($candidates, 0, min($membersCount, count($candidates)));

            foreach ($members as $memberId) {
                UserGroup::create([
                    'user_id'  => $memberId,
                    'group_id' => $group->id,
                    'is_owner' => false,
                ]);
            }
        }
    }
}
