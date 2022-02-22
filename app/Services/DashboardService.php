<?php


namespace App\Services;


use App\Helpers\Classes\AuthHelper;
use App\Models\Batch;
use App\Models\ExaminationRoutineDetail;
use App\Models\RoutineSlot;
use App\Models\User;

class DashboardService
{
    public function getTotalBatchInfo($batches): array
    {
        $info['title'] = 'Total Batch';
        $info['count'] = $batches->count();
        $info['color'] = 'info';

        return $info;
    }

    public function getRunningBatchInfo($batches): array
    {
        $info['title'] = 'Running Batch';
        $info['count'] = $batches->where('batches.batch_status', Batch::BATCH_STATUS_ON_GOING)->count();
        $info['color'] = 'warning';

        return $info;
    }

    public function getOpenBatchInfo($batches): array
    {
        $info['title'] = 'Open Batch';
        $info['count'] = $batches->where('batches.batch_status', Batch::BATCH_STATUS_OPEN_FOR_REGISTRATION)->count();
        $info['color'] = 'primary';

        return $info;
    }

    public function getCompletedBatchInfo($batches): array
    {
        $info['title'] = 'Completed Batch';
        $info['count'] = $batches->where('batches.batch_status', Batch::BATCH_STATUS_COMPLETE)->count();
        $info['color'] = 'success';

        return $info;
    }


    /**
     * info to show in info card in dashboard
     *
     * @param User $authUser
     * @return array
     */
    public function getAdminInfo(User $authUser): array
    {
        $adminInfo = [];
        $batches = Batch::query();

        if ($authUser->isUserBelongsToInstitute()) {
            $batches->where('batches.institute_id', $authUser->institute_id);
        } else if ($authUser->isTrainer()) {
            $batches->join('trainer_batches', 'trainer_batches.batch_id', '=', 'batches.id')
                ->where('trainer_batches.user_id', $authUser->id);
        }else if ($authUser->isTrainingCenterLevelUser()) {
            $batches->where( 'batches.training_center_id', $authUser->training_center_id);
        }else if ($authUser->isBranchLevelUser()) {
            $batches->where( 'batches.branch_id', $authUser->branch_id);
        }

        $adminInfo['total_batch'] = $this->getTotalBatchInfo(clone $batches);
        $adminInfo['open_batch'] = $this->getOpenBatchInfo(clone $batches);
        $adminInfo['total_running_batch'] = $this->getRunningBatchInfo(clone $batches);
        $adminInfo['total_completed_batch'] = $this->getCompletedBatchInfo(clone $batches);

        return $adminInfo;
    }


    public function examSchedules($request)
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        $routineSlot = ExaminationRoutineDetail::select([
            'examination_routine_details.examination_id',
            'examination_routine_details.start_time',
            'examination_routine_details.end_time',
            'examination_routine_details.examination_id',
            'examination_routines.batch_id',
            'batches.title as batch_title',
            'examination_routines.date',
        ]);

        $routineSlot->join('examination_routines', 'examination_routines.id', '=', 'examination_routine_details.examination_routine_id')
            ->join('batches', 'batches.id', '=', 'examination_routines.batch_id')
            ->join('trainer_batches', 'trainer_batches.batch_id', '=', 'batches.id')
            ->where('trainer_batches.user_id', '=', $authUser->id);

        $dateFilter = $request->input('date');

        if (!empty($dateFilter)) {
            $routineSlot->whereDate('examination_routines.date', $dateFilter);
        }

        return $routineSlot;
    }


    public function classSchedules($request)
    {
        $routineSlot = RoutineSlot::select([
            'routine_slots.start_time',
            'routine_slots.end_time',
            'routine_slots.class',
            'routines.batch_id',
            'batches.title as batch_title',
            'routines.date',
        ]);

        $routineSlot->where('user_id', AuthHelper::getAuthUser()->id)
            ->join('routines', 'routines.id', '=', 'routine_slots.routine_id')
            ->join('batches', 'batches.id', '=', 'routines.batch_id');

        $dateFilter = $request->input('date');
        if ($dateFilter) {
            $routineSlot->whereDate('routines.date', $dateFilter);
        }

        return $routineSlot;
    }

}
