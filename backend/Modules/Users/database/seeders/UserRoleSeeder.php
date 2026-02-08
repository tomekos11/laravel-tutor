<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        UserRole::create([
            'user_id' => 1,
            'role_id' => 4,
        ]);

        UserRole::create([
            'user_id' => 2,
            'role_id' => 3,
        ]);

        UserRole::create([
            'user_id' => 3,
            'role_id' => 2,
        ]);

        UserRole::create([
            'user_id' => 4,
            'role_id' => 1,
        ]);

        UserRole::create([
            'user_id' => 5,
            'role_id' => 1,
        ]);

        UserRole::create([
            'user_id' => 6,
            'role_id' => 1,
        ]);


        UserRole::create([
            'user_id' => 7,
            'role_id' => 2,
        ]);

        UserRole::create([
            'user_id' => 8,
            'role_id' => 2,
        ]);

        UserRole::create([
            'user_id' => 9,
            'role_id' => 3,
        ]);

        UserRole::create([
            'user_id' => 10,
            'role_id' => 4,
        ]);
    }
}
