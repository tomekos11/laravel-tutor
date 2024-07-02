<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Level extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course_levels';
    protected $fillable = [
        'id',
        'name'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function courses(){
        return $this -> hasMany(Course::class, 'level_id', 'id');
    }
}
