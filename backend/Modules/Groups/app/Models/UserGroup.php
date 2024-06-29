<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

class UserGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__users_groups';
    protected $fillable = [
        'user_id',
        'group_id',
        'is_owner'
    ];

    protected $hidden = [

    ];

    protected $casts = [
        'is_owner' => 'boolean'
    ];

    public function userGroupPermissions(){
        return $this -> hasMany(UserGroupPermission::class, 'user_groups_id', 'id');
    }

    public function assignment(){
        return $this -> hasMany(Assignment::class, 'user_group_id', 'id');
    }

    public function user(){
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }

    public function group(){
        return $this -> belongsTo(Group::class, 'group_id', 'id');
    }
}
