<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\ExaminationRoutineDetail;
use App\Models\Routine;
use App\Models\RoutineSlot;
use App\Models\TraineeCourseEnroll;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoutineService
{
    public function createRoutine(array $data): Routine
    {

        $routine = Routine::create($data);
        foreach ($data['daily_routines'] as $dailyRoutine) {
            $dailyRoutine['institute_id'] = $data['institute_id'];
            $dailyRoutine['routine_id'] = $routine->id;
            $routine->routineSlots()->create($dailyRoutine);
        }
        return $routine;
    }

    public function updateRoutine(Routine $routine, array $data): Routine
    {
        $routine->fill($data);
        $routine->save();

        foreach ($data['daily_routines'] as $dailyRoutine) {

            $dailyRoutine['institute_id'] = $data['institute_id'];
            $dailyRoutine['routine_id'] = $routine->id;

            if (empty($dailyRoutine['id'])) {
                $routine->routineSlots()->create($dailyRoutine);
                continue;
            }

            $routineClass = RoutineSlot::findOrFail($dailyRoutine['id']);
            if (!empty($dailyRoutine['delete']) && $dailyRoutine['delete'] == 1) {
                $routineClass->delete();
            } else {
                $routineClass->update($dailyRoutine);
            }
        }

        return $routine;
    }

    /**
     * @throws \Exception
     */
    public function deleteRoutine(Routine $routine): bool
    {
        $routineSlots = RoutineSlot::where('routine_id', $routine->id)->get();
        foreach ($routineSlots as $routineSlot){
            $routineSlot->delete();
        }
        return $routine->delete();
    }

    public function validator(Request $request): Validator
    {
        $rules = [
            'institute_id' => [
                'required',
                'int',
            ],
            'batch_id' => [
                'required',
                'int',
            ],
            'training_center_id' => [
                'required',
                'int',
            ],
            'date' => ['required'],
            'daily_routines.*.user_id' => ['required'],
            'daily_routines.*.class' => ['required', 'string', 'max:30'],
            'daily_routines.*.start_time' => ['required'],
            'daily_routines.*.end_time' => ['required'],
            'daily_routines.*.id' => ['nullable']
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function getRoutineLists(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|Routine $routines */
        $routines = Routine::with('Batch', 'trainingCenter')->select(
            [
                'routines.*'
            ]
        );
        if ($authUser->isUserBelongsToInstitute()) {
            $routines->where('routines.institute_id', '=', $authUser->institute_id);
        }

        return DataTables::eloquent($routines)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (Routine $routine) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $routine)) {
                    $str .= '<a href="' . route('admin.routines.show', $routine->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $routine)) {
                    $str .= '<a href="' . route('admin.routines.edit', $routine->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . '</a>';
                }
                if ($authUser->can('delete', $routine)) {
                    $str .= '<a href="#" data-action="' . route('admin.routines.destroy', $routine->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }

    public function getTraineeRoutine($courseEnrollId): JsonResponse
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder|Routine $routines */
        $routines = Routine::with('Batch', 'trainingCenter')->select(
            [
                'routines.*'
            ]
        );

        $course_enroll = TraineeCourseEnroll::select('trainee_course_enrolls.batch_id as batch_id','batches.training_center_id' )
            ->where('trainee_course_enrolls.id',$courseEnrollId)
            ->leftJoin('batches', 'batches.id','=','trainee_course_enrolls.batch_id')->first();

        $classRoutine = Routine::acl()->select([
            'routine_slots.id as id',
            'routines.date as class_date',
            'routine_slots.class as class_name',
            'routine_slots.start_time as start_time',
            'routine_slots.end_time as end_time',
            'users.name as trainer_name',
        ]);

        $classRoutine->join('routine_slots', 'routines.id', '=', 'routine_slots.routine_id');
        $classRoutine->join('users', 'users.id', '=', 'routine_slots.user_id');
        $classRoutine->where([['routines.batch_id','=',$course_enroll->batch_id],['routines.training_center_id','=',$course_enroll->training_center_id]]);
        $classRoutine->orderBy('routines.date', 'ASC');

        //return DataTables::eloquent($classRoutine)
        return DataTables::eloquent($classRoutine)
            ->editColumn('class_date', function (Routine $routine) use ($authUser) {
                return date('l, j M, y', strtotime($routine->class_date));
            })
        ->toJson();
    }

    public function getTraineeExamRoutine($courseEnrollId): JsonResponse
    {
        $examRoutines = ExaminationRoutineDetail::select([
            'examination_routine_details.examination_id',
            'examination_routines.id',
            'examination_routines.date as date',
            'examination_types.title as exam_type',
            'examination_routine_details.start_time',
            'examination_routine_details.end_time',
            'users.name as trainer_name',
            'batches.title as batch_title',
            'courses.title as course_title',
            'institutes.title as institute_title',
            'training_centers.title as training_center_title',
            'trainee_course_enrolls.id',
            'examinations.pass_mark',
            'examinations.total_mark',
            'examinations.exam_details',
            'examinations.code',
            'examinations.status as exam_status',
            'examination_results.achieved_marks',

        ]);

        $examRoutines->join('examination_routines', 'examination_routines.id', 'examination_routine_details.examination_routine_id');
        $examRoutines->join('institutes', 'institutes.id', 'examination_routines.institute_id');
        $examRoutines->join('training_centers', 'training_centers.id', 'examination_routines.training_center_id');
        $examRoutines->join('batches', 'batches.id', 'examination_routines.batch_id');
        $examRoutines->join('courses', 'batches.course_id', 'courses.id');
        $examRoutines->join('trainee_course_enrolls', 'batches.id', 'trainee_course_enrolls.batch_id');
        $examRoutines->join('examinations', 'examinations.id', 'examination_routine_details.examination_id');
        $examRoutines->join('examination_types', 'examination_types.id', 'examinations.examination_type_id');
        $examRoutines->leftJoin('users', 'users.id', 'examinations.user_id');

        //$examRoutines->leftJoin('examination_results', 'examination_results.course_id', 'courses.id');
        $examRoutines->leftJoin('examination_results', 'examination_results.batch_id', 'batches.id');
        //$examRoutines->leftJoin('examination_results', 'examination_results.examination_id', 'examination_routine_details.examination_id');

        $examRoutines->where('trainee_course_enrolls.id', '=', $courseEnrollId);



        return DataTables::eloquent($examRoutines)
            ->editColumn('date', function (ExaminationRoutineDetail $examinationRoutineDetail){
                return date('l, j F, y', strtotime($examinationRoutineDetail->date));
            })
            ->editColumn('achieved_marks', function (ExaminationRoutineDetail $examinationRoutineDetail){
                return ($examinationRoutineDetail->achieved_marks) ? $examinationRoutineDetail->achieved_marks : 'Not result published yet';
            })
            ->addColumn('exam_status', function (ExaminationRoutineDetail $examinationRoutineDetail) {
                $str = '';

                switch ($examinationRoutineDetail->exam_status) {
                    case 0:
                         $str .= '<p class="badge badge-danger">Not Published</p>';
                        break;

                    case 1:
                        $str .= '<p class="badge badge-success">Published</p>';
                        break;

                    case 2:
                        $str .= '<p class="badge badge-info">Completed</p>';
                        break;
                }

                return $str;

            })
            ->rawColumns(['exam_status','date'])
            ->toJson();
    }

}
