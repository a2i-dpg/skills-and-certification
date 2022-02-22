<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


/**
 * App\Models\Role
 *
 * @property int $id
 * @property string code
 * @property string title
 * @property string description
 * @property-read Collection|Permission[] permissions
 * @property-read int|null permissions_count
 */
class Role extends BaseModel
{
    protected $guarded = [];

    public function users(): Builder
    {
        $userModel = User::class;

        return $this->belongsToMany($userModel, 'user_roles')
            ->select(app($userModel)->getTable() . '.*')
            ->union($this->hasMany($userModel))->getQuery();
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
