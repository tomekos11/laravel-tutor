<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Users\Models\User;

class TaskComment extends Model
{
    protected $table = 'task__comments';

    protected $fillable = [
        'task_id',
        'user_id',
        'content',
        'is_pinned',
        'pinned_rating',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(TaskAttachment::class, 'attachable');
    }
}
