<?php

namespace App\Http\Controllers;

use App\Services\CertificateGenerator;
use Exception;
use http\Client\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Institute;
use App\Models\Trainee;
use App\Models\TraineeAcademicQualification;
use App\Models\TraineeBatch;
use App\Models\TraineeCourseEnroll;
use App\Models\TraineeFamilyMemberInfo;
use App\Services\TraineeService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TraineeManagementController extends Controller
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
    public function index(): View
    {
        $institutes = Institute::acl()->active()->get();

        return \view(self::VIEW_PATH . 'trainee-list', compact('institutes'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        return $this->traineeService->getListDataForDatatable($request);
    }


    /**
     * @param Request $request
     * @return array
     */
    public function importTrainee(Request $request) : array
    {
        $traineeData = (new \App\Models\TraineeImport())->toArray($request->file('trainee_csv_file'))[0];
        DB::beginTransaction();
        try {
            foreach ($traineeData as $key => $traineeDatum) {
                $validatedData = $this->traineeService->traineeImportDataValidate($traineeDatum, $key)->validate();
                $trainee = new Trainee();
                $trainee->fill($validatedData);
                $trainee->save();

                if (!empty($trainee->id)) {
                    $traineeFamilyInfos = $traineeDatum['trainee_family_info'];
                    if (!empty($traineeFamilyInfos['is_guardian'])) {
                        $isGuardian = $traineeFamilyInfos['is_guardian'];
                        unset($traineeFamilyInfos['is_guardian']);
                    }
                    foreach ($traineeFamilyInfos as $familyInfo) {
                        $familyInfo['is_guardian'] = $isGuardian;
                        $familyInfo['is_guardian_data_exist'] = array_key_exists(3, $traineeFamilyInfos);
                        $familyValidatedData = $this->traineeService->traineeFamilyInfoImportDataValidate($familyInfo, $key)->validate();
                        $familyValidatedData['trainee_id'] = $trainee->id;
                        $traineeFamily = new TraineeFamilyMemberInfo();
                        $traineeFamily->fill($familyValidatedData);
                        $traineeFamily->save();
                    }
                    foreach ($traineeDatum['trainee_academic_info'] as $academicInfo) {
                        $academicValidatedData = $this->traineeService->traineeAcademicInfoImportDataValidate($academicInfo, $key)->validate();
                        $academicValidatedData['trainee_id'] = $trainee->id;
                        $traineeAcademic = new TraineeAcademicQualification();
                        $traineeAcademic->fill($academicValidatedData);
                        $traineeAcademic->save();
                    }

                }
            }
            DB::commit();
            return [
                "status" => "success",
                "code" => ResponseAlias::HTTP_OK,
                "message" => "Successfully imported"
            ];
        } catch (Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return [
                    "status" => "fail",
                    "code" => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                    "message" => "validation error",
                    'errors' => $e->errors()
                ];
            }
            return [
                "status" => "success",
                "code" => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),//__('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ];
        }

    }

    /**
     * @param $traineeId
     * @return View
     */
    public function traineeCertificateList($traineeId): View
    {
        $trainee = Trainee::findOrFail($traineeId);

        $traineeCourseEnrolls = TraineeCourseEnroll::select([
            'trainee_course_enrolls.id as id',
            'trainees.name as trainee_name',
            'trainee_batches.batch_id as trainee_batch_id',
            'publish_courses.id as publish_course_id',
            'batches.title as batch_title',
            'batches.batch_status',
        ]);
        $traineeCourseEnrolls->join('publish_courses', 'publish_courses.id', '=', 'trainee_course_enrolls.publish_course_id');
        $traineeCourseEnrolls->leftJoin('trainee_batches', 'trainee_batches.trainee_course_enroll_id', '=', 'trainee_course_enrolls.id');
        $traineeCourseEnrolls->leftJoin('batches', 'trainee_batches.batch_id', '=', 'batches.id');
        $traineeCourseEnrolls->join('trainees', 'trainees.id', '=', 'trainee_course_enrolls.trainee_id');
        $traineeCourseEnrolls->where('trainee_course_enrolls.trainee_id', $trainee->id);
        $traineeCourseEnrolls = $traineeCourseEnrolls->get();

        return \view(self::VIEW_PATH . 'trainee-certificate-list', compact('traineeCourseEnrolls', 'trainee'));
    }

    /**
     * @param TraineeCourseEnroll $traineeCourseEnroll
     * @return RedirectResponse
     */
    public function traineeCertificateCourseWise(TraineeCourseEnroll $traineeCourseEnroll):RedirectResponse
    {
        $traineeBatch = TraineeBatch::where(['trainee_course_enroll_id' => $traineeCourseEnroll->id])->first();
        $familyInfo = TraineeFamilyMemberInfo::where("trainee_id", $traineeCourseEnroll->trainee_id)->where('relation_with_trainee', "father")->first();
        $institute = $traineeCourseEnroll->publishCourse->institute;
        $path = "trainee-certificates/" . date('Y/F/', strtotime($traineeBatch->batch->start_date)) . "course/" . Str::slug($traineeCourseEnroll->publishCourse->course->title) . "/batch/" . $traineeBatch->batch->title;

        $traineeInfo = [
            'trainee_id' => $traineeCourseEnroll->trainee_id,
            'trainee_name' => $traineeCourseEnroll->trainee->name,
            'trainee_father_name' => $familyInfo->member_name,
            'publish_course_id' => $traineeCourseEnroll->publish_course_id,
            'publish_course_name' => $traineeCourseEnroll->publishCourse->course->title,
            'path' => $path,
            "register_no" => $traineeCourseEnroll->trainee->trainee_registration_no,
            'institute_title' => $institute->title,
            'from_date' => $traineeBatch->batch->start_date,
            'to_date' => $traineeBatch->batch->end_date,
            'batch_name' => $traineeBatch->batch->title,
            'course_coordinator_signature' => "storage/{$traineeBatch->batch->trainingCenter->course_coordinator_signature}",
            'course_director_signature' => "storage/{$traineeBatch->batch->trainingCenter->course_director_signature}",
        ];

        $template = 'frontend.trainee/certificate/certificate-one';
        $pdf = app(CertificateGenerator::class);
        return redirect(asset("storage/" . $pdf->generateCertificate($template, $traineeInfo)));
    }
}
