<?php

namespace Modules\Courses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Field extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'course__fields';
    protected $fillable = [
        'id',
        'name'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function courses(){
        return $this -> hasMany(Course::class, 'field_id', 'id');
    }
}
