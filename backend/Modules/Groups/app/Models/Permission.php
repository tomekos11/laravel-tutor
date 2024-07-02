<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'group__permissions';
    protected $fillable = [
        'type'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function userGroupPermission(){
        return $this -> hasMany(UserGroupPermission::class, 'user_groups_id', 'id');
    }
}
