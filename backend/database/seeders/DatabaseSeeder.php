<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \Modules\Users\Database\Seeders\UsersDatabaseSeeder::class,
            \Modules\Courses\Database\Seeders\CoursesDatabaseSeeder::class,
            \Modules\Groups\Database\Seeders\GroupsDatabaseSeeder::class,
            \Modules\Ratings\Database\Seeders\RatingsDatabaseSeeder::class,
            \Modules\Lessons\Database\Seeders\LessonsDatabaseSeeder::class,
            \Modules\Advertisements\Database\Seeders\AdvertisementsDatabaseSeeder::class,
            ]);
    }
}
