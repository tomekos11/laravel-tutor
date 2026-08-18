<?php

namespace Modules\Books\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Books\Models\Book;

class BookSeeder extends Seeder
{
    private const BOOKS = [
        [
            'title' => 'Matematyka. Zbiór zadań',
            'author' => 'Marcin Kurczab',
            'language' => 'polski',
            'subject' => 'Matematyka',
            'publisher' => 'Oficyna Edukacyjna',
            'isbn' => '9788378931234',
            'publication_year' => 2019,
            'edition' => '3',
        ],
        [
            'title' => 'Fizyka dla szkół ponadpodstawowych',
            'author' => 'Krzysztof Chyla',
            'language' => 'polski',
            'subject' => 'Fizyka',
            'publisher' => 'Nowa Era',
            'isbn' => '9788326734567',
            'publication_year' => 2021,
            'edition' => '2',
        ],
        [
            'title' => 'Chemia. Repetytorium maturalne',
            'author' => 'Anna Warchoł',
            'language' => 'polski',
            'subject' => 'Chemia',
            'publisher' => 'WSiP',
            'isbn' => '9788302178901',
            'publication_year' => 2020,
            'edition' => '1',
        ],
        [
            'title' => 'Biologia. Zakres rozszerzony',
            'author' => 'Marek Guzik',
            'language' => 'polski',
            'subject' => 'Biologia',
            'publisher' => 'Nowa Era',
            'isbn' => '9788326745678',
            'publication_year' => 2018,
            'edition' => '4',
        ],
        [
            'title' => 'Podstawy programowania w Pythonie',
            'author' => 'Eric Matthes',
            'language' => 'polski',
            'subject' => 'Informatyka',
            'publisher' => 'Helion',
            'isbn' => '9788328356789',
            'publication_year' => 2022,
            'edition' => '3',
        ],
        [
            'title' => 'Struktury danych i algorytmy',
            'author' => 'Robert Lafore',
            'language' => 'polski',
            'subject' => 'Informatyka',
            'publisher' => 'Helion',
            'isbn' => '9788328367890',
            'publication_year' => 2017,
            'edition' => '2',
        ],
        [
            'title' => 'English Grammar in Use',
            'author' => 'Raymond Murphy',
            'language' => 'angielski',
            'subject' => 'Język angielski',
            'publisher' => 'Cambridge University Press',
            'isbn' => '9781108457651',
            'publication_year' => 2019,
            'edition' => '5',
        ],
        [
            'title' => 'Oxford Word Skills - słownictwo',
            'author' => 'Ruth Gairns',
            'language' => 'angielski',
            'subject' => 'Język angielski',
            'publisher' => 'Oxford University Press',
            'isbn' => '9780194620178',
            'publication_year' => 2016,
            'edition' => '1',
        ],
        [
            'title' => 'Matematyka. Zbiór zadań maturalnych',
            'author' => 'Wacław Zawadowski',
            'language' => 'polski',
            'subject' => 'Matematyka',
            'publisher' => 'GWO',
            'isbn' => '9788374207890',
            'publication_year' => 2023,
            'edition' => '1',
        ],
        [
            'title' => 'Termodynamika i fizyka statystyczna',
            'author' => 'Jerzy Ginter',
            'language' => 'polski',
            'subject' => 'Fizyka',
            'publisher' => 'PWN',
            'isbn' => '9788301198765',
            'publication_year' => 2015,
            'edition' => '2',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::BOOKS as $book) {
            Book::firstOrCreate(['isbn' => $book['isbn']], $book);
        }
    }
}
