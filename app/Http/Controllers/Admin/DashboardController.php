<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Classes\AuthHelper;
use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends BaseController
{
    const  VIEW_PATH = "master::acl.users.";
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        $this->authorizeResource(User::class);
    }

    /**
     * @return View
     */
    public function dashboard(): View
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        $adminInfo = $this->dashboardService->getAdminInfo($authUser);

        return view(self::VIEW_PATH . 'dashboard', ['adminInfo' => $adminInfo]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTrainerExamRoutine(Request $request): JsonResponse
    {
        $routine = $this->dashboardService->examSchedules($request);
        return DataTables::eloquent($routine)
            ->addColumn('actions', function ($routine) {
                return '<a href="' . route('admin.examinations.show', $routine->examination_id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.exam_details') . ' </a>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTrainerDailyRoutine(Request $request): JsonResponse
    {
        $routine = $this->dashboardService->classSchedules($request);
        return DataTables::eloquent($routine)->toJson();
    }

}
