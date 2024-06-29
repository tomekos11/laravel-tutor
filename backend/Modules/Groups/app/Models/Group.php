<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__groups';
    protected $fillable = [
        'course_id',
        'name'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    //todo relacja z kursami.
}
