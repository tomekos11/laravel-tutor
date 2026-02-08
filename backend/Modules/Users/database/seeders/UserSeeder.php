<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'name'     => 'Admin',
            'surname'  => 'User',
            'email'    => 'admin@example.com',
            'phone'    => '+48 600 000 001',
            'birthday' => '1990-01-01',
            'image'    => null,
        ]);

        User::create([
            'username' => 'user1',
            'password' => Hash::make('password1'),
            'name'     => 'Jan',
            'surname'  => 'Kowalski',
            'email'    => 'jan.kowalski@example.com',
            'phone'    => '+48 600 000 002',
            'birthday' => '1991-02-02',
            'image'    => null,
        ]);

        User::create([
            'username' => 'user2',
            'password' => Hash::make('password2'),
            'name'     => 'Anna',
            'surname'  => 'Nowak',
            'email'    => 'anna.nowak@example.com',
            'phone'    => '+48 600 000 003',
            'birthday' => '1992-03-03',
            'image'    => null,
        ]);

        User::create([
            'username' => 'user3',
            'password' => Hash::make('password3'),
            'name'     => 'Piotr',
            'surname'  => 'Wiśniewski',
            'email'    => 'piotr.wisniewski@example.com',
            'phone'    => '+48 600 000 004',
            'birthday' => '1993-04-04',
            'image'    => null,
        ]);

        User::create([
            'username' => 'user4',
            'password' => Hash::make('password4'),
            'name'     => 'Katarzyna',
            'surname'  => 'Wójcik',
            'email'    => 'katarzyna.wojcik@example.com',
            'phone'    => '+48 600 000 005',
            'birthday' => '1994-05-05',
            'image'    => null,
        ]);

        User::create([
            'username' => 'user5',
            'password' => Hash::make('password5'),
            'name'     => 'Tomasz',
            'surname'  => 'Kaczmarek',
            'email'    => 'tomasz.kaczmarek@example.com',
            'phone'    => '+48 600 000 006',
            'birthday' => '1995-06-06',
            'image'    => null,
        ]);

        User::create([
            'username' => 'user6',
            'password' => Hash::make('password6'),
            'name'     => 'Magdalena',
            'surname'  => 'Mazur',
            'email'    => 'magdalena.mazur@example.com',
            'phone'    => '+48 600 000 007',
            'birthday' => '1996-07-07',
            'image'    => null,
        ]);

        User::create([
            'username' => 'user7',
            'password' => Hash::make('password7'),
            'name'     => 'Michał',
            'surname'  => 'Krawczyk',
            'email'    => 'michal.krawczyk@example.com',
            'phone'    => '+48 600 000 008',
            'birthday' => '1997-08-08',
            'image'    => null,
        ]);

        User::create([
            'username' => 'user8',
            'password' => Hash::make('password8'),
            'name'     => 'Agnieszka',
            'surname'  => 'Piotrowska',
            'email'    => 'agnieszka.piotrowska@example.com',
            'phone'    => '+48 600 000 009',
            'birthday' => '1998-09-09',
            'image'    => null,
        ]);

        User::create([
            'username' => 'user9',
            'password' => Hash::make('password9'),
            'name'     => 'Paweł',
            'surname'  => 'Grabowski',
            'email'    => 'pawel.grabowski@example.com',
            'phone'    => '+48 600 000 010',
            'birthday' => '1999-10-10',
            'image'    => null,
        ]);
    }
}
