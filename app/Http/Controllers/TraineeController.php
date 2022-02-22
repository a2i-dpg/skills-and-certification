<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Institute;
use App\Services\TraineeService;
use Illuminate\View\View;

class TraineeController extends Controller
{
    const VIEW_PATH = 'backend.trainees.';
    protected TraineeService $traineeService;

    public function __construct(TraineeService $traineeService)
    {
        $this->traineeService = $traineeService;
    }

    /**
     * @return View
     */
    public function traineeAcceptList():View {
        $institutes = Institute::acl()->active()->get();
        $batches = \App\Models\Batch::acl()->where(['batch_status'=>null])->get();
        return \view(self::VIEW_PATH . 'trainee-accept-list', compact('institutes', 'batches'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAcceptDatatable(Request $request): JsonResponse
    {
        return $this->traineeService->getListForAcceptListDatatable($request);
    }

    public function traineeAcceptNowAll(Request $request): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validatedData = $this->traineeService->validateAcceptNowAll($request)->validate();
            $this->traineeService->addToTraineeAcceptedList($validatedData['trainee_ids']);
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

        return back()->with([
            'message' => __('Trainee has been accepted'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function traineeRejectNowAll(Request $request):RedirectResponse
    {
        $mobiles = $request->mobile;

        DB::beginTransaction();
        try {
            $validatedData = $this->traineeService->validateRejectNowAll($request)->validate();
            $this->traineeService->rejectTraineeAll($validatedData['trainee_ids']);
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

        return back()->with([
            'message' => __('Trainee has been rejected'),
            'alert-type' => 'success'
        ]);
    }
}
