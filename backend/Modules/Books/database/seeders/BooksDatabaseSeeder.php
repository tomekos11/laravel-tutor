<?php

namespace Modules\Books\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Books\Database\Seeders\BookSeeder;

class BooksDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            BookSeeder::class,
        ]);
    }
}
