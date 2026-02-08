<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUserId($value)
 * @mixin \Eloquent
 */
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
