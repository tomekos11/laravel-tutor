<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGroupPermission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__user_groups_permissions';
    protected $fillable = [
        'user_groups_id',
        'permissions_id'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function permission(){
        return $this -> belongsTo(Permission::class, 'permissions_id', 'id');
    }
    public function userGroup(){
        return $this -> belongsTo(UserGroup::class, 'user_groups_id', 'id');
    }
}
