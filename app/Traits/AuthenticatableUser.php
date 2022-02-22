<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;
use App\Models\UsersPermission;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * @property-read  \Illuminate\Database\Eloquent\Collection roles
 * @property-read  \Illuminate\Database\Eloquent\Collection permissions
 */
trait AuthenticatableUser
{
    /**
     * Return default User Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Return alternative User Roles.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Return User Permissions.
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'users_permissions')->with('permission');
    }

    /**
     * users custom permission start
     */

    public function activePermissions(): HasMany
    {
        return $this->hasMany(UsersPermission::class)->where('status', true)->with('permission');
    }

    public function inActivePermissions(): HasMany
    {
        return $this->hasMany(UsersPermission::class)->where('status', false)->with('permission');
    }

    /**
     * Return all User Roles, merging the default and alternative roles.
     */
    public function rolesAll()
    {
        $this->loadRolesRelations();

        return collect([$this->role])->merge($this->roles);
    }

    /**
     * Check if User has a Role(s) associated.
     *
     * @param string|array $name The role(s) to check.
     *
     * @return bool
     */
    public function hasRole($name): bool
    {
        $roles = $this->rolesAll()->pluck('name')->toArray();

        foreach ((is_array($name) ? $name : [$name]) as $role) {
            if (in_array($role, $roles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set default User Role.
     *
     * @param string $name The role name to associate.
     * @return AuthenticatableUser
     */
    public function setRole(string $name): AuthenticatableUser
    {
        $role = Role::where('name', '=', $name)->first();

        if ($role) {
            $this->role()->associate($role);
            $this->save();
        }

        return $this;
    }

    public function loadRolesRelations(): void
    {
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }

        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }

        if (!$this->relationLoaded('activePermissions')) {
            $this->load('activePermissions');
        }

        if (!$this->relationLoaded('inActivePermissions')) {
            $this->load('inActivePermissions');
        }
    }

    public function loadPermissionsRelations(): void
    {
        $this->loadRolesRelations();

        if ($this->role && !$this->role->relationLoaded('permissions')) {
            $this->role->load('permissions');
            $this->load('roles.permissions');
        }
    }

    public function hasPermission($name)
    {
        $this->loadPermissionsRelations();

        return $this->roles_all()->contains($name);
    }

    /**
     * @return Collection
     */
    public function allPermissionKey(): Collection
    {
        $this->loadRolesRelations();
        $inactiveKeys = $this->getInactivePermissionKeys();

        $activeKeys = $this->getActivePermissionKeys();

        $defaultRolekeys = collect([]);
        if ($this->role) {
            $defaultRolekeys = $this->role->permissions()->pluck('key')->concat($activeKeys);
        }


        return $this->roles()->get()
            ->map(static function ($additionalRole) {
                return $additionalRole->permissions()->pluck('key')->all();
            })
            ->flatten()
            ->concat($defaultRolekeys)
            ->diff($inactiveKeys)
            ->unique();
    }

    public function getCustomPermissions()
    {
        return collect([$this->getActivePermissionKeys()])->merge($this->getInactivePermissionKeys())->flatten();
    }

    public function getActivePermissionKeys()
    {
        return $this->activePermissions()->get()->map(static function ($userPermission) {
            return $userPermission->permission->key;
        });
    }

    public function getInactivePermissionKeys()
    {
        return $this->inActivePermissions()->get()->map(static function ($userPermission) {
            return $userPermission->permission->key;
        });
    }

    public function roles_all()
    {
//        return $this->allPermissionKey();
        //TODO: not working
        return Cache::rememberForever('userwise_permissions_' . $this->id, function () {
            return $this->allPermissionKey();
        });
    }

    /**
     * All permission keys by role. not custom.
     */
    public function getAllRolePermissionKeys()
    {
        $this->loadPermissionsRelations();
        if ($this->role) {
            $defaultPermissionKeys = $this->role->permissions()->pluck('key');
        } else {
            $defaultPermissionKeys = collect([]);
        }

        if ($this->roles()->count()) {
            $additionalPermissionKeys = $this->roles()->get()
                ->map(static function ($additionalRole) {
                    return $additionalRole->permissions()->pluck('key')->all();
                })
                ->flatten();
        } else {
            $additionalPermissionKeys = collect([]);
        }

        return $defaultPermissionKeys
            ->concat($additionalPermissionKeys)
            ->unique();
    }
}
