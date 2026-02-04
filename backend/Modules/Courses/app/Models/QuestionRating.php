<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

class QuestionRating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__questions_ratings';
    protected $fillable = [
        'id',
        'reviewer_id',
        'question_id',
        'mark'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function question(){
        return $this -> belongsTo(Question::class, 'question_id', 'id');
    }

    public function user(){
        return $this -> belongsTo(User::class, 'reviewer_id', 'id');
    }
}
