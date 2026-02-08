<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Groups\Models\Group;

/**
 * @property int $id
 * @property int $level_id
 * @property int $field_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereFieldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__courses';
    protected $fillable = [
        'id',
        'level_id',
        'field_id'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function level(){
        return $this -> belongsTo(Level::class, 'level_id', 'id');
    }

    public function field(){
        return $this -> belongsTo(Field::class, 'field_id', 'id');
    }

    public function courseTopics(){
        return $this -> hasMany(CourseTopic::class, 'course_id', 'id');
    }

    public function groups(){
        return $this -> hasMany(Group::class, 'course_id', 'id');
    }
}
