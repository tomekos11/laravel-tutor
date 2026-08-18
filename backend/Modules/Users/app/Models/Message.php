<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Advertisements\Models\Advertisement;

/**
 * @property int $id
 * @property int $conversation_id
 * @property int $creator_id
 * @property string $content
 * @property string|null $img
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        'img',
        'type',
        'advertisement_id',
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function conversation(){
        return $this -> belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    public function user(){
        return $this -> belongsTo(User::class, 'creator_id', 'id');
    }

    public function advertisement(){
        return $this -> belongsTo(Advertisement::class, 'advertisement_id', 'id');
    }
}
