<?php

namespace App\Http\Controllers;

use App\Helpers\Classes\AuthHelper;
use App\Models\Batch;
use App\Models\ExaminationRoutine;
use App\Models\ExaminationRoutineDetail;
use App\Models\Routine;
use App\Models\RoutineSlot;
use App\Models\TrainingCenter;
use App\Models\User;
use App\Models\UserType;
use App\Services\RoutineService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\isEmpty;

class RoutineController extends Controller
{
    const VIEW_PATH = 'backend.routines.';
    public RoutineService $routineService;

    public function __construct(RoutineService $routineService)
    {
        $this->routineService = $routineService;
        $this->authorizeResource(Routine::class);
    }

    /**
     * @return View
     */
    public function index()
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $batches = Batch::acl()->active()->pluck('title', 'id');
        $trainingCenters = TrainingCenter::acl()->active()->pluck('title', 'id');
        $trainers = User::acl()->where(['user_type_id' => UserType::USER_TYPE_TRAINER_USER_CODE])->get();
        
        return view(self::VIEW_PATH . 'edit-add', compact('batches', 'trainingCenters', 'trainers'));
    }


    public function store(Request $request): RedirectResponse
    {
        $authUser = AuthHelper::getAuthUser();
        if ($authUser->isUserBelongsToInstitute()) {
            $request->merge(['institute_id', $authUser->institute_id]);
        }

        $validatedData = $this->routineService->validator($request)->validate();

        try {
            $this->routineService->createRoutine($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.routines.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Routine']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Routine $routine
     * @return View
     */
    public function show(Routine $routine): View
    {
        $routineClasses = RoutineSlot::where(['routine_id' => $routine->id])->get();
        return view(self::VIEW_PATH . 'read', compact('routine', 'routineClasses'));
    }

    /**
     * @param Routine $routine
     * @return View
     */
    public function edit(Routine $routine): View
    {
        $routineData = Routine::where(['id' => $routine->id])->with('routineSlots')->get();
        $trainers = User::acl()->where(['user_type_id' => UserType::USER_TYPE_TRAINER_USER_CODE])->get();
        return view(self::VIEW_PATH . 'edit-add', compact('routine', 'trainers', 'routineData'));
    }

    /**
     * @param Request $request
     * @param Routine $routine
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Routine $routine): RedirectResponse
    {
        $validatedData = $this->routineService->validator($request)->validate();

        try {
            $this->routineService->updateRoutine($routine, $validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.routines.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Routine']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Routine $routine
     * @return RedirectResponse
     */
    public function destroy(Routine $routine): RedirectResponse
    {
        try {
            $this->routineService->deleteRoutine($routine);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Routine']),
            'alert-type' => 'success'
        ]);
    }


    public function getDatatable(Request $request): JsonResponse
    {
        return $this->routineService->getRoutineLists($request);
    }


    /**
     * @return View
     */
    public function dailyRoutine(): View
    {
        return view(self::VIEW_PATH . 'daily-routine');
    }

    /**
     * @return mixed
     */
    public function allRoutineEvents()
    {
        $classRoutine = Routine::select([
            DB::raw('DATE(DATE_FORMAT(routines.date, "%Y-%c-%d")) as start'),
            DB::raw('DATE(DATE_FORMAT(routines.date, "%Y-%c-%d")) as end'),
            DB::raw('count(*) as title')
        ]);


        $examRoutine = ExaminationRoutine::select([
            DB::raw('DATE(DATE_FORMAT(examination_routines.date, "%Y-%c-%d")) as start'),
            DB::raw('DATE(DATE_FORMAT(examination_routines.date, "%Y-%c-%d")) as end'),
            DB::raw('count(*) as title')
        ]);

        $classRoutine = $classRoutine->groupBy('start')->get();
        $examRoutine = $examRoutine->groupBy('start')->get();

        return array_merge($classRoutine->toArray(), $examRoutine->toArray());
    }

    /**
     * @param Request $request
     * @return Builder[]|Collection
     */
    public function getRoutine(Request $request)
    {
        $date = $request->input('date');

        /** @var RoutineSlot $classRoutines */
        $classRoutines = RoutineSlot::select([
            'routines.id',
            'routines.date',
            'routine_slots.class',
            'routine_slots.start_time',
            'routine_slots.end_time',
            'users.name as trainer_name',
            'batches.title as batch_title',
            'institutes.title as institute_title',
            'training_centers.title as training_center_title'
        ]);

        $classRoutines->join('routines', 'routines.id', 'routine_slots.routine_id');
        $classRoutines->join('batches', 'batches.id', 'routines.batch_id');
        $classRoutines->join('users', 'users.id', 'routine_slots.user_id');
        $classRoutines->join('institutes', 'institutes.id', 'routines.institute_id');
        $classRoutines->join('training_centers', 'training_centers.id', 'routines.training_center_id');
        $classRoutines->whereDate('routines.date', $date);

        /** @var ExaminationRoutineDetail $examRoutines */
        $examRoutines = ExaminationRoutineDetail::select([
            'examination_routines.id',
            'examination_routines.date',
            'examination_types.title as class',
            'examination_routine_details.start_time',
            'examination_routine_details.end_time',
            'users.name as trainer_name',
            'batches.title as batch_title',
            'institutes.title as institute_title',
            'training_centers.title as training_center_title',
        ]);

        $examRoutines->join('examination_routines', 'examination_routines.id', 'examination_routine_details.examination_routine_id');
        $examRoutines->join('institutes', 'institutes.id', 'examination_routines.institute_id');
        $examRoutines->join('training_centers', 'training_centers.id', 'examination_routines.training_center_id');
        $examRoutines->join('batches', 'batches.id', 'examination_routines.batch_id');
        $examRoutines->join('examinations', 'examinations.id', 'examination_routine_details.examination_id');
        $examRoutines->join('examination_types', 'examination_types.id', 'examinations.examination_type_id');
        $examRoutines->leftJoin('users', 'users.id', 'examinations.user_id');
        $classRoutines->whereDate('date', $date);

        $classRoutines = $classRoutines->get()->where('date','=', $date);
        $examRoutines = $examRoutines->get()->where('date','=', $date);

        /* merge two routines to show together in calendar*/
        $data = array_merge($classRoutines->toArray(), $examRoutines->toArray());

        return [
            'data' => $data,
            'msg' => isEmpty($data) ? __('generic.no_event_found') : 'event_found'
        ];
    }
}
