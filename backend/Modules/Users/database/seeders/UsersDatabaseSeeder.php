<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            SchoolSeeder::class,
            UserRoleSeeder::class,
            UserCertificateSeeder::class,
            UserSchoolSeeder::class,
            ConversationSeeder::class,
            ConversationMessageSeeder::class,
            UserPreferenceSeeder::class,
            UserRatingSeeder::class,
        ]);
    }
}
