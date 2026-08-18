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

 /**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Modules\Users\Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
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

    /** Linki, w których ten użytkownik jest rodzicem. */
    public function childLinks(){
        return $this -> hasMany(ParentChild::class, 'parent_id', 'id');
    }

    /** Linki, w których ten użytkownik jest dzieckiem. */
    public function parentLinks(){
        return $this -> hasMany(ParentChild::class, 'child_id', 'id');
    }

    /** Zatwierdzone konta dzieci widoczne dla tego rodzica. */
    public function children(){
        return $this -> belongsToMany(User::class, 'user__parent_children', 'parent_id', 'child_id')
            -> wherePivot('status', ParentChild::STATUS_APPROVED)
            -> withPivot('id', 'status', 'requested_by', 'created_at');
    }

    /** Zatwierdzeni rodzice tego konta (dziecka). */
    public function parents(){
        return $this -> belongsToMany(User::class, 'user__parent_children', 'child_id', 'parent_id')
            -> wherePivot('status', ParentChild::STATUS_APPROVED)
            -> withPivot('id', 'status', 'requested_by', 'created_at');
    }

    public function hasRole(string $role): bool
    {
        $this->loadMissing('userRoles.role');

        return $this->userRoles->contains(fn ($userRole) => $userRole->role && $userRole->role->name === $role);
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
        return $this -> hasMany(UserConversation::class, 'member_id', 'id');
    }

    public function conversations(){
        return $this -> hasMany(Conversation::class, 'owner_id', 'id');
    }

    /** Wszystkie konwersacje, w których użytkownik jest uczestnikiem (1-1 i grupowe). */
    public function memberConversations()
    {
        return $this->belongsToMany(
            \Modules\Users\Models\Conversation::class,
            'user__users_conversations',
            'member_id',
            'conversation_id'
        )->withPivot('last_read_at');
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

    public function givenRatings()
    {
        return $this->hasMany(Rating::class, 'reviewer_id', 'id');
    }

    public function receivedRatings()
    {
        return $this->hasMany(Rating::class, 'tutor_id', 'id');
    }

    public function advertisements()
    {
        return $this->hasMany(\Modules\Advertisements\Models\Advertisement::class, 'user_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Users\Database\Factories\UserFactory::new();
    }

}
