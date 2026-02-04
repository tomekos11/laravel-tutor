<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

class AnswerRating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__answers_ratings';
    protected $fillable = [
        'id',
        'reviewer_id',
        'answer_id',
        'mark'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function answer(){
        return $this -> belongsTo(Answer::class, 'answer_id', 'id');
    }

    public function user(){
        return $this -> belongsTo(User::class, 'reviewer_id', 'id');
    }
}
