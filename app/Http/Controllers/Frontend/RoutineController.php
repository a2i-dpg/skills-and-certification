<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Classes\AuthHelper;
use App\Http\Controllers\Controller;
use App\Models\Trainee;
use App\Models\TraineeAcademicQualification;
use App\Models\TraineeCourseEnroll;
use App\Models\TraineeFamilyMemberInfo;
use App\Services\RoutineService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSchema\Validator;


class RoutineController extends Controller
{
    const VIEW_PATH = "frontend.routine.";
    protected RoutineService $routineService;

    public function __construct(RoutineService $routineService)
    {
        $this->routineService = $routineService;
    }

    /**
     * @param $course_enroll_id
     * @return View
     */

    public function index($course_enroll_id)
    {
        $trainee = Trainee::getTraineeByAuthUser();

        abort_if(!$trainee, 401, 'You are not Auth user, Please login');

        //$trainee = Trainee::findOrFail($trainee->id);
        $trainee = Trainee::find($trainee->id);

        //dd($trainee);

        $trainee->load([
            'traineeRegistration',
        ]);

        $academicQualifications = TraineeAcademicQualification::where(['trainee_id' => $trainee->id])->get();

        $traineeSelfInfo = TraineeFamilyMemberInfo::where(['trainee_id' => $trainee->id, 'relation_with_trainee' => 'self'])->first();

        $count = TraineeCourseEnroll::select()->where('id', $course_enroll_id)->count();
        
        if ($count == 0 ) {
            return redirect()->route('frontend.trainee-enrolled-courses')->with([
                'message' => __('Something went wrong'),
                'alert-type' => 'warning'
            ]);
        }
        $courseEnrollInfo = TraineeCourseEnroll::select()->where('id', $course_enroll_id)->with('course', 'batch')->first();

        return \view(self::VIEW_PATH . 'trainee-routine')->with(
            [
                'trainee' => $trainee,
                'haveTraineeFamilyMembersInfo' => !empty($traineeFamilyMembers['haveTraineeFamilyMembersInfo']) ? $traineeFamilyMembers['haveTraineeFamilyMembersInfo'] : [],
                'traineeSelfInfo' => $traineeSelfInfo,
                'academicQualifications' => $academicQualifications,
                'course_enroll_id' => $course_enroll_id,
                'courseEnrollInfo' => $courseEnrollInfo
            ]);
    }


    /**
     * @param Request $request
     * @param $courseEnrollId
     * @return JsonResponse
     */
    public function getTraineeRoutine(Request $request, $courseEnrollId): JsonResponse
    {
        return $this->routineService->getTraineeRoutine($courseEnrollId);
    }

    public function getTraineeExamRoutine(Request $request, $courseEnrollId): JsonResponse
    {
        return $this->routineService->getTraineeExamRoutine($courseEnrollId);
    }

}
