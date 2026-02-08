<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $coordinates
 * @property string $city
 * @property string $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|School newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|School newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|School query()
 * @method static \Illuminate\Database\Eloquent\Builder|School whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|School whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'user__schools';
    protected $fillable = [
        'name',
        'type',
        'coordinates',
        'city',
        'country'
    ];
    protected $hidden = [

    ];

    protected $casts = [

    ];

    public function schoolUsers(){
        return $this -> hasMany(UserSchool::class, 'school_id', 'id');
    }
}
