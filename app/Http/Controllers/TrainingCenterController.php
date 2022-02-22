<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Institute;
use App\Models\TrainingCenter;
use App\Services\TrainingCenterService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TrainingCenterController extends Controller
{
    const VIEW_PATH = 'backend.training-centers.';
    public TrainingCenterService $trainingCenterService;

    public function __construct(TrainingCenterService $trainingCenterService)
    {
        $this->trainingCenterService = $trainingCenterService;
        $this->authorizeResource(TrainingCenter::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view(self::VIEW_PATH . 'browse');
    }

    /**
     * @return View
     */
    public function create(): view
    {
        $institutes = Institute::active()->get();
        $branches = Branch::active()->get();

        $trainingCenter = new TrainingCenter();

        return view(self::VIEW_PATH . 'edit-add', compact('trainingCenter', 'institutes', 'branches'));

    }


    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->trainingCenterService->validator($request)->validate();
        try {
            $this->trainingCenterService->createTrainingCenter($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.training-centers.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Training Center']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param TrainingCenter $trainingCenter
     * @return View
     */
    public function show(TrainingCenter $trainingCenter): View
    {
        return view(self::VIEW_PATH . 'read', compact('trainingCenter'));
    }

    /**
     * @param TrainingCenter $trainingCenter
     * @return View
     */
    public function edit(TrainingCenter $trainingCenter): View
    {
        $institutes = Institute::active()->get();
        $branches = Branch::where(['institute_id' => $trainingCenter->institute_id])->get();

        return view(self::VIEW_PATH . 'edit-add', compact('trainingCenter', 'institutes', 'branches'));
    }

    /**
     * @param Request $request
     * @param TrainingCenter $trainingCenter
     * @return RedirectResponse
     * @throws ValidationException
     *
     */
    public function update(Request $request, TrainingCenter $trainingCenter): RedirectResponse
    {
        $validateData = $this->trainingCenterService->validator($request)->validate();

        try {
            $this->trainingCenterService->updateTrainingCenter($trainingCenter, $validateData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.training-centers.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Training Center']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param TrainingCenter $trainingCenter
     * @return RedirectResponse
     */
    public function destroy(TrainingCenter $trainingCenter): RedirectResponse
    {
        try {
            $this->trainingCenterService->deleteTrainingCenter($trainingCenter);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Training center']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        return $this->trainingCenterService->getListDataForDatatable($request);
    }
}

