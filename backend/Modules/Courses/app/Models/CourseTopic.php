<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $topic_id
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTopic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTopic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTopic query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTopic whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTopic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTopic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTopic whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseTopic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
