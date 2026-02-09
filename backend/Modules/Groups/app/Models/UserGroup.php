<?php

namespace Modules\Groups\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;

/**
 * @property int $id
 * @property int $user_id
 * @property int $group_id
 * @property bool $is_owner
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserGroup whereUserId($value)
 * @mixin \Eloquent
 */
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

    public function assignmentDates(){
        return $this -> hasMany(AssignmentDate::class, 'user_group_id', 'id');
    }

    public function user(){
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }

    public function group(){
        return $this -> belongsTo(Group::class, 'group_id', 'id');
    }
}
