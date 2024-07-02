<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentAnswerCorrectness extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__assignments_answers_correctness';
    protected $fillable = [
        'assignment_id',
        'question_id',
        'is_correct'
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

}
