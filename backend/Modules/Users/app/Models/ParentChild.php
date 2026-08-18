<?php

namespace Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Link between a parent account and a child (student) account.
 *
 * @property int $id
 * @property int $parent_id
 * @property int $child_id
 * @property string $status 'pending'|'approved'
 * @property string $requested_by 'parent'|'child'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ParentChild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParentChild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParentChild query()
 * @mixin \Eloquent
 */
class ParentChild extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';

    public const REQUESTED_BY_PARENT = 'parent';
    public const REQUESTED_BY_CHILD = 'child';

    protected $table = 'user__parent_children';

    protected $fillable = [
        'parent_id',
        'child_id',
        'status',
        'requested_by',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->belongsTo(User::class, 'child_id', 'id');
    }
}
