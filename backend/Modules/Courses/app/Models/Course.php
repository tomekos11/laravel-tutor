<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Groups\Models\Group;

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
