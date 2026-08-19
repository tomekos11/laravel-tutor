<?php

namespace Modules\Advertisements\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Advertisements\Models\Advertisement;
use Modules\Users\Models\User;
use Modules\Courses\Models\Field;
use Modules\Courses\Models\Level;
use Modules\Courses\Models\Course;
use Modules\Groups\Models\Group;
use Modules\Groups\Models\UserGroup;
use Modules\Users\Models\UserRole;

class AdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $userIds  = User::pluck('id')->all();
        $fieldIds = Field::pluck('id')->all();
        $levelIds = Level::pluck('id')->all();

        if (empty($userIds) || empty($fieldIds)) {
            return;
        }

        // Licznik grup na twórcę - do generowania nazw grup zgodnie z konwencją
        // "Kategoria / rok / nr grupy twórcy / poziom", tak jak w GroupsController.
        $ownerGroupCounts = [];

        // Uczniowie, których można dopisać jako członków grup zajęciowych podpiętych do ogłoszeń.
        $studentIds = UserRole::where('role_id', 1)->pluck('user_id')->all();
        if (empty($studentIds)) {
            $studentIds = $userIds;
        }

        for ($i = 1; $i <= 10; $i++) {
            $userId  = $userIds[array_rand($userIds)];
            $fieldId = $fieldIds[array_rand($fieldIds)];
            $levelId = !empty($levelIds) ? $levelIds[array_rand($levelIds)] : null;

            $ad = Advertisement::create([
                'user_id'     => $userId,
                'field_id'    => $fieldId,
                'price'       => random_int(50, 200), // zł
                'description' => 'Oferta korepetycji nr ' . $i,
                'address'     => 'Rzeszów, ul. Przykładowa ' . $i,
            ]);

            // Co drugie ogłoszenie - zajęcia grupowe, reszta indywidualne (min=max=1).
            $isGroupClass = $i % 2 === 0;
            $minMembers = $isGroupClass ? random_int(2, 4) : 1;
            $maxMembers = $isGroupClass ? $minMembers + random_int(0, 4) : 1;

            $ownerGroupCounts[$userId] = ($ownerGroupCounts[$userId] ?? 0) + 1;

            $fieldName = Field::find($fieldId)?->name;
            $levelName = $levelId ? Level::find($levelId)?->name : null;

            $groupName = implode(' / ', array_filter([
                $fieldName ?: 'Korepetycje',
                (string) now()->year,
                (string) $ownerGroupCounts[$userId],
                $levelName,
            ], static fn ($part) => $part !== null && $part !== ''));

            $course = $levelId
                ? Course::firstOrCreate(['field_id' => $fieldId, 'level_id' => $levelId])
                : null;

            // Każde ogłoszenie musi mieć przypisaną co najmniej jedną (wymaganą) grupę zajęciową.
            $group = Group::create([
                'name'             => $groupName,
                'course_id'        => $course?->id,
                'advertisement_id' => $ad->id,
                'min_members'      => $minMembers,
                'max_members'      => $maxMembers,
            ]);

            UserGroup::create([
                'user_id'  => $userId,
                'group_id' => $group->id,
                'is_owner' => true,
            ]);

            // Dopisz uczniów do grupy, żeby zajęcia grupowe miały realnych członków.
            $candidates = array_values(array_diff($studentIds, [$userId]));
            shuffle($candidates);

            $membersCount = max($minMembers, min($maxMembers, count($candidates)));
            $members = array_slice($candidates, 0, $membersCount);

            foreach ($members as $memberId) {
                UserGroup::create([
                    'user_id'  => $memberId,
                    'group_id' => $group->id,
                    'is_owner' => false,
                ]);
            }
        }
    }
}
