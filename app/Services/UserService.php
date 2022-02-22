<?php


namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\BaseModel;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    public function createUser(array $data): User
    {
        if (!empty($data['profile_pic'])) {
            $filename = FileHandler::storePhoto($data['profile_pic'], User::PROFILE_PIC_FOLDER_NAME);
            $data['profile_pic'] = $filename ? User::PROFILE_PIC_FOLDER_NAME . '/' . $filename : User::DEFAULT_PROFILE_PIC;
        }

        $data['password'] = Hash::make($data['password']);

        /** @var UserType $userType */
        $userType = UserType::findOrFail($data['user_type_id']);
        $data['role_id'] = $userType->default_role_id;

        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        if ($authUser) {
            $data = $this->setACLData($data);
        }

        return User::create($data);
    }

    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'name' => [
                'bail',
                'required',
                'string'
            ],
            'email' => [
                'bail',
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'user_type_id' => [
                'bail',
                'required',
                'exists:user_types,code'
            ],
            'institute_id' => [
                'requiredIf:user_type_id,' . UserType::USER_TYPE_INSTITUTE_USER_CODE . ',' . UserType::USER_TYPE_BRANCH_USER_CODE . ',' . UserType::USER_TYPE_TRAINING_CENTER_USER_CODE . ',' . UserType::USER_TYPE_TRAINER_USER_CODE,
                'int',
                'exists:institutes,id'
            ],
            'branch_id' => [
                'requiredIf:user_type_id,' . UserType::USER_TYPE_BRANCH_USER_CODE,
                'int',
                'exists:branches,id'
            ],
            'training_center_id' => [
                'requiredIf:user_type_id,' . UserType::USER_TYPE_TRAINING_CENTER_USER_CODE,
                'int',
                'exists:training_centers,id'
            ],
            'password' => [
                'bail',
                new RequiredIf(!$id),
                'confirmed'
            ],
            'profile_pic' => 'nullable|mimes:jpeg,jpg,png,gif|max:10000',
            'row_status' => [Rule::requiredIf($id), Rule::in([BaseModel::ROW_STATUS_ACTIVE, BaseModel::ROW_STATUS_INACTIVE, BaseModel::ROW_STATUS_DELETED])],
        ];

        if (AuthHelper::getAuthUser()->id == $id && !empty($request->input('password'))) {
            $rules['old_password'] = [
                'bail',
                static function ($attribute, $value, $fail) {
                    if (!Hash::check($value, AuthHelper::getAuthUser()->password)) {
                        $fail(__('Credentials does not match.'));
                    }
                }
            ];
        }

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function updateUser(User $user, array $data): User
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        if (!empty($data['profile_pic'])) {
            if (!empty($user->profile_pic) && !$user->profilePicIsDefault()) {
                FileHandler::deleteFile($user->profile_pic);
            }
            $filename = FileHandler::storePhoto($data['profile_pic'], User::PROFILE_PIC_FOLDER_NAME);
            $data['profile_pic'] = $filename ? User::PROFILE_PIC_FOLDER_NAME . '/' . $filename : User::DEFAULT_PROFILE_PIC;
        }

        if (!empty($data['password']) && $authUser->can('changePassword', $user)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $userType = UserType::findOrFail($data['user_type_id']);

        if (!$user->role_id || $user->user_type_id != $data['user_type_id']) {
            $data['role_id'] = $userType->default_role_id;
        }

        if ($authUser) {
            $data = $this->setACLData($data);
        }

        $user->update($data);
        return $user;
    }


    public function getListDataForDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder|User $users */
        $users = User::acl()->select([
            'users.id as id',
            'users.name',
            'users.user_type_id',
            'institutes.title as institute_title',
            'user_types.title as user_type_title',
            'users.email',
            'users.created_at',
            'users.updated_at'
        ]);
        $users->join('user_types', 'users.user_type_id', '=', 'user_types.id');
        $users->leftJoin('institutes', 'users.institute_id', '=', 'institutes.id');
        $users->acl();

        if ($request->input('user_type_id')) {
            $users->where('users.user_type_id', $request->input('user_type_id'));
        }
        if ($request->input('institute_id')) {
            $users->where('users.institute_id', $request->input('institute_id'));
        }

        return DataTables::eloquent($users)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (User $user) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $user)) {
                    $str .= '<a href="' . route('admin.users.show', $user->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }
                if ($authUser->can('update', $user)) {
                    $str .= '<a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $user)) {
                    $str .= '<a href="#" data-action="' . route('admin.users.destroy', $user->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }
                if ($authUser->can('editTrainerInformation', $user) && $user->isTrainer()) {
                    $str .= '<a href="' . route('admin.trainers.additional-info', $user->id) . '" class="btn btn-outline-info btn-sm trainer-info"> <i class="fas fa-info"></i> ' . __('generic.additional_info_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }

    public function getListDataForTrainerDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder|User $users */
        $users = User::select([
            'users.id as id',
            'users.name',
            'users.user_type_id',
            'institutes.title as institute_title',
            'loc_districts.title as loc_district_name',
            'user_types.title as user_type_title',
            'users.email',
            'users.created_at',
            'users.updated_at'
        ]);
        $users->join('user_types', 'users.user_type_id', '=', 'user_types.id');
        $users->leftJoin('institutes', 'users.institute_id', '=', 'institutes.id');
        $users->leftJoin('loc_districts', 'users.loc_district_id', '=', 'loc_districts.id');
        $users->where('users.user_type_id', '=', User::USER_TYPE_TRAINER_USER_CODE);

        if ($authUser->isUserBelongsToInstitute()) {
            $users->where('users.institute_id', $authUser->institute_id);
        }

        return DataTables::eloquent($users)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (User $user) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $user)) {
                    $str .= '<a href="' . route('admin.users.show', $user->id) . '" class="btn btn-outline-info btn-sm "> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $user) && $authUser->can('updateTrainer', $user)) {
                    $str .= '<a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-outline-warning btn-sm "> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $user)) {
                    $str .= '<a href="#" data-action="' . route('admin.users.destroy', $user->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }
                if ($authUser->can('editTrainerInformation', $user)) {
                    $str .= '<a href="' . route('admin.trainers.additional-info', $user->id) . '" class="btn btn-outline-info btn-sm trainer-info"> <i class="fas fa-info"></i> ' . __('generic.additional_info_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }

    public function deleteUser(User $user): bool
    {
        if ($user->profile_pic && !$user->profilePicIsDefault()) {
            FileHandler::deleteFile($user->profile_pic);
        }

        return $user->delete();
    }

    public function syncUserPermission(User $user, array $permissions): User
    {
        $permissionKeys = Permission::whereIn('id', $permissions)->pluck('key');

        $rolePermissions = $user->getAllRolePermissionKeys();
        $inactivePermissionKeys = $rolePermissions->diff($permissionKeys);

        $activePermissionKeys = $permissionKeys->diff($rolePermissions);

        $inactivePermissions = Permission::whereIn('key', $inactivePermissionKeys)->pluck('id');
        $activePermissions = Permission::whereIn('key', $activePermissionKeys)->pluck('id');

        $user->permissions()->sync([]);
        $user->permissions()->attach($inactivePermissions, ['status' => 0]);
        $user->permissions()->attach($activePermissions, ['status' => 1]);

        /** TODO: not a good idea */
        Cache::forget('userwise_permissions_' . $user->id);

        return $user;
    }

    public function syncUserRoles(User $user, $roleId, $roleIds): User
    {
        if (count($roleIds)) {
            $roleIds = array_diff($roleIds, [$roleId]);
        }

        $user->roles()->sync($roleIds);
        $user->role_id = $roleId;
        $user->save();

        $userExistingPermissionIds = Permission::whereIn('key', $user->allPermissionKey()->toArray())->pluck('id')->toArray();
        $this->syncUserPermission($user, $userExistingPermissionIds);

        return $user;
    }

    protected function setACLData(array $data): array
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        if (
            $authUser->user_type_id == UserType::USER_TYPE_INSTITUTE_USER_CODE
            || $authUser->user_type_id == UserType::USER_TYPE_TRAINER_USER_CODE
            || $authUser->user_type_id == UserType::USER_TYPE_TRAINING_CENTER_USER_CODE
            || $authUser->user_type_id == UserType::USER_TYPE_BRANCH_USER_CODE
        ) {
            $data['institute_id'] = $authUser->institute_id;
        }

        if ($authUser->user_type_id == UserType::USER_TYPE_BRANCH_USER_CODE) {
            $data['branch_id'] = $authUser->branch_id;
        }

        if ($authUser->user_type_id == UserType::USER_TYPE_TRAINING_CENTER_USER_CODE) {
            $data['training_center_id'] = $authUser->training_center_id;
        }

        return $data;
    }
}
