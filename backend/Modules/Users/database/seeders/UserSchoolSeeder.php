<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\UserSchool;

class UserSchoolSeeder extends Seeder
{
    public function run(): void
    {
        UserSchool::create([
            'user_id'    => 1,
            'school_id'  => 1,
            'title'      => 'Uczeń',
            'type'       => 'primary',
            'begin_date' => '2005-09-01',
            'end_date'   => '2011-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 1,
            'school_id'  => 2,
            'title'      => 'Uczeń',
            'type'       => 'high',
            'begin_date' => '2011-09-01',
            'end_date'   => '2014-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 2,
            'school_id'  => 3,
            'title'      => 'Uczeń',
            'type'       => 'primary',
            'begin_date' => '2006-09-01',
            'end_date'   => '2012-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 3,
            'school_id'  => 4,
            'title'      => 'Uczeń',
            'type'       => 'high',
            'begin_date' => '2007-09-01',
            'end_date'   => '2013-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 4,
            'school_id'  => 5,
            'title'      => 'Student',
            'type'       => 'university',
            'begin_date' => '2008-09-01',
            'end_date'   => '2014-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 5,
            'school_id'  => 6,
            'title'      => 'Uczeń',
            'type'       => 'music',
            'begin_date' => '2009-09-01',
            'end_date'   => '2015-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 6,
            'school_id'  => 7,
            'title'      => 'Uczeń',
            'type'       => 'art',
            'begin_date' => '2010-09-01',
            'end_date'   => '2016-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 7,
            'school_id'  => 8,
            'title'      => 'Uczeń',
            'type'       => 'technical',
            'begin_date' => '2011-09-01',
            'end_date'   => '2017-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 8,
            'school_id'  => 9,
            'title'      => 'Uczeń',
            'type'       => 'primary',
            'begin_date' => '2012-09-01',
            'end_date'   => '2018-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 9,
            'school_id'  => 10,
            'title'      => 'Uczeń',
            'type'       => 'high',
            'begin_date' => '2013-09-01',
            'end_date'   => '2019-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 10,
            'school_id'  => 4,
            'title'      => 'Uczeń',
            'type'       => 'high',
            'begin_date' => '2010-09-01',
            'end_date'   => '2013-06-30',
        ]);

        UserSchool::create([
            'user_id'    => 10,
            'school_id'  => 5,
            'title'      => 'Student',
            'type'       => 'university',
            'begin_date' => '2013-09-01',
            'end_date'   => '2016-06-30',
        ]);
    }
}
