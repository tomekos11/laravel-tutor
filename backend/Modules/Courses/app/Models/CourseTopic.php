<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseTopic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__courses_topics';
    protected $fillable = [
        'id',
        'topic_id',
        'course_id'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function course(){
        return $this -> belongsTo(Course::class, 'course_id', 'id');
    }
    public function topic(){
        return $this -> belongsTo(Topic::class, 'topic_id', 'id');
    }
}
