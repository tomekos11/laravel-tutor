<?php

namespace Modules\Advertisements\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Models\User;
use Modules\Courses\Models\Field;
use Modules\Courses\Models\Level;
use Modules\Groups\Models\Group;

class Advertisement extends Model
{
    use HasFactory;

    protected $table = 'advertisement__advertisements';

    protected $fillable = [
        'user_id',
        'price',
        'description',
        'field_id',
        'address',
        'travel_radius_km',
        'price_per_km',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_km' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id', 'id');
    }

    public function levels()
    {
        return $this->belongsToMany(
            Level::class,
            'advertisement__advertisement_levels',
            'advertisement_id',
            'level_id'
        );
    }

    public function locations()
    {
        return $this->belongsToMany(
            Location::class,
            'advertisement__advertisement_locations',
            'advertisement_id',
            'location_id'
        );
    }

    public function advertisementLevels()
    {
        return $this->hasMany(AdvertisementLevel::class, 'advertisement_id', 'id');
    }

    public function advertisementLocations()
    {
        return $this->hasMany(AdvertisementLocation::class, 'advertisement_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'advertisement_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Advertisements\Database\Factories\AdvertisementFactory::new();
    }
}
