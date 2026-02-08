<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $member_id
 * @property int $conversation_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserConversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserConversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserConversation query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserConversation whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserConversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserConversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserConversation whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserConversation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserConversation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user__users_conversations';
    protected $fillable = [
        'member_id',
        'conversation_id',
    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function user(){
        return $this -> belongsTo(User::class, 'member_id', 'id');
    }

    public function conversation(){
        return $this -> belongsTo(Conversation::class, 'conversation_id', 'id');
    }
}
