<?php

namespace Modules\Tasks\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Books\Models\Book;
use Modules\Tasks\Models\Task;
use Modules\Users\Models\User;

class TaskSeeder extends Seeder
{
    private const DIFFICULTIES = ['easy', 'medium', 'hard'];

    private const FALLBACK_SUBJECTS = ['Matematyka', 'Fizyka', 'Chemia', 'Biologia', 'Informatyka', 'Język angielski'];

    private const TITLES = [
        'Równania kwadratowe',
        'Prawa Newtona w praktyce',
        'Reakcje redoks',
        'Budowa komórki eukariotycznej',
        'Algorytmy sortowania',
        'Czasy gramatyczne - present perfect',
        'Pochodne funkcji jednej zmiennej',
        'Prawo Ohma - zadania obliczeniowe',
        'Stechiometria - obliczenia chemiczne',
        'Fotosynteza i oddychanie komórkowe',
        'Struktury danych - listy i stosy',
        'Czasowniki nieregularne',
        'Trygonometria - funkcje kąta',
        'Dynamika ruchu prostoliniowego',
        'Wiązania chemiczne',
        'Genetyka mendlowska',
        'Bazy danych - relacje i klucze obce',
        'Słownictwo biznesowe',
        'Geometria analityczna',
        'Termodynamika - I zasada',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorIds = User::pluck('id')->all();

        if (empty($authorIds)) {
            return;
        }

        $subjects = DB::table('course__fields')->pluck('name')->all();
        if (empty($subjects)) {
            $subjects = self::FALLBACK_SUBJECTS;
        }

        $categories = DB::table('course__levels')->pluck('name')->all();

        $books = Book::all();
        $booksBySubject = $books->groupBy('subject');

        foreach (self::TITLES as $title) {
            $subject = $subjects[array_rand($subjects)];

            $task = Task::create([
                'author_id' => $authorIds[array_rand($authorIds)],
                'title' => $title,
                'description' => "Rozwiąż zadania dotyczące tematu: {$title}. Przedstaw pełny tok rozumowania i uzasadnij odpowiedzi.",
                'subject' => $subject,
                'category' => !empty($categories) ? $categories[array_rand($categories)] : null,
                'difficulty' => self::DIFFICULTIES[array_rand(self::DIFFICULTIES)],
            ]);

            // Attach a book to roughly every second task, preferring one matching the task's subject.
            if (random_int(0, 1) === 1 && $books->isNotEmpty()) {
                $candidates = $booksBySubject->get($subject, collect())->isNotEmpty()
                    ? $booksBySubject->get($subject)
                    : $books;

                $book = $candidates->random();

                $task->update([
                    'book_id' => $book->id,
                    'book_task_number' => sprintf('%d.%d', random_int(1, 12), random_int(1, 20)),
                ]);
            }
        }
    }
}
