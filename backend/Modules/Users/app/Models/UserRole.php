<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */

    protected $table = 'user__users_roles';
    protected $fillable = [
        'user_id',
        'role_id'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function user(){
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }
    public function role(){
        return $this -> belongsTo(Role::class, 'role_id', 'id');
    }
}
