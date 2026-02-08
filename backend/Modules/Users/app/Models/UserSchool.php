<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool query()
 * @mixin \Eloquent
 */
class UserSchool extends Model
{
    use HasFactory;

    protected $table = 'user__users_schools';

    protected $fillable = [
        'user_id',
        'school_id',
        'title',
        'type',
        'begin_date',
        'end_date',
    ];

    protected $casts = [
        'begin_date' => 'date',
        'end_date'   => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
