<?php


namespace App\Services;


use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleService
{
    public function deleteRole(Role $role): ?bool
    {
        return $role->delete();
    }

    public function validator(array $postData, int $id = null): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [
            'title' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:191', 'unique:roles,code,' . $id],
            'is_deletable' => 'required',
            'description' => 'string'
        ];
        return Validator::make($postData, $rules);
    }

    public function createRole(array $postData)
    {
        return Role::create($postData);
    }

    public function updateRole(Role $role, array $postData)
    {
        return $role->update($postData);
    }

    public function getListDataForDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder $roles */

        $roles = Role::select([
            'roles.id as id',
            'roles.title',
            'roles.code',
            'roles.description',
            'roles.created_at',
            'roles.updated_at'
        ]);

        return DataTables::eloquent($roles)
            ->editColumn('description', static function (Role $role) {
                return Str::limit($role->description, '20', '...');
            })
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (Role $role) use ($authUser) {
                $str = '';
                if($authUser->can('rolePermission', $role)) {
                    $str .= '<a href="' . route('admin.roles.permissions', $role->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-cogs"></i> ' . __('Permissions') . ' </a>';
                }
                if($authUser->can('view', $role)) {
                    $str .= '<a href="' . route('admin.roles.show', $role->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }
                if($authUser->can('update', $role)) {
                    $str .= '<a href="' . route('admin.roles.edit', $role->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if($authUser->can('delete', $role)) {
                    $str .= '<a href="#" data-action="' . route('admin.roles.destroy', $role->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }

    public function syncRolePermission(Role $role, array $permissions): Role
    {
        $role->permissions()->sync($permissions);

        /** TODO: not a good idea */
        $role->users()->pluck('id')->each(function ($userId) {
            Cache::forget('userwise_permissions_' . $userId);
        });

        return $role;
    }
}
