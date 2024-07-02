<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSchool extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [

    ];

    protected  $hidden = [

    ];
    protected $casts = [
        'begin_date' => 'date',
        'end_date' => 'date'
    ];

    public function school(){
        return $this -> belongsToMany(School::class, 'school_id', 'id');
    }

    public function user(){
        return $this -> belongsToMany(User::class, 'user_id', 'id');
    }
}
