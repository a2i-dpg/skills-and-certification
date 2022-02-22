<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\BaseController;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Services\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    const  VIEW_PATH = "master::acl.users.";

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->authorizeResource(User::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $userTypes = UserType::authUserWiseType()->get();
        return view(self::VIEW_PATH . 'browse', compact('userTypes'));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $user = new User();
        $userTypes = UserType::authUserWiseType()->get();

        return \view(self::VIEW_PATH . 'edit-add', compact('user', 'userTypes'));
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->userService->validator($request)->validate();

        try {
            $this->userService->createUser($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }


        return redirect()->route('admin.users.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'User']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @return View
     */
    public function show(User $user, Request $request): View
    {
        return \view(self::VIEW_PATH . 'read', compact('user'));
    }

    /**
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        $userTypes = UserType::authUserWiseType()->get();

        return \view(self::VIEW_PATH . 'edit-add', compact('user', 'userTypes'));
    }

    /**
     * @param User $user
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(User $user, Request $request): RedirectResponse
    {
        $validatedData = $this->userService->validator($request, $user->id)->validate();

        DB::beginTransaction();
        try {
            $this->userService->updateUser($user, $validatedData);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.users.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'User']),
            'alert-type' => 'success'
        ]);

    }

    /**
     *  Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->userService->deleteUser($user);
        } catch (\Exception $exception) {
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'User']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        return $this->userService->getListDataForDatatable($request);
    }

    public function userPermissionIndex(User $user): View
    {
        $this->authorize('viewUserPermission', $user);

        $roles = Role::all();

        $userPermissions = $user->allPermissionKey()->all();
        $customPermissions = $user->getCustomPermissions()->all();
        $userExtraRoles = $user->roles()->pluck('id', 'id')->toArray();

        $permissionsGroupByTable = Permission::all()->groupBy('table_name');
        return \view(
            self::VIEW_PATH . 'permissions',
            compact(
                'user',
                'permissionsGroupByTable',
                'customPermissions',
                'userExtraRoles',
                'userPermissions',
                'roles'
            )
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function userRoleSync(Request $request, User $user): RedirectResponse
    {
        $this->authorize('changeUserRole', $user);


        $request->validate([
            'role_id' => 'required|integer',
            'role_ids' => 'nullable|array',
        ]);

        $roleId = $request->input('role_id');
        $roleIds = $request->input('role_ids', []);

        try {
            $this->userService->syncUserRoles($user, $roleId, $roleIds);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'User Role']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function userPermissionSync(Request $request, User $user): RedirectResponse
    {
        $permissions = $request->input('permissions', []);

        try {
            $this->userService->syncUserPermission($user, $permissions);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'User Permission']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checkUserEmailUniqueness(Request $request): JsonResponse
    {
        $trainee = User::where('email', $request->email)->first();
        if ($trainee == null) {
            return response()->json(true);
        }
        return response()->json("This email address already in use");
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checkUserEmail(Request $request): JsonResponse
    {
        $user = User::where(['email' => $request->email])->first();
        if ($user == null) {
            return response()->json(true);
        } else {
            return response()->json('Email already in use!');
        }
    }

    public function trainerList(User $user): View
    {
        $trainerList = User::where('institute_id', $user->institute_id)
            ->where('user_type_id', User::USER_TYPE_TRAINER_USER_CODE)
            ->get();


        return \view(self::VIEW_PATH . 'trainers', compact('trainerList'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTrainersDatatable(Request $request): JsonResponse
    {
        return $this->userService->getListDataForTrainerDatatable($request);
    }
}
