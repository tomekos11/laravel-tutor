<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Courses\Models\Answer;
use Modules\Courses\Models\AnswerRating;
use Modules\Courses\Models\Question;
use Modules\Courses\Models\QuestionRating;
use Modules\Groups\Models\Assignment;
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
    public function answers(){
        return $this -> hasMany(Answer::class, 'creator_id', 'id');
    }

    public function answerRatings(){
        return $this -> hasMany(AnswerRating::class, 'reviewer_id', 'id');
    }

    public function questions(){
        return $this -> hasMany(Question::class, 'creator_id', 'id');
    }
    public function questionRatings(){
        return $this -> hasMany(QuestionRating::class, 'reviewer_id', 'id');
    }
    public function assignments(){
        return $this -> hasMany(Assignment::class, 'user_id', 'id');
    }
}
