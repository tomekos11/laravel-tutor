<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user_ratings';
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
