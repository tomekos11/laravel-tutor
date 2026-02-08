<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Groups\Models\AssignemtDate;

/**
 * @property int $id
 * @property int $assignment_date_id
 * @property int $question_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentQuestion whereAssignmentDateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentQuestion whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssignmentQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__assignments_questions';
    protected $fillable = [
        'id',
        'assignment_date_id',
        'question_id'
    ];
    public function question(){
        return $this -> belongsTo(Question::class, 'question_id', 'id');
    }

    public function assignmentDate(){
        return $this -> belongsTo(AssignemtDate::class, 'assignment_id', 'id');
    }
}
