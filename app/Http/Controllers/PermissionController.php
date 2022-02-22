<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Permission;
use App\Services\PermissionService;

class PermissionController extends BaseController
{
    const VIEW_PATH = 'master::acl.permissions.';

    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
        $this->authorizeResource(Permission::class);
    }

    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    public function create(): View
    {
        $permission = new Permission();
        return \view(self::VIEW_PATH . 'edit-add', compact('permission'));
    }

    public function store(Request $request): RedirectResponse
    {
        $postData = $request->only(array_keys($this->permissionService->validationRules()));
        $postData['is_user_defined'] = $postData['is_user_defined'] === 'on';
        $postData['sub_group_order'] = empty($postData['sub_group_order']) ? 0 : $postData['sub_group_order'];

        $this->permissionService->validator($postData)->validate();

        try {
            $this->permissionService->createPermission($postData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Permission']),
            'alert-type' => 'success'
        ]);
    }

    public function show(Permission $permission): View
    {
        return \view(self::VIEW_PATH . 'read', compact('permission'));
    }

    public function edit(Permission $permission): View
    {
        return \view(self::VIEW_PATH . 'edit-add', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $postData = $request->only(array_keys($this->permissionService->validationRules()));
        $postData['is_user_defined'] = $postData['is_user_defined'] === 'on';
        $postData['sub_group_order'] = empty($postData['sub_group_order']) ? 0 : $postData['sub_group_order'];

        $this->permissionService->validator($postData, $permission->id)->validate();

        try {
            $this->permissionService->updatePermission($permission, $postData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Permission']),
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        try {
            $this->permissionService->deletePermission($permission);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Permission']),
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->permissionService->getListDataForDatatable($request);
    }
}
