<?php

namespace Modules\Advertisements\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $table = 'advertisement__locations';

     protected $fillable = [
        'name',
    ];

    public function advertisements()
    {
        return $this->belongsToMany(
            Advertisement::class,
            'advertisement__advertisement_locations',
            'location_id',
            'advertisement_id'
        );
    }
}
