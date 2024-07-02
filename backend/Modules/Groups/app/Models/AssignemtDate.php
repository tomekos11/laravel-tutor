<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Courses\Models\AssignmentQuestion;

class AssignemtDate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__assignments_dates';
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

    public function assignmentQuestions(){
        return $this -> hasMany(AssignmentQuestion::class, 'assignment_id', 'id');
    }

    public function assignments(){
        return $this -> hasMany(Assignment::class, 'assignment_date_id', 'id');
    }

    public function userGroup(){
        return $this -> belongsTo(UserGroup::class, 'user_group_id', 'id');
    }
}
