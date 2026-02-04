<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Groups\Models\AssignemtDate;

class AssignmentQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__assignments_questions';
    protected $fillable = [
        'id',
        'assignment_date_id',
        'question_id'
    ];
    public function question(){
        return $this -> belongsTo(Question::class, 'question_id', 'id');
    }

    public function assignmentDate(){
        return $this -> belongsTo(AssignemtDate::class, 'assignment_id', 'id');
    }
}
