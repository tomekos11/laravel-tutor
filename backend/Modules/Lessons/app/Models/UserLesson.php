<?php

namespace Modules\Lessons\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLesson extends Model
{
    protected $table = 'lesson__user_lessons';

    protected $fillable = [
        'lesson_id',
        'user_id',
        'grade',
        'comment',
        'absence_reported',
        'absence_reason',
        'absence_reported_at',
    ];

    protected $casts = [
        'grade' => 'int',
        'absence_reported' => 'bool',
        'absence_reported_at' => 'datetime',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\Modules\Users\Models\User::class, 'user_id');
    }
}
