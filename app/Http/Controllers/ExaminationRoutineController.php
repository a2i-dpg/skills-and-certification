<?php

namespace App\Http\Controllers;

use App\Helpers\Classes\AuthHelper;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Models\Batch;
use App\Models\Examination;
use App\Models\ExaminationRoutine;
use App\Models\ExaminationRoutineDetail;
use App\Models\TrainingCenter;
use App\Services\ExaminationRoutineService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ExaminationRoutineController extends Controller
{
    const VIEW_PATH = 'backend.examination-routines.';
    public ExaminationRoutineService $examinationRoutineService;

    public function __construct(ExaminationRoutineService $examinationRoutineService)
    {
        $this->examinationRoutineService = $examinationRoutineService;
        $this->authorizeResource(ExaminationRoutine::class);
    }

    /**
     * @return View
     */
    public function index()
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    /**
     * @param ExaminationRoutine  $examinationRoutine
     * @return View
     */

    public function create(ExaminationRoutine $examinationRoutine ): View
    {
        $batches = Batch::acl()->active()->pluck('title','id');
        $trainingCenters = TrainingCenter::acl()->active()->pluck('title','id');
        $trainers = User::acl()->where(['user_type_id' => 1 ])->get();
        $examinations = Examination::acl()->where('status','!=',Examination::EXAMINATION_STATUS_COMPLETE)->get();

        return view(self::VIEW_PATH . 'edit-add', compact('examinationRoutine','batches','trainingCenters','trainers','examinations'));
    }


    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->examinationRoutineService->validator($request)->validate();
        $authUser = AuthHelper::getAuthUser();
        try {
            $validatedData['created_by'] = $authUser->id;
            $this->examinationRoutineService->createExaminationRoutine($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-routine' => 'error'
            ]);
        }

        return redirect()->route('admin.examination-routines.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'ExaminationRoutine']),
            'alert-routine' => 'success'
        ]);
    }

    /**
     * @param ExaminationRoutine $examinationRoutine
     * @return View
     */
    public function show(ExaminationRoutine $examinationRoutine): View
    {
        $examinationRoutineDetails = ExaminationRoutineDetail::with('examination','examinationRoutine')->where(['examination_routine_id' => $examinationRoutine->id])->get();
        return view(self::VIEW_PATH . 'read', compact('examinationRoutine','examinationRoutineDetails'));
    }

    /**
     * @param ExaminationRoutine $examinationRoutine
     * @return View
     */
    public function edit(ExaminationRoutine $examinationRoutine): View
    {
        $trainers = User::acl()->where(['user_type_id' => 1])->get();
        $examinations = Examination::acl()->get();


        return view(self::VIEW_PATH . 'edit-add', compact('examinationRoutine','trainers','examinations'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, ExaminationRoutine $examinationRoutine): RedirectResponse
    {
        $this->examinationRoutineService->validator($request)->validate();
        try {
            $this->examinationRoutineService->updateExaminationRoutine($examinationRoutine, $request->all());
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-routine' => 'error'
            ]);
        }

        return redirect()->route('admin.examination-routines.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'ExaminationRoutine']),
            'alert-routine' => 'success'
        ]);
    }

    /**
     * @param ExaminationRoutine $examinationRoutine
     * @return RedirectResponse
     */
    public function destroy(ExaminationRoutine $examinationRoutine): RedirectResponse
    {
        try {
            $this->examinationRoutineService->deleteExaminationRoutine($examinationRoutine);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-routine' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'ExaminationRoutine']),
            'alert-routine' => 'success'
        ]);
    }


    public function getDatatable(Request $request): JsonResponse
    {
        return $this->examinationRoutineService->getExaminationRoutineLists($request);
    }
    public function examinationRoutine()
    {
        return view(self::VIEW_PATH . 'examination-routine');
    }
}
