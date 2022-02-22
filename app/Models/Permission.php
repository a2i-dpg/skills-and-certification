<?php

namespace App\Models;

use Illuminate\Support\Str;


/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $key
 * @property string|null $table_name
 * @property string|null $sub_group
 * @property int $sub_group_order
 * @property bool $is_user_defined
 * @property-read \Illuminate\Database\Eloquent\Collection|Role[] $roles
 * @property-read int|null $roles_count
 */
class Permission extends BaseModel
{
    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public static function getIsUserDefinedOptions(): array
    {
        return [
            '0' => __('generic.no'),
            '1' => __('generic.yes'),
        ];
    }

    public static function generateFor($table_name, $permissionKeys = []): void
    {
        $existingList = self::select('key')
            ->where('table_name', $table_name)
            ->get()
            ->keyBy('key');

        if (empty($permissionKeys)) {
            $permissionKeys = ['view_any', 'view_single', 'create', 'update', 'delete', 'restore', 'force_delete'];
        }

        foreach ($permissionKeys as $key) {
            if (!$existingList->has($key . "_" . Str::singular($table_name))) {
                self::create(['key' => $key . "_" . Str::singular($table_name), 'table_name' => $table_name]);
            }
        }
    }

    public static function removeFrom($table_name): void
    {
        self::where(['table_name' => $table_name])->delete();
    }
}
