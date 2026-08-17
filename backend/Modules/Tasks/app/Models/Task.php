<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Users\Models\User;

class Task extends Model
{
    protected $table = 'task__tasks';

    protected $fillable = [
        'author_id',
        'title',
        'description',
        'subject',
        'category',
        'difficulty',
        'attachment_path',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function groupTasks(): HasMany
    {
        return $this->hasMany(GroupTask::class, 'task_id');
    }
}
