<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Courses\Models\Question;

/**
 * @property int $id
 * @property int $assignment_id
 * @property int $question_id
 * @property bool $is_correct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswerCorrectness newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswerCorrectness newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswerCorrectness query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswerCorrectness whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswerCorrectness whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswerCorrectness whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswerCorrectness whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswerCorrectness whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswerCorrectness whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssignmentAnswerCorrectness extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__answers_correctness';
    protected $fillable = [
        'assignment_id',
        'question_id',
        'is_correct'
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function question(){
        return $this -> belongsTo(Question::class, 'question_id', 'id');
    }

    public function assignmentAnswers(){
        return $this -> hasMany(AssignmentAnswer::class, 'answer_correctness_id', 'id');
    }

    public function assignment(){
        return $this -> belongsTo(Assignment::class, 'assignment_id', 'id');
    }
}
