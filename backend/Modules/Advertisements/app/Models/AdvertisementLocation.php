<?php

namespace Modules\Advertisements\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Advertisements\Models\Location;

class AdvertisementLocation extends Model
{
    use HasFactory;

    protected $table = 'advertisement__advertisement_locations';

    protected $fillable = [
        'advertisement_id',
        'location_id',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
