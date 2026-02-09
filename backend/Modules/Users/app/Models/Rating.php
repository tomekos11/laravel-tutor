<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $tutor_id
 * @property int $reviewer_id
 * @property int $tutor_made_this_review
 * @property int $rating
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereReviewerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereTutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereTutorMadeThisReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Rating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user__ratings';
    protected $fillable = [
      'tutor_id',
      'reviewer_id',
      'tutor_made_this_review',
      'rating',
      'description'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];
    public function reviewer(){
        return $this -> belongsTo(User::class, 'reviewer_id', 'id');
    }
    public function ratedUser(){
        return $this -> belongsTo(User::class, 'tutor_id', 'id');
    }
}
