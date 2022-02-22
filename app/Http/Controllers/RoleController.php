<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Permission;
use App\Models\Role;
use App\Services\RoleService;

class RoleController extends BaseController
{
    const VIEW_PATH = 'master::acl.roles.';

    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        $this->authorizeResource(Role::class);
    }

    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    public function create(): View
    {
        $role = new Role();
        return \view(self::VIEW_PATH . 'edit-add', compact('role'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->roleService->validator($request->all())->validate();

        try {
            $this->roleService->createRole($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Role']),
            'alert-type' => 'success'
        ]);
    }

    public function show(Role $role): View
    {
        return \view(self::VIEW_PATH . 'read', compact('role'));
    }

    public function edit(Role $role): View
    {
        return \view(self::VIEW_PATH . 'edit-add', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validatedData = $this->roleService->validator($request->all(), $role->id)->validate();

        try {
            $this->roleService->updateRole($role, $validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Role']),
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            $this->roleService->deleteRole($role);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Role']),
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->roleService->getListDataForDatatable($request);
    }

    public function rolePermissionIndex(Role $role): View
    {
        $permissionsGroupByTable = Permission::all()->groupBy('table_name');
        return \view(self::VIEW_PATH . 'permissions', compact('role', 'permissionsGroupByTable'));
    }

    public function rolePermissionSync(Request $request, Role $role): RedirectResponse
    {
        $permissions = $request->input('permissions', []);

        try {
            $this->roleService->syncRolePermission($role, $permissions);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Role Permission']),
            'alert-type' => 'success'
        ]);
    }
}
