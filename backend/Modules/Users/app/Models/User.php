<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Groups\Models\UserGroup;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user__users';

    protected $fillable = [
        'username',
        'password',

        'name',
        'surname',
        'email',
        'phone',
        'birthday',
        'image',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birthday' => 'datetime'
    ];

    public function userRoles(){
        return $this -> hasMany(UserRole::class, 'user_id', 'id');
    }

    public function preference(){
        return $this -> hasOne(Preference::class, 'user_id', 'id');
    }

    public function certificates(){
        return $this -> hasMany(Certificate::class, 'user_id', 'id');
    }

    public function userSchools(){
        return $this -> hasMany(UserSchool::class, 'user_id', 'id');
    }

    public function userGroups(){
        return $this -> hasMany(UserGroup::class, 'user_id', 'id');
    }

    public function usersConversations(){
        return $this -> hasMany(UserConversation::class, 'user_id', 'id');
    }

    public function conversations(){
        return $this -> hasMany(Conversation::class, 'owner_id', 'id');
    }

    public function messages(){
        return $this -> hasMany(Message::class, 'user_id', 'id');
    }
}
