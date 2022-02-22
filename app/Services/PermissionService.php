<?php


namespace App\Services;


use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionService
{
    public function deletePermission(Permission $permission): ?bool
    {
        return $permission->delete();
    }

    public function validator(array $postData, int $id = null): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($postData, $this->validationRules($id));
    }

    public function validationRules(int $id = null): array
    {
        return [
            'key' => ['required', 'string', 'max:191', 'unique:permissions,key,' . $id],
            'table_name' => ['required', 'string', 'max:191'],
            'sub_group' => ['nullable', 'string', 'max:191'],
            'sub_group_order' => ['nullable', 'numeric'],
            'is_user_defined' => ['nullable', 'boolean'],
        ];
    }

    public function createPermission(array $postData)
    {
        return Permission::create($postData);
    }

    public function updatePermission(Permission $permission, array $postData)
    {
        return $permission->update($postData);
    }

    public function getListDataForDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder $permissions */

        $permissions = Permission::select([
            'permissions.id as id',
            'permissions.key',
            'permissions.table_name',
            'permissions.sub_group',
            'permissions.sub_group_order',
            'permissions.is_user_defined',
            'permissions.created_at',
            'permissions.updated_at'
        ]);

        return DataTables::eloquent($permissions)
            ->editColumn('is_user_defined', static function (Permission $permission) {
                return $permission->is_user_defined ? 'Yes' : 'No';
            })
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (Permission $permission) use($authUser) {
                $str = '';
                if($authUser->can('view', $permission)) {
                    $str .= '<a href="' . route('admin.permissions.show', $permission->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }
                if($authUser->can('update', $permission)) {
                    $str .= '<a href="' . route('admin.permissions.edit', $permission->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if($authUser->can('delete', $permission)) {
                    $str .= '<a href="#" data-action="' . route('admin.permissions.destroy', $permission->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }
}
