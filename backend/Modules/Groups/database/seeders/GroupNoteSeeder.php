<?php

namespace Modules\Groups\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Models\Group;
use Modules\Groups\Models\GroupNote;
use Modules\Groups\Models\UserGroup;

class GroupNoteSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::query()->get();

        if ($groups->isEmpty()) {
            return;
        }

        $contents = [
            'Dobra praca na ostatnich zajęciach, tak trzymać!',
            'Proszę uzupełnić zadanie domowe przed kolejnym spotkaniem.',
            'Warto powtórzyć materiał z poprzedniego tygodnia.',
            'Świetne postępy, widać systematyczną naukę.',
            'Przypominam o terminie oddania zadania.',
        ];

        foreach ($groups as $group) {
            $owner = UserGroup::where('group_id', $group->id)
                ->where('is_owner', true)
                ->first();

            if (!$owner) {
                continue;
            }

            $member = UserGroup::where('group_id', $group->id)
                ->where('is_owner', false)
                ->inRandomOrder()
                ->first();

            GroupNote::create([
                'group_id'  => $group->id,
                'author_id' => $owner->user_id,
                'user_id'   => $member?->user_id,
                'content'   => $contents[array_rand($contents)],
            ]);
        }
    }
}
