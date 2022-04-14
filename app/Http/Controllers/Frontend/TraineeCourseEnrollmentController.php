<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Classes\AuthHelper;
use App\Http\Controllers\BaseController;
use App\Models\Trainee;
use App\Models\Course;
use App\Models\TraineeCourseEnroll;
use App\Services\TraineeCourseEnrollmentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use App\Events\EnrollEmailEvent;

class TraineeCourseEnrollmentController extends BaseController
{
    const VIEW_PATH = "frontend.";
    protected TraineeCourseEnrollmentService $traineeCourseEnrollmentService;

    public function __construct(TraineeCourseEnrollmentService $traineeCourseEnrollmentService)
    {
        $this->traineeCourseEnrollmentService = $traineeCourseEnrollmentService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function courseEnroll(Request $request): JsonResponse
    {
        //dd($request->all());
        $validatedData = $this->traineeCourseEnrollmentService->validator($request)->validate();

        $authTrainee = Trainee::getTraineeByAuthUser();
        $course = Course::where(['id'=>$request->course_id])->first();
        $email_data = [
            'email' => $authTrainee->email,
            'name' => $authTrainee->name,
            'course' => $course->title,
            'subject' => 'Enrollment registration confirmation email',
            'view' => 'frontend.email.trainee-enroll-email',
            'from' => env('MAIL_FROM_ADDRESS'),
        ];
        
        $isAlreadyEnrolled = TraineeCourseEnroll::where('trainee_id', $authTrainee->id)
            ->where('course_id', $validatedData['course_id'])
            ->first();

        if ($isAlreadyEnrolled) {
            return response()->json([
                'message' => __('You have already enrolled this course'),
                'alertType' => 'info'
            ]);
        }
        DB::beginTransaction();

        try {
            $this->traineeCourseEnrollmentService->saveCourseEnrollData($validatedData);
            // Need to send mail to trainee
            event(new EnrollEmailEvent($email_data));
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());

            return response()->json([
                'message' => __('generic.something_wrong_try_again'),
                'alertType' => 'error'
            ]);
        }

        return response()->json([
            'message' => __('Course enrollment successful!'),
            'alertType' => 'success'
        ]);
    }


    /**
     * @param int $courseId
     * @return View
     */
    public function showCourseEnrollSuccessPage(int $courseId): View
    {
        /** @var Trainee $authTrainee */

        $authTrainee = Trainee::getTraineeByAuthUser();
        $enrolledCourse = TraineeCourseEnroll::where('course_id', $courseId)
            ->where('trainee_id', $authTrainee->id)
            ->first();

        if (!$enrolledCourse) {
            abort(404);
        }

        return \view(self::VIEW_PATH . 'trainee-course-enroll.success', compact('enrolledCourse'));
    }
}
