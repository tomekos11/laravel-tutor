<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user__conversations';
    protected $fillable = [
        'id',
        'titile',
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
