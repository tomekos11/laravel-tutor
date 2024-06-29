<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentAnswer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__assignments_answers';
    protected $fillable = [
        'assignment_answer_correctness_id',
        'is_correct',
    ];
    protected $hidden = [

    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

}
