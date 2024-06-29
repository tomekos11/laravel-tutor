<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user__roles';

    protected $fillable = [
        'name'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function roleUsers()
    {
        return $this -> hasMany(UserRole::class, 'role_id', 'id');
    }
}
