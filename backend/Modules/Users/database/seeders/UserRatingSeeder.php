<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\Rating;
use Modules\Users\Models\UserRole;

class UserRatingSeeder extends Seeder
{
    public function run(): void
    {
        // Tutorzy (role_id = 2)
        $tutorIds = UserRole::where('role_id', 2)
            ->pluck('user_id')
            ->all();

        if (empty($tutorIds)) {
            return;
        }

        // Przyjmijmy, że pozostali użytkownicy mogą być recenzentami
        $allUserIds = UserRole::pluck('user_id')->unique()->all();

        foreach ($tutorIds as $tutorId) {
            // proste przykładowe oceny od kilku userów
            $this->rateTutor($tutorId, 1, 5, 'Świetny tutor, bardzo jasno tłumaczy.');
            $this->rateTutor($tutorId, 2, 4, 'Dobre tempo zajęć, dużo praktyki.');
            $this->rateTutor($tutorId, 4, 3, 'Ok, ale mógłby podawać więcej przykładów z życia.');
        }
    }

    private function rateTutor(int $tutorId, int $reviewerId, int $rating, string $description): void
    {
        // jeśli nie chcesz self-review, można dodać warunek
        if ($tutorId === $reviewerId) {
            return;
        }

        Rating::create([
            'tutor_id'             => $tutorId,
            'reviewer_id'          => $reviewerId,
            'tutor_made_this_review' => false,
            'rating'               => $rating,
            'description'          => $description,
        ]);
    }
}
