<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use App\Models\Batch;
use App\Models\Examination;
use App\Models\ExaminationResult;
use App\Models\Trainee;
use App\Models\TraineeBatch;
use App\Models\TraineeCourseEnroll;
use App\Services\ExaminationResultService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ExaminationResultController extends Controller
{
    const VIEW_PATH = 'backend.examination-results.';
    public ExaminationResultService $examinationResultService;

    public function __construct(ExaminationResultService $examinationResultService)
    {
        $this->examinationResultService = $examinationResultService;
        $this->authorizeResource(ExaminationResult::class);
    }


    /**
     * @return View
     */
    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $examinationResult = new Batch();
        $examinations = Examination::with('examinationType')->where(['row_status' => BaseModel::ROW_STATUS_ACTIVE, 'status' => Examination::EXAMINATION_STATUS_COMPLETE])->get();
        return view(self::VIEW_PATH . 'edit-add', compact('examinationResult', 'examinations'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */

    public function store(Request $request): RedirectResponse
    {

        $validatedData = $this->examinationResultService->validator($request)->validate();
        $authUser = Auth::user();
        $examination = Examination::where(['id' => $request->examination_id])->first();
        $batch_id = $examination->batch_id;
        $training_center_id = $examination->training_center_id;
        try {
            $validatedData['batch_id'] = $batch_id;
            $validatedData['training_center_id'] = $training_center_id;
            $validatedData['institute_id'] = !empty($authUser->institute_id) ? $authUser->institute_id : $request->institute_id;
            $validatedData['created_by'] = $authUser->id;
            $validatedData['user_id'] = $authUser->id;
            $this->examinationResultService->createExaminationResult($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-result' => 'error'
            ]);
        }

        return redirect()->route('admin.examination-results.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'ExaminationResult']),
            'alert-result' => 'success'
        ]);
    }

    /**
     * @param ExaminationResult $examinationResult
     * @return View
     */
    public function show(ExaminationResult $examinationResult): View
    {
        $examination = Examination::where('id', $examinationResult->examination_id)->first();
        return view(self::VIEW_PATH . 'read', compact('examinationResult', 'examination'));
    }

    /**
     * @param ExaminationResult $examinationResult
     * @return View
     */
    public function edit(ExaminationResult $examinationResult)
    {
        $trainees = Trainee::where(['id' => $examinationResult->trainee_id])->pluck('name', 'id');
        $examination = Examination::where('id', $examinationResult->examination_id)->first();

        return view(self::VIEW_PATH . 'edit-add', compact('examinationResult', 'examination', 'trainees'));
    }

    /**
     * @param Request $request
     * @param ExaminationResult $examinationResult
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, ExaminationResult $examinationResult): RedirectResponse
    {
        $validatedData = $this->examinationResultService->validator($request)->validate();
        $examination = Examination::where(['id' => $request->examination_id])->first();
        $batch_id = $examination->batch_id;
        $training_center_id = $examination->training_center_id;
        try {
            $validatedData['batch_id'] = $batch_id;
            $validatedData['training_center_id'] = $training_center_id;
            $this->examinationResultService->updateExaminationResult($examinationResult, $validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-result' => 'error'
            ]);
        }

        return redirect()->route('admin.examination-results.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'ExaminationResult']),
            'alert-result' => 'success'
        ]);
    }

    /**
     * @param ExaminationResult $examinationResult
     * @return RedirectResponse
     */
    public function destroy(ExaminationResult $examinationResult): RedirectResponse
    {
        try {
            $this->examinationResultService->deleteExaminationResult($examinationResult);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-result' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'ExaminationResult']),
            'alert-result' => 'success'
        ]);
    }


    public function getDatatable(Request $request): JsonResponse
    {
        return $this->examinationResultService->getExaminationResultLists($request);
    }

    public function getTrainees($examinationIid)
    {
        $examination = Examination::where(['id' => $examinationIid])->first();
        $batchId = $examination->batch_id;
        $traineeBatches = TraineeBatch::select(
            [
                'trainees.id as id',
                'trainee_course_enrolls.id as trainee_registrations.trainee_registration_no',
                //'trainees.trainee_registration_no as trainee_registration_no',
                'trainees.name as trainee_name',
                DB::raw('DATE_FORMAT(trainee_batches.enrollment_date,"%d %b, %Y %h:%i %p") AS enrollment_date'),
            ]
        );
        $traineeBatches->join('batches', 'trainee_batches.batch_id', '=', 'batches.id');
        $traineeBatches->leftJoin('trainee_course_enrolls', 'trainee_batches.trainee_course_enroll_id', '=', 'trainee_course_enrolls.id');
        $traineeBatches->join('trainees', 'trainee_course_enrolls.trainee_id', '=', 'trainees.id');
        $traineeBatches->where('batches.id', $batchId);
        $data = $traineeBatches->get();

        return $data->toArray();


    }

    /**
     * @return View
     */
    public function batchResult($examinationId): View
    {
        $examination = Examination::find($examinationId);
        $trainees = ExaminationResult::select([
            'trainees.name as name',
            'examinations.id as examination_id',
            'examination_results.achieved_marks as achieved_marks',
            'examination_results.feedback as feedback',
            'examinations.total_mark as total_marks'
        ])
            ->leftjoin('examinations', 'examination_results.examination_id', 'examinations.id')
            ->leftjoin('trainees', 'examination_results.trainee_id', '=', 'trainees.id')
            ->where([
                'examinations.id' => $examination->id
            ])
            ->get();
        return \view(self::VIEW_PATH . 'batch-result', compact('trainees', 'examination'));
    }

    /**
     * @return View
     */
    public function batchResultadd($examinationId): View
    {
        $examination = Examination::find($examinationId);
        $trainees = TraineeCourseEnroll::select(
            [
                'trainees.id as id',
                'trainee_course_enrolls.course_id as course_id',
                'trainee_course_enrolls.batch_id as batch_id',
                'trainees.name as name',
                'examinations.id as examination_id',
                'examinations.total_mark as total_marks',
                'examinations.code as code',
            ]
        )
            ->join('batches', 'trainee_course_enrolls.batch_id', '=', 'batches.id')
            ->join('trainees', 'trainee_course_enrolls.trainee_id', '=', 'trainees.id')
            ->leftjoin('examinations', 'batches.id', '=', 'examinations.batch_id')
            ->where([
                'batches.id' => $examination->batch_id,
                'examinations.id' => $examination->id,
                'trainee_course_enrolls.enroll_status' => TraineeCourseEnroll::ENROLL_STATUS_ACCEPT
            ])
            ->get();
        return \view(self::VIEW_PATH . 'batch-result-edit-add', compact('trainees', 'examination'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */

    function batchResultUpdate(Request $request): RedirectResponse
    {
        $validatedData = $this->examinationResultService->updateResultValidator($request)->validate();
        $this->examinationResultService->updateBatchResult($validatedData);
        try {

        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-result' => 'error'
            ]);
        }

        return redirect()->route('admin.examinations.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Examination Result']),
            'alert-result' => 'success'
        ]);
    }

    /**
     * @param ExaminationResult $examinationResult
     * @return View
     */
    public function batchResultEdit(ExaminationResult $examinationResult, $examinationID)
    {

        $examinationResult = ExaminationResult::where(['examination_id' => $examinationID])->first();
        $trainees = ExaminationResult::select([
            'trainees.id as id',
            'examination_results.course_id as course_id',
            'examination_results.batch_id as batch_id',
            'trainees.name as name',
            'examination_results.id as examination_result_id',
            'examination_results.examination_id as examination_id',
            'examination_results.achieved_marks as achieved_marks',
            'examination_results.feedback as feedback',
            'examinations.total_mark as total_marks',

        ])
            ->leftjoin('examinations', 'examination_results.examination_id', 'examinations.id')
            ->leftjoin('trainees', 'examination_results.trainee_id', '=', 'trainees.id')
            ->where([
                'examinations.id' => $examinationID
            ])
            ->get();
//            dd($trainees, $examinationID);
        return \view(self::VIEW_PATH . 'batch-result-edit-add', compact('trainees', 'examinationResult'));
    }

    /**
     * @param ExaminationResult $examination
     * @param Request $request
     * @return RedirectResponse
     */

    public function batchResultstore(Request $request): RedirectResponse
    {
        //dd($request->all());
        $validatedData = $this->examinationResultService->resultValidator($request)->validate();
        //dd($validatedData);
        try {
            $this->examinationResultService->createBatchResult($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-result' => 'error'
            ]);
        }

        return redirect()->route('admin.examinations.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Examination Result']),
            'alert-result' => 'success'
        ]);
    }
}
