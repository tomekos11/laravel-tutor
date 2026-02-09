<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $img
 * @property string|null $link
 * @property string $issued_by
 * @property string $issue_identifier
 * @property \Illuminate\Support\Carbon $issue_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereIssueIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereIssuedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereUserId($value)
 * @mixin \Eloquent
 */
class Certificate extends Model
{
    use HasFactory;

    protected $table = 'user__certificates';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'img',
        'issued_by',
        'issue_identifier',
        'issue_date',
        'link',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'issue_date' => 'date'
    ];

    public function user(){
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }
}
