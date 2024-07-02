<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Groups\Models\AssignmentAnswerCorrectness;
use Modules\Users\Models\User;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__questions';
    protected $fillable = [
        'id',
        'creator_id',
        'content',
        'type'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];
    public function user(){
        return $this -> belongsTo(User::class, 'creator_id', 'id');
    }

    public function answers(){
        return $this -> hasMany(Answer::class, 'question_id', 'id');
    }

    public function questionRatings(){
        return $this -> hasMany(QuestionRating::class, 'question_id', 'id');
    }

    public function topicQuestions(){
        return $this -> hasMany(TopicQuestion::class, 'question_id', 'id');
    }

    public function assignmentQuestions(){
        return $this -> hasMany();
        //todo
    }
    public function answerCorrectnesses(){
        return $this -> hasMany(AssignmentAnswerCorrectness::class, 'question_id', 'id');
    }
}
