<?php

namespace Softbd\MenuBuilder\Policies;

use App\Models\User;
use App\Policies\BasePolicy;

class MenuItemPolicy extends BasePolicy
{
    protected static $permissions = null;

    /**
     * Handle all requested permission checks.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return bool
     */
    public function __call($name, $arguments)
    {
        if (count($arguments) < 2) {
            throw new \InvalidArgumentException('not enough arguments');
        }
        /** @var User $user */
        $user = $arguments[0];

        /** @var $model */
        $model = $arguments[1];

        return $this->checkPermission($user, $model, $name);
    }

    /**
     * Check if user has an associated permission.
     * @param \App\Models\User $user
     * @param object $model
     * @param string $action
     * @return bool
     */
    protected function checkPermission(User $user, $model, $action): bool
    {
        if ($model->permission_key) {
            return $user->hasPermission($model->permission_key);
        }

        return true;
    }
}
