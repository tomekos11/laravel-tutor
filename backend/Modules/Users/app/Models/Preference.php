<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property string $tutoring_format
 * @property string $hourly_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Preference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Preference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Preference query()
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereHourlyPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereTutoringFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preference whereUserId($value)
 * @mixin \Eloquent
 */
class Preference extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user__preferences';

    protected $fillable = [
        'user_id',
        'tutoring_format',
        'hourly_price',
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function user(){
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }
}
