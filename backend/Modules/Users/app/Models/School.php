<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'schools';
    protected $fillable = [
        'name',
        'type',
        'coordinates',
        'city',
        'country'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function schoolUsers(){
        return $this -> hasMany(UserSchool::class, 'school_id', 'id');
    }
}
