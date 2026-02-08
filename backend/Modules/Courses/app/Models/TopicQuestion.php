<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $topic_id
 * @property int $question_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TopicQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicQuestion whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
