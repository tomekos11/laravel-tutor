<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'user__cerfificates';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'img',
        'issued_by',
        'issue_identifier',
        'issue_date'
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
