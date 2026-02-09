<?php

namespace Modules\Courses\Database\Seeders;

use Illuminate\Database\Seeder;

class CoursesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            FieldSeeder::class,
            LevelSeeder::class,
            CourseSeeder::class,
            TopicSeeder::class,
            CourseTopicSeeder::class,
            QuestionSeeder::class,
            TopicQuestionSeeder::class,
            AnswerSeeder::class,
            AssignmentQuestionSeeder::class,
        ]);
    }
}
