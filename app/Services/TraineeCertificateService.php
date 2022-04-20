<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\Batch;
use App\Models\BatchCertificate;
use App\Models\CertificateRequest;
use App\Models\TraineeCourseEnroll;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TraineeCertificateService
{

    /**
     * @param Request $request
     * @param null $id
     * @return Validator
     */
    public function validator(Request $request, $id = null): Validator
    {

        $rules = [
            'authorized_by' => [
                'required',
                'string',
                'max:191'
            ],
            'issued_date' => [
                'required'
            ],
            'custom_css' => [
            ],
            'batchCertificate_id' => [
            ],
            'batch_id' => [
                'required',
                'int'
            ],
            'certificate_template_id' => [
                'int'
            ],
            'tamplate' => [
                'int'
            ],
            'signature' => [
                'image',
                'max:5000',
                'mimes:jpg,bmp,png,jpeg,svg',

            ],

        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function createBatchCertificate(array $data): BatchCertificate
    {

        $filename = null;
        if (!empty($data['signature'])) {
            $filename = FileHandler::storePhoto($data['signature'], 'signature');
        }

        if ($filename) {
            $data['signature'] = 'signature/' . $filename;
        } else {
            $data['signature'] = '';
        }
        if (!empty($data['batchCertificate_id'])) {
            $batchCertificate = BatchCertificate::find($data['batchCertificate_id']);
            $batchCertificate->update($data);
            return $batchCertificate;
        } else {

            return BatchCertificate::create($data);
        }
    }


    public function getBatchCertificateLists(): JsonResponse
    {
        /** @var $authUser $authUser */
        $authUser = AuthHelper::getAuthUser();

        $traineeCourseEnrolls = TraineeCourseEnroll::select([
                'trainee_course_enrolls.batch_id as batch_id',
                'batches.title as batch_title',
                'courses.title as course_title',
                DB::raw('COUNT(trainee_course_enrolls.trainee_id) as trainee'),
                DB::raw('CASE WHEN EXISTS (SELECT Id FROM batch_certificates WHERE batch_certificates.batch_id = trainee_course_enrolls.batch_id) THEN TRUE  ELSE FALSE  END AS certificate ')
            ]
        );

        if ($authUser->isUserBelongsToInstitute()) {
            $traineeCourseEnrolls->acl();
        }else {
            $traineeCourseEnrolls->join('batches', 'trainee_course_enrolls.batch_id', '=', 'batches.id');
        }

        $traineeCourseEnrolls->join('courses', 'batches.course_id', '=', 'courses.id');
        $traineeCourseEnrolls->leftJoin('batch_certificates', 'batch_certificates.batch_id', '=', 'trainee_course_enrolls.batch_id');
        $traineeCourseEnrolls->where('batches.batch_status', Batch::BATCH_STATUS_COMPLETE);
        //$traineeCourseEnrolls->groupBy('trainee_course_enrolls.batch_id');
        $traineeCourseEnrolls->groupBy('trainee_course_enrolls.batch_id','batches.title','courses.title');


        return DataTables::eloquent($traineeCourseEnrolls)
            ->addColumn('is_certificate', function (TraineeCourseEnroll $traineeCourseEnroll) {
                if ($traineeCourseEnroll->certificate) {
                    return '<div class="btn-group btn-group-sm" role="group"><span class="badge badge-success">Created</span></div>';
                } else {
                    return '<div class="btn-group btn-group-sm" role="group"><span class="badge badge-warning">Not Created</span></div>';
                }
            })
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (TraineeCourseEnroll $traineeCourseEnroll) {
                $str = '';
                $str .= '<a href="' . route('admin.batches.certificates.edit', $traineeCourseEnroll->batch_id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-eye"></i> Certificate </a>';
                return $str;
            }))
            ->rawColumns(['action', 'is_certificate'])
            ->toJson();
    }


    public function getCertificateRequestDatatable(): JsonResponse
    {
        /** @var Builder $traineeBatches */
        $authUser = AuthHelper::getAuthUser();
        $certificateRequests = CertificateRequest::select(
            [
                'certificate_requests.id as id',
                'batches.title as batch_title',
                'courses.title as course_title',
                'certificate_requests.name as name',
            ]
        );
        $certificateRequests->leftJoin('trainee_course_enrolls', 'trainee_course_enrolls.id', '=', 'certificate_requests.trainee_course_enrolls_id');
        $certificateRequests->leftJoin('batches', 'trainee_course_enrolls.batch_id', '=', 'batches.id');
        $certificateRequests->leftJoin('courses', 'trainee_course_enrolls.course_id', '=', 'courses.id');
        $certificateRequests->where('certificate_requests.row_status', '=', CertificateRequest::REQUESTED);
        if($authUser->isUserBelongsToInstitute()){
            $certificateRequests->where('batches.institute_id', '=', $authUser->institute_id);
        }

        return DataTables::eloquent($certificateRequests)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (CertificateRequest $certificateRequest) {
                $str = '';
                //$str .= '<a href="' . route('frontend.trainee-registrations.show', $certificateRequest->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> Read </a>';
                $str .= '<a href="' . route('admin.trainee.certificates.request.view', $certificateRequest->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-eye"></i> Generate certificate </a>';
                return $str;
            }))
            ->rawColumns(['action', 'is_certificate'])
            ->toJson();
    }

    public function getAcceptedCertificateRequestDatatable(): JsonResponse
    {
        /** @var $authUser $authUser */
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder $traineeBatches */

        $certificateRequests = CertificateRequest::select(
            [
                'certificate_requests.id as id',
                'certificate_requests.trainee_course_enrolls_id as trainee_course_enrolls_id',
                'batches.title as batch_title',
                'courses.title as course_title',
                'certificate_requests.name as name',
                'institutes.title as institute_title'
            ]
        );

        $certificateRequests->leftJoin('trainee_course_enrolls', 'trainee_course_enrolls.id', '=', 'certificate_requests.trainee_course_enrolls_id');
        $certificateRequests->leftJoin('batches', 'trainee_course_enrolls.batch_id', '=', 'batches.id');
        $certificateRequests->join('institutes', 'batches.institute_id', '=', 'institutes.id');
        $certificateRequests->leftJoin('courses', 'trainee_course_enrolls.course_id', '=', 'courses.id');
        $certificateRequests->where('certificate_requests.row_status', '=', CertificateRequest::ACCEPTED);
        if($authUser->isUserBelongsToInstitute()){
            $certificateRequests->where('batches.institute_id', '=', $authUser->institute_id);
        }

        return DataTables::eloquent($certificateRequests)
            ->addColumn('row_status', DatatableHelper::getActionButtonBlock(static function () {
                $str = '';
                $str .= '<span class="badge badge-success">Accepted</span> ';
                return $str;
            }))
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (CertificateRequest $certificateRequest) {
                $str = '';
                $str .= '<a target="_blank" href="' . route('certificate.generation', $certificateRequest->trainee_course_enrolls_id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-eye"></i> View Certificate </a>';
                return $str;
            }))
            ->rawColumns(['action', 'row_status'])
            ->toJson();
    }
}
