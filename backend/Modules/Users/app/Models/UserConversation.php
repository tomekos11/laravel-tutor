<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
