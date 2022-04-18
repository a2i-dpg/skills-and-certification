<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Services\BatchService;
use Illuminate\Contracts\Foundation\Application;
use App\Models\Examination;
use App\Models\TrainerBatch;
use App\Models\Batch;
use App\Models\TrainingCenter;
use App\Services\TraineeResultManagementService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BatchController extends Controller
{
    const VIEW_PATH = 'backend.batches.';
    public BatchService $batchService;

    public function __construct(BatchService $batchService)
    {
        $this->batchService = $batchService;
        $this->authorizeResource(Batch::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return \view(self::VIEW_PATH . 'edit-add')->with([
            'batch' => new Batch(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validateData = $this->batchService->validator($request)->validate();

        try {
            $this->batchService->createBatch($validateData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.batches.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Batch']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Batch $batch
     * @return View
     */
    public function show(Batch $batch): View
    {
        return \view(self::VIEW_PATH . 'read', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Batch $batch
     * @return View
     */
    public function edit(Batch $batch): View
    {
        return \view(self::VIEW_PATH . 'edit-add')->with([
            'batch' => $batch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Batch $batch
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Batch $batch): RedirectResponse
    {
        $validateData = $this->batchService->validator($request, $batch->id)->validate();

        try {
            $this->batchService->updateBatch($batch, $validateData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.batches.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Batch']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Batch $batch
     * @return RedirectResponse
     */
    public function destroy(Batch $batch): RedirectResponse
    {
        try {
            $this->batchService->deleteBatch($batch);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Batch']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        return $this->batchService->getBatchLists($request);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checkCode(Request $request): JsonResponse
    {
        $batch = Batch::where(['code' => $request->code])->first();
        if ($batch == null) {
            return response()->json(true);
        } else {
            return response()->json('Code already in use!');
        }
    }

    public function batchOnGoing(Batch $batch): RedirectResponse
    {
        if ($batch->batch_status == Batch::BATCH_STATUS_ON_GOING) {
            return back()->with([
                'message' => __('This batch already on going now', ['object' => 'Batch']),
                'alert-type' => 'warning'
            ]);
        }

        $data['batch_status'] = Batch::BATCH_STATUS_ON_GOING;

        try {
            $this->batchService->changeBatchStatus($batch, $data);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('This batch on going now', ['object' => 'Batch']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Batch $batch
     * @return RedirectResponse
     */
    public function batchComplete(Batch $batch): RedirectResponse
    {
        if ($batch->batch_status == Batch::BATCH_STATUS_COMPLETE) {
            return back()->with([
                'message' => __('This batch already completed', ['object' => 'Batch']),
                'alert-type' => 'warning'
            ]);
        }
        if ($batch->batch_status != Batch::BATCH_STATUS_ON_GOING) {
            return back()->with([
                'message' => __('This batch not start, Please start batch at first', ['object' => 'Batch']),
                'alert-type' => 'warning'
            ]);
        }

        $data['batch_status'] = Batch::BATCH_STATUS_COMPLETE;

        try {
            $this->batchService->changeBatchStatus($batch, $data);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('This batch completed', ['object' => 'Batch']),
            'alert-type' => 'success'
        ]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function batchTrainingCenter(Request $request)
    {
        $publishCourse = Course::findOrFail($request->publish_course_id);
        return TrainingCenter::whereIn('id', $publishCourse->training_center_id)->get();
    }

    /**
     * @param $id
     * @return View
     */
    public function trainerMapping($id): View
    {
        $trainerBatches = TrainerBatch::with('batch', 'user')
            ->where(['batch_id' => $id])
            ->get();

        $trainers = User::acl()->where('user_type_id', User::USER_TYPE_TRAINER_USER_CODE)->get();
        $batch_id = $id;

        return view(self::VIEW_PATH . 'trainer-mapping', compact('trainers', 'trainerBatches', 'batch_id'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function trainerMappingUpdate(Request $request): RedirectResponse
    {
        $batch_id = $request->get('batch_id');

        foreach ($request->get('delete') as $key => $user_id) {
            if ($key > 0) {
                $count = Examination::where(['batch_id' => $batch_id, 'created_by' => $user_id])->count();
                if ($count == 0) {
                    TrainerBatch::where(['batch_id' => $batch_id, 'user_id' => $user_id])->delete();
                }
            }
        }

        foreach ($request->get('user_id') as $user_id) {
            $trainer_batch_id = TrainerBatch::where([['batch_id', '=', $batch_id], ['user_id', '=', $user_id,]])->first();

            if ($user_id > 0 && empty($trainer_batch_id)) {
                TrainerBatch::create(['batch_id' => $batch_id, 'user_id' => $user_id, 'created_at' => Auth::user()->id]);
            }
        }

        return back()->with([
            'message' => __('admin.common.success'),
            'alert-routine' => 'success'
        ]);
    }
}
