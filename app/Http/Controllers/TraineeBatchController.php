<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Trainee;
use App\Models\TraineeAcademicQualification;
use App\Models\TraineeBatch;
use App\Models\TraineeCourseEnroll;
use App\Models\TraineeFamilyMemberInfo;
use App\Services\TraineeBatchService;
use App\Services\TraineeService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TraineeBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    const VIEW_PATH = 'backend.trainee-batches.';
    public TraineeBatchService $traineeBatchService;

    public function __construct(TraineeBatchService $traineeBatchService)
    {
        $this->traineeBatchService = $traineeBatchService;
    }

    /**
     * @param int $id
     * @return View
     */
    public function index(int $id): View
    {
        $batch = Batch::findOrFail($id);

        return \view(self::VIEW_PATH . 'browse', compact('batch'));
    }

    public function getDatatable(Request $request, int $id): JsonResponse
    {
        return $this->traineeBatchService->getTraineeBatchLists($id);
    }


    public function importTrainee(Request $request, int $batch_id): array
    {
        $traineeData = (new \App\Models\TraineeImport())->toArray($request->file('import_trainee_file'))[0];

        DB::beginTransaction();
        try {
            $publishCourseId = Batch::findOrFail($batch_id)->publish_course_id;
            foreach ($traineeData as $key => $traineeDatum) {
                $validatedData = app(TraineeService::class)->traineeImportDataValidate($traineeDatum, ($key+1))->validate();
                $trainee = new Trainee();
                $trainee->fill($validatedData);
                $trainee->save();

                if (!empty($trainee->id)) {
                    $traineeFamilyInfos = $traineeDatum['trainee_family_info'];
                    foreach ($traineeFamilyInfos as $familyInfo) {
                        $familyInfo['is_guardian_data_exist'] = array_key_exists(3, $traineeFamilyInfos);
                        $familyValidatedData = app(TraineeService::class)->traineeFamilyInfoImportDataValidate($familyInfo, ($key+1))->validate();
                        $familyValidatedData['trainee_id'] = $trainee->id;
                        $traineeFamily = new TraineeFamilyMemberInfo();
                        $traineeFamily->fill($familyValidatedData);
                        $traineeFamily->save();
                    }
                    foreach ($traineeDatum['trainee_academic_info'] as $academicInfo) {
                        $academicValidatedData = app(TraineeService::class)->traineeAcademicInfoImportDataValidate($academicInfo,($key+1))->validate();
                        $academicValidatedData['trainee_id'] = $trainee->id;
                        $traineeAcademic = new TraineeAcademicQualification();
                        $traineeAcademic->fill($academicValidatedData);
                        $traineeAcademic->save();
                    }

                    $traineeCourseEnrollInfo = [
                        "publish_course_id" => $publishCourseId,
                        "enroll_status" => TraineeCourseEnroll::ENROLL_STATUS_ACCEPT,
                        "payment_status" => TraineeCourseEnroll::PAYMENT_STATUS_PAID,
                    ];

                    $traineeEnrolment = $trainee->traineeCourseEnroll()->create($traineeCourseEnrollInfo);
                    if ($traineeEnrolment) {

                        $traineeBatch = app(TraineeBatch::class);
                        $traineeBatch->batch_id = $batch_id;
                        $traineeBatch->trainee_course_enroll_id = $traineeEnrolment->id;
                        $traineeBatch->enrollment_date = date('Y-m-d');
                        $traineeBatch->enrollment_status = TraineeBatch::ENROLLMENT_STATUS_ENROLLED;
                        $traineeBatch->save();
                    }

                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return [
                    "status" => "fail",
                    "code" => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                    "message" => "validation error",
                    'errors' => array_values($e->errors())
                ];
            }
            return [
                "status" => "success",
                "code" => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),//__('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ];
        }

        return [
            "status" => "success",
            "code" => ResponseAlias::HTTP_OK,
            "message" => "Successfully imported"
        ];
    }
}
