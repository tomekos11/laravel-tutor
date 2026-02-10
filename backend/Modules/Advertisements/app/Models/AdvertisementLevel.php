<?php

namespace Modules\Advertisements\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Courses\Models\Level;

class AdvertisementLevel extends Model
{
    use HasFactory;

    protected $table = 'advertisement__advertisement_levels';

    protected $fillable = [
        'advertisement_id',
        'level_id',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }
}
