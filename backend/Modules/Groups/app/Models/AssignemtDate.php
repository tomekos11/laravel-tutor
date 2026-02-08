<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Courses\Models\AssignmentQuestion;

/**
 * @property int $id
 * @property int $user_group_id
 * @property \Illuminate\Support\Carbon|null $open_date
 * @property \Illuminate\Support\Carbon|null $finish_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AssignemtDate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignemtDate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignemtDate query()
 * @method static \Illuminate\Database\Eloquent\Builder|AssignemtDate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignemtDate whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignemtDate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignemtDate whereOpenDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignemtDate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AssignemtDate whereUserGroupId($value)
 * @mixin \Eloquent
 */
class AssignemtDate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__assignments_dates';
    protected $fillable = [
        'user_group_id',
        'open_date',
        'finish_date'
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'open_date' => 'date',
        'finish_date' => 'date'
    ];

    public function assignmentQuestions(){
        return $this -> hasMany(AssignmentQuestion::class, 'assignment_id', 'id');
    }

    public function assignments(){
        return $this -> hasMany(Assignment::class, 'assignment_date_id', 'id');
    }

    public function userGroup(){
        return $this -> belongsTo(UserGroup::class, 'user_group_id', 'id');
    }
}
