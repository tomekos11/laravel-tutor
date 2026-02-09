<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $school_id
 * @property string $title
 * @property string $type
 * @property \Illuminate\Support\Carbon $begin_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Users\Models\School $school
 * @property-read \Modules\Users\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool whereBeginDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool whereSchoolId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSchool whereUserId($value)
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
