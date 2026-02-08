<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

/**
 * @property int $id
 * @property int $reviewer_id
 * @property int $question_id
 * @property int $mark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionRating query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionRating whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionRating whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionRating whereReviewerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionRating whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
