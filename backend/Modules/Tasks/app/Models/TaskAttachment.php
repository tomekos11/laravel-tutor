<?php

namespace Modules\Tasks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Users\Models\User;

class TaskAttachment extends Model
{
    protected $table = 'task__attachments';

    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'uploaded_by',
        'path',
        'url',
        'original_name',
        'size',
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
