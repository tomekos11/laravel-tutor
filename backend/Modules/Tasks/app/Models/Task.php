<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Users\Models\User;
use Modules\Books\Models\Book;

class Task extends Model
{
    protected $table = 'task__tasks';

    protected $fillable = [
        'author_id',
        'book_id',
        'book_task_number',
        'title',
        'description',
        'subject',
        'category',
        'difficulty',
        'attachment_path',
        'solved',
    ];

    protected $casts = [
        'solved' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function groupTasks(): HasMany
    {
        return $this->hasMany(GroupTask::class, 'task_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'task_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(TaskRating::class, 'task_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(TaskAttachment::class, 'attachable');
    }
}
