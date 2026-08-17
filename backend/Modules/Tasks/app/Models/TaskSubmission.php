<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Users\Models\User;

class TaskSubmission extends Model
{
    protected $table = 'task__submissions';

    protected $fillable = [
        'group_task_id',
        'user_id',
        'status',
        'content',
        'submitted_at',
        'grade',
        'feedback',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'grade' => 'int',
    ];

    public function groupTask(): BelongsTo
    {
        return $this->belongsTo(GroupTask::class, 'group_task_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
