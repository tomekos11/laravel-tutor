<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

class Answer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__answers';
    protected $fillable = [
        'id',
        'creator_id',
        'question_id'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function answerRatings(){
        return $this -> hasMany(answerRating::class, 'answer_id', 'id');
    }

    public function user(){
        return $this -> belongsTo(User::class, 'creator_id', 'id');
    }

    public function question(){
        return $this -> belongsTo(Question::class, 'question_id', 'id');
    }
}
