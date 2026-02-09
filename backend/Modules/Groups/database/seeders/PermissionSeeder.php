<?php

namespace Modules\Groups\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Groups\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'view',
            'edit',
            'delete',
            'manage_members',
            'invite',
            'assign_tasks',
            'grade',
            'comment',
            'archive',
            'export',
        ];

        foreach ($types as $type) {
            Permission::firstOrCreate(['type' => $type]);
        }
    }
}
