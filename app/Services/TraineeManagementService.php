<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\Batch;
use App\Models\Course;
use App\Models\TraineeBatch;
use App\Models\TraineeCourseEnroll;
use App\Models\TraineeRegistration;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TraineeManagementService
{
    /**
     *
     * validation during addition trainee to batch
     *
     * @param Request $request
     * @return Validator
     */
    public function validateAddTraineeToBatch(Request $request): Validator
    {
        $rules = [
            'batch_id' => ['bail', 'required'],
            'trainee_enroll_ids' => ['bail', 'required', 'array', 'min:1'],
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function getListDataForDatatable($request): JsonResponse
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();
        $trainee = TraineeCourseEnroll::select([
            'trainee_course_enrolls.id as trainee_course_enroll_id',
            'trainees.id as id',
            'trainees.name',
            'trainees.mobile',
            DB::raw('DATE_FORMAT(trainee_course_enrolls.created_at,"%d %b, %Y %h:%i %p") AS application_date'),
            'institutes.title as institute_title',
            'courses.title as course_title',
            'enroll_status',
        ]);

        $trainee->join('trainees', 'trainees.id', '=', 'trainee_course_enrolls.trainee_id');
        $trainee->join('courses', 'courses.id', '=', 'trainee_course_enrolls.course_id');
        $trainee->join('institutes', 'institutes.id', '=', 'courses.institute_id');
        $trainee->leftjoin('batches', 'trainee_course_enrolls.batch_id', '=', 'batches.id');
        $trainee->leftjoin('training_centers', 'batches.training_center_id', '=', 'training_centers.id');
        $trainee->where('enroll_status', TraineeCourseEnroll::ENROLL_STATUS_PROCESSING);

        if ($authUser->isUserBelongsToInstitute()) {
            $trainee->where('institutes.id', $authUser->institute_id);
        }

        $instituteId = $request->input('institute_id');
        $branchId = $request->input('branch_id');
        $trainingCenterId = $request->input('training_center_id');
        $courseId = $request->input('course_id');
        $applicationDate = $request->input('application_date');


        if ($instituteId) {
            $trainee->where('courses.institute_id', $instituteId);
        }

        if ($branchId) {
            $trainee->where('trainee_course_enrolls.branch_id', $branchId);
        }
        if ($trainingCenterId) {
            $trainee->where('training_centers.id', $trainingCenterId);
        }
        if ($courseId) {
            $trainee->where('courses.id', $courseId);
        }

        if ($applicationDate) {
            $trainee->whereDate('trainees.created_at', Carbon::parse($applicationDate)->format('Y-m-d'));
        }

        return DataTables::eloquent($trainee)
            ->addColumn('enroll_status', DatatableHelper::getActionButtonBlock(static function (TraineeCourseEnroll $traineeCourseEnroll) {

                $str = '';
                if ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_PROCESSING) {
                    $str .= '<span class="badge badge-warning">Processing</span>';
                } else if ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_ACCEPT) {
                    $str .= '<span class="badge badge-success">Accept</span>';
                } else if ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_REJECT) {
                    $str .= '<span class="badge badge-danger">Reject</span>';
                }

                return $str;
            }))
            ->addColumn('payment_status', DatatableHelper::getActionButtonBlock(static function (TraineeCourseEnroll $traineeCourseEnroll) {
                $str = '';
                $str .= '<span style="width:70px" ' . '" class="badge badge-' . ($traineeCourseEnroll->payment_status ? "success payment-paid" : "danger payment-unpaid") . '">' . ($traineeCourseEnroll->payment_status ? "Paid" : "Unpaid") . ' </span>';
                return $str;
            }))
            ->editColumn('registration_date', function (TraineeCourseEnroll $traineeCourseEnroll) {
                return date('d M Y', strtotime($traineeCourseEnroll->created_at));
            })
            ->addColumn('paid_or_unpaid', static function (TraineeCourseEnroll $traineeCourseEnroll) {
                return $traineeCourseEnroll->payment_status;
            })
            ->addColumn('enroll_status_check', static function (TraineeCourseEnroll $traineeCourseEnroll) {
                return $traineeCourseEnroll->enroll_status;
            })
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (TraineeCourseEnroll $traineeCourseEnroll) {
                $str = '';
                $str .= '<a href="' . route('frontend.trainee-registrations.show', $traineeCourseEnroll->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';

                if ($traineeCourseEnroll->enroll_status != TraineeCourseEnroll::ENROLL_STATUS_ACCEPT) {
                    $str .= '<a href="#" id="' . $traineeCourseEnroll->trainee_course_enroll_id . '" data-action="' . route('admin.trainee-course-enroll-accept', $traineeCourseEnroll->id) . '"' . ' class="btn btn-outline-success btn-sm accept-application"> <i class="fas fa-check-circle"></i> ' . __('Accept Now') . ' </a>';
                }

                // if ($traineeCourseEnroll->enroll_status != TraineeCourseEnroll::ENROLL_STATUS_REJECT) {
                //     $str .= '<a href="#" data-action="' . route('admin.trainee-course-enroll-reject', $traineeCourseEnroll->id) . '"' . ' class="btn btn-outline-danger btn-sm reject-application"> <i class="fas fa-times-circle"></i> ' . __('Reject') . ' </a>';
                // }

                return $str;
            }))
            ->rawColumns(['action', 'enroll_status', 'payment_status', 'paid_or_unpaid', 'enroll_status_check'])
            ->toJson();
    }

    public function getPreferredBatch($id): JsonResponse
    {
        $traineeCourseEnroll = TraineeCourseEnroll::find($id);
        $batchList = Course::find($traineeCourseEnroll->course_id)->runningBatches;

        return response()->json(['message' => 'success', 'data' => ['preferedBatch' => $traineeCourseEnroll, 'batchList' => $batchList]]);
    }

    public function addTraineeToBatch(Batch $batch, array $traineeCourseEnrolls): bool
    {
        foreach ($traineeCourseEnrolls as $traineeCourseEnrollId) {
            /** @var TraineeRegistration $traineeCourseEnroll */
            $traineeCourseEnroll = TraineeCourseEnroll::findOrFail($traineeCourseEnrollId);

            TraineeBatch::updateOrCreate(
                [
                    'batch_id' => $batch->id,
                    'trainee_course_enroll_id' => $traineeCourseEnroll->id,
                ],
                [
                    'enrollment_date' => now(),
                    'enrollment_status' => TraineeBatch::ENROLLMENT_STATUS_ENROLLED,
                ]
            );
        }
        return true;
    }

}
