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
    ];

    protected $casts = [
        'grade' => 'int',
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
