<?php

namespace Modules\Books\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Tasks\Models\Task;

class Book extends Model
{
    protected $table = 'book__books';

    protected $fillable = [
        'title',
        'author',
        'language',
        'subject',
        'publisher',
        'isbn',
        'publication_year',
        'edition',
        'cover_path',
        'description',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'book_id');
    }
}
