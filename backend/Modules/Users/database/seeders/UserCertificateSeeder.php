<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\Certificate;
use Modules\Users\Models\UserRole;

class UserCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $tutorIds = UserRole::where('role_id', 2)
            ->pluck('user_id')
            ->all();

        foreach ($tutorIds as $userId) {
            Certificate::create([
                'user_id'          => $userId,
                'name'             => 'Certified Web Developer',
                'img'              => 'certificates/web-developer-'.$userId.'.png',
                'issued_by'        => 'Laravel Academy',
                'issue_identifier' => 'LARA-WEB-'.$userId.'-001',
                'issue_date'       => '2023-01-15',
                'link'             => 'https://certs.example.com/LARA-WEB-'.$userId.'-001',
            ]);

            Certificate::create([
                'user_id'          => $userId,
                'name'             => 'Advanced PHP & Laravel',
                'img'              => 'certificates/php-laravel-'.$userId.'.png',
                'issued_by'        => 'PHP Experts Group',
                'issue_identifier' => 'PHP-LAR-'.$userId.'-002',
                'issue_date'       => '2023-06-10',
                'link'             => 'https://certs.example.com/LARA-WEB-'.$userId.'-001',
            ]);

            Certificate::create([
                'user_id'          => $userId,
                'name'             => 'Online Teaching Fundamentals',
                'img'              => 'certificates/teaching-'.$userId.'.png',
                'issued_by'        => 'Online Teaching Institute',
                'issue_identifier' => 'TEACH-ONL-'.$userId.'-003',
                'issue_date'       => '2024-02-01',
                'link'             => 'https://certs.example.com/LARA-WEB-'.$userId.'-001',
            ]);
        }
    }
}
