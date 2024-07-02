<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignemtDate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__assingments_dates';
    protected $fillable = [
        'user_group_id',
        'open_date',
        'finish_date'
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'open_date' => 'date',
        'finish_date' => 'date'
    ];


}
