<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\Message;

class ConversationMessageSeeder extends Seeder
{
    public function run(): void
    {
        Message::create([
            'conversation_id' => 1,
            'creator_id'      => 1,
            'content'         => 'Cześć wszystkim! To jest główny kanał z informacjami o platformie.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 1,
            'creator_id'      => 2,
            'content'         => 'Hej! Dzięki za zaproszenie, wygląda ciekawie.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 2,
            'creator_id'      => 3,
            'content'         => 'Macie jakieś pytania przed następnymi zajęciami?',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 2,
            'creator_id'      => 4,
            'content'         => 'Tak, mam problem z zadaniem z kolekcjami w Laravelu.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 3,
            'creator_id'      => 3,
            'content'         => 'Tutaj wrzucajcie pytania o Eloquent i relacje.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 3,
            'creator_id'      => 6,
            'content'         => 'Jak najlepiej paginować wyniki z relacjami?',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 4,
            'creator_id'      => 7,
            'content'         => 'Egzamin obejmuje rozdziały 1–5, nie zapomnijcie o zadaniach domowych.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 4,
            'creator_id'      => 8,
            'content'         => 'Dzięki, czy możesz wrzucić przykładowe pytania?',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 5,
            'creator_id'      => 8,
            'content'         => 'Wrzućcie tutaj linki do repozytoriów z zadaniami.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 5,
            'creator_id'      => 4,
            'content'         => 'Gotowe: https://github.com/example/user4-homework',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 6,
            'creator_id'      => 2,
            'content'         => 'Jutro zajęcia wyjątkowo zaczynamy o 18:00.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 6,
            'creator_id'      => 5,
            'content'         => 'Ok, dzięki za info.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 7,
            'creator_id'      => 4,
            'content'         => 'Jeśli macie problem z logowaniem lub rejestracją, piszcie tutaj.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 7,
            'creator_id'      => 1,
            'content'         => 'Na produkcji pojawił się błąd 500 przy resetowaniu hasła, sprawdzę logi.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 8,
            'creator_id'      => 5,
            'content'         => 'Jak oceniacie tempo kursu? Śmiało, potrzebujemy szczerego feedbacku.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 8,
            'creator_id'      => 7,
            'content'         => 'Tempo ok, ale chętnie zobaczyłbym więcej przykładów z realnych projektów.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 9,
            'creator_id'      => 6,
            'content'         => 'Proponuję, żebyśmy zrobili projekt w Laravelu + Vue.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 9,
            'creator_id'      => 8,
            'content'         => 'Jestem za, mogę ogarnąć część frontendową.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 10,
            'creator_id'      => 9,
            'content'         => 'Przypominam o zasadach – bez spamowania i off-topicu.',
            'img'             => null,
        ]);

        Message::create([
            'conversation_id' => 10,
            'creator_id'      => 3,
            'content'         => 'Spoko, będę zgłaszał podejrzane wiadomości.',
            'img'             => null,
        ]);
    }
}
