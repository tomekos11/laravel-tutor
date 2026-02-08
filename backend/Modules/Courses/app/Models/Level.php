<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|Level newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Level newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Level query()
 * @mixin \Eloquent
 */
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
