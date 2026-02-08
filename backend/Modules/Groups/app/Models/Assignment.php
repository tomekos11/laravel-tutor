<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $assignemnt_date_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereAssignemntDateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Assignment whereUserId($value)
 * @mixin \Eloquent
 */
class Assignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__assignments';
    protected $fillable = [
        'user_id',
        'assingment_date_id'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function answerCorectnesses(){
        return $this -> hasMany(AssignmentAnswerCorrectness::class, 'assignment_id', 'id');
    }

    public function assignmentDate(){
        return $this -> belongsTo(AssignemtDate::class, 'assignment_date_id', 'id');
    }

    public function user(){
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }
}
