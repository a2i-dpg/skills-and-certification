<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Trainee;
use App\Models\TraineeCourseEnroll;
use App\Services\TraineeRegistrationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class TraineeRegistrationController extends Controller
{
    const VIEW_PATH = 'frontend.trainee-registration.';
    protected TraineeRegistrationService $traineeRegistrationService;

    public function __construct(TraineeRegistrationService $traineeRegistrationService)
    {
        $this->traineeRegistrationService = $traineeRegistrationService;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return \view(self::VIEW_PATH . 'registration-form');
    }

    /**
     * applied trainee for registration data view
     * @param $traineeId
     * @return View
     */
    public function show($traineeId): View
    {
        //$trainee = Trainee::findOrFail($traineeId);
        $trainee = Trainee::find($traineeId);

        
        $guardians = $trainee->familyMemberInfo;
        $traineeAcademicQualifications = $this->traineeRegistrationService->getTraineeAcademicQualification($trainee);
        $traineeSelfInfo = $this->traineeRegistrationService->getTraineeInfo($trainee);
        return \view('backend.trainees.cv-view-one', compact('trainee','guardians','traineeAcademicQualifications','traineeSelfInfo'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->traineeRegistrationService->validator($request)->validate();

        DB::beginTransaction();
        try {
            $this->traineeRegistrationService->createRegistration($validated);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());

            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alertType' => 'error'
            ])->withInput();
        }

        return redirect()->route('registration-verification.notice')->with([
            'message' => __('generic.successfully_registered'),
            'alertType' => 'success',
        ]);
    }

    /**
     * show email verification notice page after trainee registration submit
     *
     * @return View
     */
    public function showVerificationNotice(): View
    {
        return \view('acl.auth.trainee-email-verification-notice')->with([
            'message' => __('generic.successfully_registered'),
            'alertType' => 'success',
        ]);
    }

    public function registrationSuccess($accessKey)
    {
        return \view('frontend/trainee-registrations/application-success-message', compact('accessKey'));
    }


    public function acceptTraineeCourseEnroll($traineeCourseEnrollId)
    {
        $traineeCourseEnroll = TraineeCourseEnroll::findOrFail($traineeCourseEnrollId);

        /**
         * Check trainee application already rejected or not
         * */
        if ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_REJECT) {
            return back()->with([
                'message' => __('Already rejected this application'),
                'alert-type' => 'warning'
            ]);
        }

        /**
         * Check trainee application already accepted or not
         * */
        if ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_ACCEPT) {
            return back()->with([
                'message' => __('Already accepted this application'),
                'alert-type' => 'warning'
            ]);
        }

        try {
            if (!empty($traineeCourseEnroll->trainee->mobile)) {
                try {
                    $link = route('frontend.trainee-enrolled-courses');
                    $traineeName = strtoupper($traineeCourseEnroll->trainee->name);
                    $messageBody = "Dear $traineeName, Your course enrolment is accepted. Please payment within 72 hours. visit " . $link . " for payment";
                    $smsResponse = sms()->send($traineeCourseEnroll->trainee->mobile, $messageBody);
                    if (!$smsResponse->is_successful()) {
                        sms()->send($traineeCourseEnroll->trainee->mobile, $messageBody);
                    }
                } catch (\Throwable $exception) {
                    Log::debug($exception->getMessage());
                }
            }

            /**
             * Send mail to trainee for conformation
             * */
            $traineeEmailAddress = $traineeCourseEnroll->trainee->email;
            $mailMsg = "Congratulations! Your application has been accepted, Please pay now within 72 hours.<p>Payment Link: https://www.test.com.bd</p>";
            $mailSubject = "Congratulations! Your application has been accepted";
            $traineeName = $traineeCourseEnroll->trainee->name;
            try {
                Mail::to($traineeEmailAddress)->send(new \App\Mail\TraineeApplicationAcceptMail($mailSubject, $traineeCourseEnroll->trainee->access_key, $mailMsg, $traineeName));
            } catch (\Throwable $exception) {
                Log::debug($exception->getMessage());
                return back()->with([
                    'message' => __('Something is wrong, Please try again'),
                    'alert-type' => 'error'
                ])->withInput();
            }

            /**
             * Changing Enroll Status
             * */
            $this->traineeRegistrationService->changeTraineeCourseEnrollStatusAccept($traineeCourseEnroll);

        } catch (\Throwable $exception) {
            return response()->json([
                'message' => __('generic.something_wrong_try_again'),
                'alertType' => 'error'
            ]);
        }


        return redirect()->back()->with([
            'message' => __('Trainee course enroll accepted & notifying to trainee'),
            'alertType' => 'success',
        ]);
    }

    public function rejectTraineeCourseEnroll($traineeCourseEnrollId)
    {
        $traineeCourseEnroll = TraineeCourseEnroll::findOrFail($traineeCourseEnrollId);

        /**
         * Check trainee application already accepted or not
         * */
        if ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_ACCEPT) {
            return back()->with([
                'message' => __('Already accepted this application'),
                'alert-type' => 'warning'
            ]);
        }

        /**
         * Check trainee application already accepted or not
         * */
        if ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_REJECT) {
            return back()->with([
                'message' => __('Already rejected this application'),
                'alert-type' => 'warning'
            ]);
        }

        try {

            /**
             * Send mail to trainee for reject conformation
             * */
            $traineeEmailAddress = $traineeCourseEnroll->trainee->email;
            $mailMsg = 'Sorry! Your application has been rejected, Please enroll again by your account access key. <p>Courses link: <a href="' . (route('frontend.course_search')) . '">Courses</a></p>';
            $mailSubject = "Your application has been rejected";
            $traineeName = $traineeCourseEnroll->trainee->name;
            try {
                Mail::to($traineeEmailAddress)->send(new \App\Mail\TraineeApplicationRejectMail($mailSubject, $traineeCourseEnroll->trainee->access_key, $mailMsg, $traineeName));
            } catch (\Throwable $exception) {
                Log::debug($exception->getMessage());
                return back()->with([
                    'message' => __('Something wrong try again'),
                    'alert-type' => 'error'
                ])->withInput();
            }

            /**
             * Changing Enroll Status
             * */
            $this->traineeRegistrationService->changeTraineeCourseEnrollStatusReject($traineeCourseEnroll);

        } catch (\Throwable $exception) {
            return response()->json([
                'message' => __('generic.something_wrong_try_again'),
                'alertType' => 'error'
            ]);
        }


        return redirect()->back()->with([
            'message' => __('Trainee course enroll rejected & notifying to trainee'),
            'alertType' => 'success',
        ]);
    }
}
