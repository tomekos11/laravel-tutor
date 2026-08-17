<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Groups\Models\Group;

class GroupTask extends Model
{
    protected $table = 'task__group_tasks';

    protected $fillable = [
        'task_id',
        'group_id',
        'assigned_by',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class, 'group_task_id');
    }
}
