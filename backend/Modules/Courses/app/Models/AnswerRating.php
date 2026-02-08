<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

/**
 * @property int $id
 * @property int $reviewer_id
 * @property int $answer_id
 * @property int $mark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerRating query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerRating whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerRating whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerRating whereReviewerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerRating whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
