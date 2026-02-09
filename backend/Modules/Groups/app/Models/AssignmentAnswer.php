<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $answer_correctness_id
 * @property bool $is_correct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswer whereAnswerCorrectnessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignmentAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AssignmentAnswer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__assignments_answers';
    protected $fillable = [
        'answer_correctness_id',
        'is_correct',
    ];
    protected $hidden = [

    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function answerCorrectness(){
        return $this -> belongsTo(AssignmentAnswerCorrectness::class, 'answer_correctness_id', 'id');
    }
}
