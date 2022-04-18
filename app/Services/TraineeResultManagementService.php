<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Batch;
use App\Models\Trainee;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TraineeResultManagementService
{
    public function getTraineeResultDatatable($batchId): JsonResponse
    {
        $traineesResult = Trainee::select([
            DB::raw('group_concat(distinct trainees.name) as trainee_name'),
            DB::raw('group_concat(distinct trainees.id) as id'),
            DB::raw('group_concat(distinct batches.title) as batch_title'),
            DB::raw('SUM(examinations.total_mark) as total_mark'),
            DB::raw('SUM(examination_results.achieved_marks) as achieved_mark'),
        ]);

        $traineesResult->join('trainee_course_enrolls', 'trainees.id', 'trainee_course_enrolls.trainee_id');
        $traineesResult->join('batches', 'trainee_course_enrolls.batch_id', 'batches.id');
        //$traineesResult->leftJoin('examination_results', 'trainees.id', 'examination_results.trainee_id');
        $traineesResult->join('examination_results', 'examination_results.course_id', 'trainee_course_enrolls.course_id');
        
        $traineesResult->leftJoin('examinations', 'examination_results.examination_id', 'examinations.id');
        $traineesResult->where('trainee_course_enrolls.batch_id', $batchId);
        $traineesResult->where('batches.batch_status', Batch::BATCH_STATUS_COMPLETE);
        $traineesResult->where('examination_results.batch_id', $batchId);
        $traineesResult->groupBy('examination_results.achieved_marks','examinations.total_mark');
        

        return DataTables::eloquent($traineesResult)
            ->addColumn('result', function ($traineesResult) {
                if ($traineesResult->total_mark > 0) {
                    return number_format(($traineesResult->achieved_mark / $traineesResult->total_mark) * 100, 2, '.','');
                }

                return 0;
            })
            ->rawColumns(['result'])
            ->toJson();
    }

    public function getCompletedBatchesDatatable(): JsonResponse
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder|Batch $batches */
        $batches = Batch::acl()->select(
            [
                'batches.id as id',
                'batches.title',
                'institutes.title as institute_title',
                'courses.title as course_title',
                'branches.title as branch_title',
                'training_centers.title as training_center_title',
                'batches.row_status',
                'batches.batch_status',
                'batches.created_at',
                'batches.updated_at',
                DB::raw('(select count(trainee_course_enrolls.id) from trainee_course_enrolls where trainee_course_enrolls.batch_id = batches.id group by trainee_course_enrolls.batch_id) as total_trainee')
            ]
        );
        $batches->where('batch_status', Batch::BATCH_STATUS_COMPLETE);
        $batches->join('courses', 'batches.course_id', '=', 'courses.id');
        $batches->join('institutes', 'batches.institute_id', '=', 'institutes.id');
        $batches->leftJoin('branches', 'batches.branch_id', '=', 'branches.id');
        $batches->join('training_centers', 'batches.training_center_id', '=', 'training_centers.id');

        return DataTables::eloquent($batches)
            ->addColumn('action', function (Batch $batch) use ($authUser) {
                $str = '';

                if ($authUser->can('viewFinalResult', $batch) && $batch->total_trainee > 0) {
                    $str .= '<a href="' . route('admin.show.trainee.final-result-list', $batch->id) . '" class="btn btn-outline-info btn-sm"><i class="fas fa-list-alt"></i> ' . __('generic.trainees_result') . '</a>';
                }

                //$str .= '<a href="' . route('admin.show.trainee.final-result-list', $batch->id) . '" class="btn btn-outline-info btn-sm"><i class="fas fa-list-alt"></i> ' . __('generic.trainees_result') . '</a>';

                return $str;
            })
            ->addColumn('batch_status', function (Batch $batch) {
                $str = '';
                switch ($batch->batch_status) {
                    case Batch::BATCH_STATUS_OPEN_FOR_REGISTRATION:
                        $str .= '<span class="badge badge-info">Open</span>';
                        break;
                    case Batch::BATCH_STATUS_ON_GOING:
                        $str .= '<span class="badge badge-warning">Ongoing</span>';
                        break;
                    case Batch::BATCH_STATUS_COMPLETE:
                        $str .= '<span class="badge badge-success">Completed</span>';
                        break;
                    default:
                        $str .= '';
                }

                return $str;
            })
            ->rawColumns(['action', 'batch_status', 'total_trainee'])
            ->toJson();
    }
}
