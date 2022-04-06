<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Services\TraineeResultManagementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Trainee;
use DB;

class TraineeResultManagementController extends Controller
{
    const VIEW_PATH = 'backend.batch-result.';
    public TraineeResultManagementService $traineeResultManagementService;

    public function __construct(TraineeResultManagementService $traineeResultManagementService)
    {
        $this->traineeResultManagementService = $traineeResultManagementService;
    }

    /**
     * @param int $id
     * @return View
     */
    public function showTraineeResultList(int $id): View
    {
        $batch = Batch::findOrFail($id);
        return \view(self::VIEW_PATH . 'trainees-final-result', compact('batch'));
    }

    /**
     * @return View
     */
    public function showCompletedBatches(): View
    {
        return \view(self::VIEW_PATH . 'completed-batches');
    }

    public function traineeResultDatatable(Request $request): JsonResponse
    {
        $batchId = $request->input('batch_id');

       return $this->traineeResultManagementService->getTraineeResultDatatable($batchId);
    }

    /**
     * @return JsonResponse
     */
    public function completedBatchesDatatable(): JsonResponse
    {
       return $this->traineeResultManagementService->getCompletedBatchesDatatable();
    }
}
