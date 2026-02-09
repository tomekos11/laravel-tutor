<?php

namespace Modules\Lessons\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonAttachment extends Model
{
    protected $table = 'lesson__lesson_attachments';

    protected $fillable = [
        'lesson_id',
        'filename',
        'path',
        'mime_type',
        'size',
        'title',
        'description',
    ];

    protected $casts = [
        'size' => 'int',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}
