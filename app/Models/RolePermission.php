<?php

namespace App\Models;

/**
 * App\Models\RolePermission
 *
 * @property int $permission_id
 * @property int $role_id
 */
class RolePermission extends BaseModel
{
    protected $table = 'permission_role';

}
