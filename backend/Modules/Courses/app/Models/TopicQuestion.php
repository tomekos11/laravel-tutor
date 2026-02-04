<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopicQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__topics_questions';
    protected $fillable = [
        'id',
        'topic_id',
        'question_id'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function question(){
        return $this -> belongsTo(Question::class, 'question_id', 'id');
    }

    public function topic(){
        return $this -> belongsTo(Topic::class, 'topic_id', 'id');
    }
}
