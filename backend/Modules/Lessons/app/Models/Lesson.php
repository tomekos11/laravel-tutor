<?php

namespace Modules\Lessons\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $table = 'lesson__lessons';

    protected $fillable = [
        'group_id',
        'start_time',
        'end_time',
        'real_end_time',
        'shared',
        'notes',
    ];

    protected $casts = [
        'start_time'    => 'datetime',
        'end_time'      => 'datetime',
        'real_end_time' => 'datetime',
        'shared'        => 'bool',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(\Modules\Groups\Models\Group::class, 'group_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(LessonAttachment::class, 'lesson_id');
    }

    public function userLessons(): HasMany
    {
        return $this->hasMany(UserLesson::class, 'lesson_id');
    }
}
