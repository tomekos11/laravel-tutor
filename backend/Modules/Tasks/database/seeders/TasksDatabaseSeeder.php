<?php

namespace Modules\Tasks\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tasks\Database\Seeders\TaskAttachmentSeeder;
use Modules\Tasks\Database\Seeders\TaskCommentSeeder;
use Modules\Tasks\Database\Seeders\TaskRatingSeeder;
use Modules\Tasks\Database\Seeders\TaskSeeder;

class TasksDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            TaskSeeder::class,
            TaskCommentSeeder::class,
            TaskRatingSeeder::class,
            TaskAttachmentSeeder::class,
        ]);
    }
}
