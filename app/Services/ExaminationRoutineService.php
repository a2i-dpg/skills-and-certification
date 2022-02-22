<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\ExaminationRoutine;
use App\Models\ExaminationRoutineDetail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Routine;
use App\Models\RoutineSlot;
use Yajra\DataTables\Facades\DataTables;

class ExaminationRoutineService
{
    public function createExaminationRoutine(array $data): ExaminationRoutine
    {
        $examinationRoutine = ExaminationRoutine::create($data);
        foreach($data['daily_routines'] as $dailyRoutine){
            $dailyRoutine['institute_id'] = $examinationRoutine->institute_id;
            $dailyRoutine['examination_routine_id'] = $examinationRoutine->id;
            $examinationRoutine->examinationRoutineDetail()->create($dailyRoutine);
        }
        return $examinationRoutine;
    }

    public function updateExaminationRoutine(ExaminationRoutine $examinationRoutine, array $data): ExaminationRoutine
    {
        $examinationRoutine->fill($data);
        $examinationRoutine->save();
        $authUser = AuthHelper::getAuthUser();

        foreach($data['daily_routines'] as $dailyRoutine){
            $dailyRoutine['institute_id'] = $authUser->institute_id;
            $dailyRoutine['examination_routine_id'] = $examinationRoutine->id;
            if (empty($dailyRoutine['id'])) {
                $examinationRoutine->examinationRoutineDetail()->create($dailyRoutine);
                continue;
            }
            $examinationRoutineDetail = ExaminationRoutineDetail::findOrFail($dailyRoutine['id']);
            if (!empty($dailyRoutine['delete']) && $dailyRoutine['delete'] == 1) {
                $examinationRoutineDetail->delete();
            } else {
                $examinationRoutineDetail->update($dailyRoutine);
            }
        }

        return $examinationRoutine;
    }

    /**
     * @throws \Exception
     */
    public function deleteExaminationRoutine(ExaminationRoutine $examinationRoutine): bool
    {
        $examinationRoutineDetails = ExaminationRoutineDetail::where('examination_routine_id', $examinationRoutine->id)->get();
        foreach ($examinationRoutineDetails as $examinationRoutineDetail){
            $examinationRoutineDetail->delete();
        }
        return $examinationRoutine->delete();
    }

    public function validator(Request $request): Validator
    {
        $rules = [

            'batch_id' => [
                'required',
                'int',
            ],
            'institute_id' => [
                'required',
                'int',
            ],
            'training_center_id' => [
                'required',
                'int',
            ],
            'date' => ['required'],
            'daily_routines.*.examination_id' => ['required'],
            'daily_routines.*.start_time' => ['required'],
            'daily_routines.*.end_time' => ['required']
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function getExaminationRoutineLists(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|ExaminationRoutine $examinationRoutines */
        $examinationRoutines = ExaminationRoutine::with('Batch','trainingCenter')->select(
            [
                'examination_routines.*',
            ]
        );
        if ($authUser->isUserBelongsToInstitute()) {
            $examinationRoutines->where('examination_routines.institute_id', $authUser->institute_id);
        }
        return DataTables::eloquent($examinationRoutines)
            ->editColumn('date', function (ExaminationRoutine $examinationRoutine) use ($authUser) {
                return date('F j, y', strtotime($examinationRoutine->date));
            })
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (ExaminationRoutine $examinationRoutine) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $examinationRoutine)) {
                    $str .= '<a href="' . route('admin.examination-routines.show', $examinationRoutine->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $examinationRoutine)) {
                    $str .= '<a href="' . route('admin.examination-routines.edit', $examinationRoutine->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . '</a>';
                }
                if ($authUser->can('delete', $examinationRoutine)) {
                    $str .= '<a href="#" data-action="' . route('admin.examination-routines.destroy', $examinationRoutine->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }
}
