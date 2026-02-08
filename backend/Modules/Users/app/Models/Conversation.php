<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $owner_id
 * @property string $title
 * @property string $theme
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Conversation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user__conversations';
    protected $fillable = [
        'id',
        'title',
        'theme',
        'owner_id'
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function conversationUsers(){
        return $this -> hasMany(UserConversation::class, 'conversation_id', 'id');
    }
    public function messages(){
        return $this -> hasMany(Message::class, 'conversation_id', 'id');
    }
    public function user(){
        return $this -> belongsTo(User::class, 'owner_id', 'id');
    }
}
