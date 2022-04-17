<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Classes\AuthHelper;
use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\course;
use App\Models\CertificateRequest;
use App\Models\Trainee;
use App\Models\TraineeCourseEnroll;
use App\Models\TraineeFamilyMemberInfo;
use App\Services\CertificateRequestService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use App\Events\EnrollEmailEvent;

class CertificateController extends Controller
{
    const VIEW_PATH = "frontend.";
    protected CertificateRequestService $certificateRequestService;

    public function __construct(CertificateRequestService $certificateRequestService)
    {
        $this->certificateRequestService = $certificateRequestService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $enroll_id
     * @return mixed
     */
    public function index($enroll_id): View
    {

        /** @var Trainee $trainee */
        $trainee = Trainee::getTraineeByAuthUser();

        $trainee_course_enrolls = TraineeCourseEnroll::find($enroll_id);
        $batch = Batch::find($trainee_course_enrolls->batch_id)->first();

        $info = [];
        if (!$trainee) {
            return redirect()->route('frontend.trainee.login-form')->with([
                    'message' => 'You are not Auth user, Please login',
                    'alert-type' => 'error']
            );
        }
        $isCertificateRequestExist = CertificateRequest::where('trainee_course_enrolls_id', '=', $enroll_id)->first();

        if ($isCertificateRequestExist) {

            $info['name'] = $isCertificateRequestExist->name;
            $info['father'] = $isCertificateRequestExist->father_name;
            $info['mother'] = $isCertificateRequestExist->mother_name;
            $info['date_of_birth'] = $isCertificateRequestExist->date_of_birth;
            $info['edit'] = true;
        } else {
            $info['name'] = $trainee->name;
            $info['father'] = optional($trainee->familyMemberInfo->where('relation_with_trainee', TraineeFamilyMemberInfo::GUARDIAN_FATHER)->first())->name;
            $info['mother'] = optional($trainee->familyMemberInfo->where('relation_with_trainee', TraineeFamilyMemberInfo::GUARDIAN_MOTHER)->first())->name;
            $info['date_of_birth'] = $trainee->date_of_birth;
            $info['edit'] = false;
        }

        return \view(self::VIEW_PATH . 'trainee.certificate.certification-request', compact('enroll_id', 'trainee', 'info', 'batch'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        
        $validateData = $this->certificateRequestService->validator($request)->validate();

        

        try {
            $this->certificateRequestService->requestTraineeCertificate($validateData);
            //nedd to send email
            $trainee = Trainee::getTraineeByAuthUser();
            $traineeCourseEnroll = TraineeCourseEnroll::where(['trainee_id'=>$trainee->id])->first();
            $course = Course::where(['id'=>$traineeCourseEnroll->course_id])->first();
            $email_data = [
                'email' => $trainee->email,
                'name' => $trainee->name,
                'course' => $course->title,
                'subject' => 'Requested certificate confirmation email',
                'view' => 'frontend.email.trainee-request-certificate-email',
                'from' => env('MAIL_FROM_ADDRESS'),
            ];
            event(new EnrollEmailEvent($email_data));
            //dd($email_data);

        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('frontend.trainee-enrolled-courses')->with([
            'message' => __('generic.successfully_created'),
            'alert-type' => 'success'
        ]);
    }
}
