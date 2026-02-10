<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\Rating;
use Modules\Users\Models\User;

class UserRatingSeeder extends Seeder
{
    public function run(): void
    {
        // Uczeń
        $student = User::find(1);
        if (! $student) {
            return;
        }

        // Korepetytorzy – np. kolejni użytkownicy
        $tutors = User::where('id', '>=', 2)
            ->orderBy('id')
            ->take(5)
            ->get();

        if ($tutors->isEmpty()) {
            return;
        }

        // 1) Uczeń (id=1) ocenia każdego tutora
        foreach ($tutors as $index => $tutor) {
            $rating = [5, 4, 3, 5, 4][$index] ?? 4;

            $this->rateUser(
                tutorId: $tutor->id,         // oceniany = tutor
                reviewerId: $student->id,    // oceniający = uczeń
                rating: $rating,
                description: 'Uczeń #1: stała, powtarzalna ocena korepetytora.',
                tutorMadeThisReview: false
            );
        }

        // 2) Tutorzy oceniają siebie nawzajem (peer review)
        foreach ($tutors as $i => $tutor) {
            foreach ($tutors as $j => $otherTutor) {
                if ($tutor->id === $otherTutor->id) {
                    continue;
                }

                $cycle = [4, 5, 3];
                $rating = $cycle[($i + $j) % count($cycle)];

                $this->rateUser(
                    tutorId: $tutor->id,
                    reviewerId: $otherTutor->id,
                    rating: $rating,
                    description: 'Peer review między korepetytorami.',
                    tutorMadeThisReview: true
                );
            }
        }

        // 3) Tutorzy oceniają ucznia (id=1)
        foreach ($tutors as $index => $tutor) {
            $rating = [3, 4, 5, 4, 3][$index] ?? 4;

            $this->rateUser(
                tutorId: $student->id,       // oceniany = uczeń
                reviewerId: $tutor->id,
                rating: $rating,
                description: 'Tutor ocenia ucznia #1.',
                tutorMadeThisReview: true
            );
        }
    }

    private function rateUser(
        int $tutorId,
        int $reviewerId,
        int $rating,
        string $description,
        bool $tutorMadeThisReview
    ): void {
        if ($tutorId === $reviewerId) {
            return;
        }

        Rating::firstOrCreate(
            [
                'tutor_id'    => $tutorId,
                'reviewer_id' => $reviewerId,
            ],
            [
                'tutor_made_this_review' => $tutorMadeThisReview,
                'rating'                 => $rating,
                'description'            => $description,
            ]
        );
    }
}
