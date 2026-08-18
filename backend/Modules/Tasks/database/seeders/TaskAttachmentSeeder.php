<?php

namespace Modules\Tasks\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Tasks\Models\Task;
use Modules\Tasks\Models\TaskAttachment;
use Modules\Tasks\Models\TaskComment;
use Modules\Users\Models\User;

class TaskAttachmentSeeder extends Seeder
{
    /**
     * A tiny 1x1px placeholder PNG, reused as the seeded attachment file.
     */
    private const PLACEHOLDER_PNG_BASE64 = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id')->all();

        if (empty($userIds)) {
            return;
        }

        $path = $this->ensurePlaceholderFile();

        $task = Task::inRandomOrder()->first();

        if ($task) {
            TaskAttachment::create([
                'attachable_type' => Task::class,
                'attachable_id' => $task->id,
                'uploaded_by' => $userIds[array_rand($userIds)],
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'original_name' => 'przyklad-zadania.png',
                'size' => Storage::disk('public')->size($path),
            ]);
        }

        $comment = TaskComment::inRandomOrder()->first();

        if ($comment) {
            TaskAttachment::create([
                'attachable_type' => TaskComment::class,
                'attachable_id' => $comment->id,
                'uploaded_by' => $userIds[array_rand($userIds)],
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'original_name' => 'zrzut-ekranu.png',
                'size' => Storage::disk('public')->size($path),
            ]);
        }
    }

    /**
     * Ensure a placeholder image exists on the public disk and return its path.
     */
    private function ensurePlaceholderFile(): string
    {
        $path = 'task-attachments/seed-placeholder.png';

        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->put($path, base64_decode(self::PLACEHOLDER_PNG_BASE64));
        }

        return $path;
    }
}
