<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user__messages';
    protected $fillable = [
        'conversation_id',
        'creator_id',
        'content',
        'img'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function conversation(){
        return $this -> belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    public function user(){
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }
}
