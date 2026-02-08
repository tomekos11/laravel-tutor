<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'student']);
        Role::create(['name' => 'tutor']);
        Role::create(['name' => 'moderator']);
        Role::create(['name' => 'admin']);
    }
}
