<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\UsersPermission
 *
 * @property int $id
 * @property int $user_id
 * @property int $permission_id
 * @property bool $status
 * @property-read Permission $permission
 */
class UsersPermission extends BaseModel
{
    public $timestamps = true;

    protected $table = 'users_permissions';
    protected $fillable = ['user_id', 'permission_id', 'status'];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class,'permission_id','id');
    }
}
