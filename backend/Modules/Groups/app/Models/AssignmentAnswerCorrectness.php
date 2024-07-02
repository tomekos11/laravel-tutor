<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Courses\Models\Question;

class AssignmentAnswerCorrectness extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__answers_correctness';
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

    public function question(){
        return $this -> belongsTo(Question::class, 'question_id', 'id');
    }

    public function assignmentAnswers(){
        return $this -> hasMany(AssignmentAnswer::class, 'answer_correctness_id', 'id');
    }

    public function assignment(){
        return $this -> belongsTo(Assignment::class, 'assignment_id', 'id');
    }
}
