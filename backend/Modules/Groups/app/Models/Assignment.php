<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__assignments';
    protected $fillable = [
        'user_id',
        'assingment_date_id'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function answerCorectnesses(){
        return $this -> hasMany(AssignmentAnswerCorrectness::class, 'assignment_id', 'id');
    }

    public function assignmentDate(){
        return $this -> belongsTo(AssignemtDate::class, 'assignment_date_id', 'id');
    }

    public function user(){
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }
}
