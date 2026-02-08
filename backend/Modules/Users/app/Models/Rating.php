<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating query()
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
