<?php

namespace App\Services;

use App\Helpers\Classes\DatatableHelper;
use App\Models\Batch;
use App\Models\TraineeCourseEnroll;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TraineeBatchService
{
    public function getTraineeBatchLists(int $batchId): JsonResponse
    {
        /** @var TraineeCourseEnroll $enrolledTrainees */
        $enrolledTrainees = TraineeCourseEnroll::select([
            'trainee_course_enrolls.id as trainee_course_enroll_id',
            'trainees.id as id',
            'trainees.name as trainee_name',
            'trainees.mobile',
            DB::raw('DATE_FORMAT(trainees.created_at,"%d %b, %Y %h:%i %p") AS application_date'),
            'institutes.title as institute_title',
            'courses.title as course_title',
            'enroll_status',
            'batches.batch_status',
            'trainee_course_enrolls.trainee_id',
            'trainee_course_enrolls.created_at as enrollment_date'
        ]);
        $enrolledTrainees->join('trainees', 'trainees.id', '=', 'trainee_course_enrolls.trainee_id');
        $enrolledTrainees->join('batches', 'batches.id', '=', 'trainee_course_enrolls.batch_id');
        $enrolledTrainees->join('courses', 'courses.id', '=', 'trainee_course_enrolls.course_id');
        $enrolledTrainees->join('institutes', 'institutes.id', '=', 'courses.institute_id');
        $enrolledTrainees->where('trainee_course_enrolls.batch_id', $batchId);
        return DataTables::eloquent($enrolledTrainees)
            ->addColumn('action', function (TraineeCourseEnroll $traineeCourseEnroll) {
                $str = '';
                $str .= '<a href="' . route('frontend.trainee-registrations.show', $traineeCourseEnroll->trainee_id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read') . '</a>  ';
                if ($traineeCourseEnroll->batch_status == Batch::BATCH_STATUS_COMPLETE) {
                    $str .= '<a href="' . route('admin.trainer-feedbacks.index',  $traineeCourseEnroll->trainee_course_enroll_id) . '" class="btn btn-outline-secondary btn-sm"> <i class="fas fa-comment-dots"></i></i> ' . __('admin.trainee_batches.feedback') . '</a>';
                }
                return $str;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function validator(Request $request): Validator
    {
        $rules = [
            'id' => [
                'required',
                'int'
            ],
            'feedback' => [
                'required'
            ]
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }


}
