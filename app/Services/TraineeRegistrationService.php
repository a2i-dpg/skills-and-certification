<?php


namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\Batch;
use App\Models\Trainee;
use App\Models\TraineeCourseEnroll;
use App\Models\TraineeFamilyMemberInfo;
use App\Models\User;
use App\Models\UserType;
use App\Models\CertificateRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\RequiredIf;
use Yajra\DataTables\Facades\DataTables;

class TraineeRegistrationService
{
    /**
     * @param array $data
     * @return mixed
     */
    public function createRegistration(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        if (isset($data['student_signature_pic'])) {
            $filename = FileHandler::storePhoto($data['student_signature_pic'], 'student');
            $trainee['student_signature_pic'] = 'student/' . $filename;
        }

        if (isset($data['student_pic'])) {
            $filename = FileHandler::storePhoto($data['student_pic'], 'student', 'signature_' . $data['access_key']);
            $trainee['student_pic'] = 'student/' . $filename;
        }
        //create a user
        $userData = Arr::only($data, ['name', 'email', 'password', 'profile_pic']);

        $userData = array_merge($userData, ['user_type_id' => UserType::USER_TYPE_TRAINEE_USER_CODE]);
        $user = User::create($userData);
        event(new Registered($user));

        $data = array_merge($data, ['user_id' => $user->id]);

        return Trainee::create($data);
    }

    /**
     * @param Request $request
     * @param null $id
     * @return Validator
     */
    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'name' => 'required|string|max:191',
            'mobile' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'email' => 'required|string|max:191|email|unique:trainees',
            'address' => 'string|required',
            'physically_disable' => 'nullable',
            'disable_status' => 'nullable',
            'physical_disabilities' => 'nullable',
            'gender' => 'required|int',
            'password' => [
                'bail',
                new RequiredIf($id == null),
                'confirmed'
            ]
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    /**
     * @param Trainee $trainee
     * @return Collection
     */
    public function getTraineeAcademicQualification(Trainee $trainee): Collection
    {
        //return $trainee->academicQualifications;
        return $trainee->academicQualificationns;
    }

    /**
     * @param Trainee $trainee
     * @return array
     */
    public function getTraineeFamilyMemberInfo(Trainee $trainee): array
    {
        $father = $trainee->familyMemberInfo->where('relation_with_trainee', TraineeFamilyMemberInfo::GUARDIAN_FATHER)->first();
        $mother = $trainee->familyMemberInfo->where('relation_with_trainee', TraineeFamilyMemberInfo::GUARDIAN_MOTHER)->first();
        $guardian = $trainee->familyMemberInfo->where('is_guardian', TraineeFamilyMemberInfo::GUARDIAN_OTHER)->first();

        if (!empty($father) && empty($guardian) && $father->is_guardian == TraineeFamilyMemberInfo::GUARDIAN_FATHER) {
            $guardian = $father;
        } else if (!empty($mother) && empty($guardian) && $mother->is_guardian == TraineeFamilyMemberInfo::GUARDIAN_MOTHER) {
            $guardian = $mother;
        }

        $haveTraineeFamilyMembersInfo = true;
        if (empty($father) && empty($mother) && empty($guardian)) {
            $haveTraineeFamilyMembersInfo = false;
        }
        return [
            'father' => $father,
            'mother' => $mother,
            'guardian' => $guardian,
            'haveTraineeFamilyMembersInfo' => $haveTraineeFamilyMembersInfo,
        ];
    }

    /**
     * @param Trainee $trainee
     * @return Model
     */
    public function getTraineeInfo(Trainee $trainee): Model
    {
        return $trainee;
    }

    /**
     * @param TraineeCourseEnroll $traineeCourseEnroll
     */
    public function changeTraineeCourseEnrollStatusAccept(TraineeCourseEnroll $traineeCourseEnroll)
    {
        $data['enroll_status'] = TraineeCourseEnroll::ENROLL_STATUS_ACCEPT;
        $traineeCourseEnroll->update($data);
    }

    /**
     * @param TraineeCourseEnroll $traineeCourseEnroll
     */
    public function changeTraineeCourseEnrollStatusReject(TraineeCourseEnroll $traineeCourseEnroll)
    {
        $data['enroll_status'] = TraineeCourseEnroll::ENROLL_STATUS_REJECT;
        $traineeCourseEnroll->update($data);
    }

    /**
     * @return JsonResponse
     */
    public function getListDataForDatatable(): JsonResponse
    {
        /** @var Trainee $trainee */
        //$trainee = AuthHelper::getAuthUser();
        $trainee = Trainee::getTraineeByAuthUser();

        /** @var Builder|TraineeCourseEnroll $traineeCourseEnrolls */
        $traineeCourseEnrolls = TraineeCourseEnroll::select([
            'trainee_course_enrolls.id as id',
            'trainee_course_enrolls.trainee_id',
            'trainee_course_enrolls.batch_id',
            'trainee_course_enrolls.course_id',
            'trainee_course_enrolls.enroll_status',
            'trainee_course_enrolls.payment_status',
            'trainee_course_enrolls.created_at as enroll_date',
            'trainee_course_enrolls.updated_at as enroll_updated_date',
            'courses.course_fee as course_fee',
            'batches.title as batch_title',
            'batches.batch_status as batch_status',
            'courses.title as course_title',
            'certificate_requests.id as certificate_requests_id',
            'certificate_requests.row_status as certificate_requests_status',
            'certificate_requests.trainee_batch_id',
            DB::raw('CASE WHEN EXISTS (SELECT Id FROM trainee_certificates WHERE trainee_certificates.certificate_request_id = certificate_requests.id) THEN TRUE  ELSE FALSE  END AS trainee_certificates_id '),
        ]);

        $traineeCourseEnrolls->leftJoin('courses', 'courses.id', '=', 'trainee_course_enrolls.course_id');
        $traineeCourseEnrolls->leftJoin('batches', 'trainee_course_enrolls.batch_id', '=', 'batches.id');
        $traineeCourseEnrolls->leftJoin('certificate_requests', 'trainee_course_enrolls.id', '=', 'certificate_requests.trainee_course_enrolls_id');
        $traineeCourseEnrolls->leftJoin('trainees', 'trainees.id', '=', 'trainee_course_enrolls.trainee_id');
        // $traineeCourseEnrolls->leftJoin('routines', function($join)
        //     {
        //         $join->on('routines.batch_id', '=', 'trainee_course_enrolls.trainee_id');
        //         $join->on('routines.training_center_id', '=', 'batches.training_center_id');
        //     });
        $traineeCourseEnrolls->where('trainee_course_enrolls.trainee_id', $trainee->id);

        return DataTables::eloquent($traineeCourseEnrolls)
            ->addColumn('enroll_status', static function (TraineeCourseEnroll $traineeCourseEnroll) {
                $str = '';
                $str .= '<span href="#" style="width:80px" class="badge ' . ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_PROCESSING ? 'badge-warning' : ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_ACCEPT ? 'badge-success' : 'badge-danger')) . '"> ' . ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_PROCESSING ? 'Processing' : ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_ACCEPT ? 'Accepted' : 'Rejected')) . ' </span>';
                return $str;
            })
            ->addColumn('action', function (TraineeCourseEnroll $traineeCourseEnroll) {
                $str = '';

               if ($traineeCourseEnroll->batch_id) {
                    $str .= '<a href="' . route('frontend.class-routine', $traineeCourseEnroll->id) . '" class="btn btn-info btn-sm mr-1">' . __(' View Routine') . ' </a>';
                }
                if ($traineeCourseEnroll->batch_id != null && $traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_ACCEPT) {

                    if(!empty($traineeCourseEnroll->trainee_certificates_id) && $traineeCourseEnroll->trainee_certificates_id > 0) {
                         $str .= '<a target="_blank" href="'.route('certificate.generation',$traineeCourseEnroll->id).'" data-id="'.$traineeCourseEnroll->id.'"  class="btn btn-info btn-sm trainee-certificate-generation" target="_self">' . __('Download Certificate') . ' </a>';
                    }
                    
                    else if(!empty($traineeCourseEnroll->certificate_requests_id) && $traineeCourseEnroll->certificate_requests_id > 0){
                        if($traineeCourseEnroll->certificate_requests_status === CertificateRequest::REJECTED){
                            $str .= '<a href="#" class="btn btn-danger btn-sm" target="_self">' . __('Request Certificate Rejected') .' </a>';
                        }else{
                            $str .= '<a href="' . route('certificate-request',$traineeCourseEnroll->id) . '"  class="btn btn-info btn-sm" target="_self">' . __('View Request') . ' </a>';
                        }
                    }else if($traineeCourseEnroll->batch_status == Batch::BATCH_STATUS_COMPLETE){
                        $str .= '<a href="' . route('frontend.trainee-batch-trainer', $traineeCourseEnroll->batch_id) . '" class="btn btn-dark btn-sm mr-1">' . __(' Trainers') . ' </a>';
                        $str .= '<a href="' . route('certificate-request',$traineeCourseEnroll->id) . '" class="btn btn-primary btn-sm" target="_self">' . __('Request Certificate') .' </a>';
                        
                    }
                }
                return $str;
            })
            ->rawColumns(['enroll_status', 'action', 'enroll_last_date', 'batch_status'])
            ->toJson();
    }

}
