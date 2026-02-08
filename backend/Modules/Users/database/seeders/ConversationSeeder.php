<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Models\Conversation;
use Modules\Users\Models\UserConversation;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $conv1 = Conversation::create([
            'owner_id' => 1,
            'title'    => 'Wprowadzenie do platformy',
            'theme'    => 'general',
        ]);

        UserConversation::create([
            'member_id'         => 1,
            'conversation_id' => $conv1->id,
        ]);
        UserConversation::create([
            'member_id'         => 2,
            'conversation_id' => $conv1->id,
        ]);
        UserConversation::create([
            'member_id'         => 3,
            'conversation_id' => $conv1->id,
        ]);

        $conv2 = Conversation::create([
            'owner_id' => 3,
            'title'    => 'Konsultacje z tutorem',
            'theme'    => 'tutoring',
        ]);

        UserConversation::create([
            'member_id'         => 3,
            'conversation_id' => $conv2->id,
        ]);
        UserConversation::create([
            'member_id'         => 4,
            'conversation_id' => $conv2->id,
        ]);
        UserConversation::create([
            'member_id'         => 5,
            'conversation_id' => $conv2->id,
        ]);

        $conv3 = Conversation::create([
            'owner_id' => 3,
            'title'    => 'Laravel – pytania o Eloquent',
            'theme'    => 'laravel',
        ]);

        UserConversation::create([
            'member_id'         => 3,
            'conversation_id' => $conv3->id,
        ]);
        UserConversation::create([
            'member_id'         => 6,
            'conversation_id' => $conv3->id,
        ]);
        UserConversation::create([
            'member_id'         => 7,
            'conversation_id' => $conv3->id,
        ]);

        $conv4 = Conversation::create([
            'owner_id' => 7,
            'title'    => 'Przygotowanie do egzaminu',
            'theme'    => 'exam',
        ]);

        UserConversation::create([
            'member_id'         => 7,
            'conversation_id' => $conv4->id,
        ]);
        UserConversation::create([
            'member_id'         => 8,
            'conversation_id' => $conv4->id,
        ]);

        $conv5 = Conversation::create([
            'owner_id' => 8,
            'title'    => 'Code review zadań domowych',
            'theme'    => 'review',
        ]);

        UserConversation::create([
            'member_id'         => 8,
            'conversation_id' => $conv5->id,
        ]);
        UserConversation::create([
            'member_id'         => 4,
            'conversation_id' => $conv5->id,
        ]);
        UserConversation::create([
            'member_id'         => 5,
            'conversation_id' => $conv5->id,
        ]);

        $conv6 = Conversation::create([
            'owner_id' => 2,
            'title'    => 'Ogłoszenia dla studentów',
            'theme'    => 'announcements',
        ]);

        foreach ([2, 3, 4, 5, 6, 7, 8, 9] as $uid) {
            UserConversation::create([
                'member_id'         => $uid,
                'conversation_id' => $conv6->id,
            ]);
        }

        $conv7 = Conversation::create([
            'owner_id' => 4,
            'title'    => 'Pomoc techniczna',
            'theme'    => 'support',
        ]);

        foreach ([1, 4, 5, 6] as $uid) {
            UserConversation::create([
                'member_id'         => $uid,
                'conversation_id' => $conv7->id,
            ]);
        }

        $conv8 = Conversation::create([
            'owner_id' => 5,
            'title'    => 'Feedback o kursie',
            'theme'    => 'feedback',
        ]);

        foreach ([3, 5, 6, 7] as $uid) {
            UserConversation::create([
                'member_id'         => $uid,
                'conversation_id' => $conv8->id,
            ]);
        }

        $conv9 = Conversation::create([
            'owner_id' => 6,
            'title'    => 'Projekt grupowy – ustalenia',
            'theme'    => 'project',
        ]);

        foreach ([4, 5, 6, 7, 8] as $uid) {
            UserConversation::create([
                'member_id'         => $uid,
                'conversation_id' => $conv9->id,
            ]);
        }

        $conv10 = Conversation::create([
            'owner_id' => 9,
            'title'    => 'Moderacja dyskusji',
            'theme'    => 'moderation',
        ]);

        foreach ([2, 3, 9, 10] as $uid) {
            UserConversation::create([
                'member_id'         => $uid,
                'conversation_id' => $conv10->id,
            ]);
        }
    }
}
