<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__topics';
    protected $fillable = [
        'id',
        'name'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function courseTopics(){
        return $this -> hasMany(courseTopic::class, 'topic_id', 'id');
    }
    public function topicQuestions(){
        return $this -> hasMany(TopicQuestion::class, 'topic_id', 'id');
    }
}
