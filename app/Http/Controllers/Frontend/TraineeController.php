<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Classes\AuthHelper;
use App\Http\Controllers\Controller;
use App\Models\Institute;
use App\Models\Payment;
use App\Models\QuestionAnswer;
use App\Models\TrainerBatch;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\Trainee;
use App\Models\TraineeAcademicQualification;
use App\Models\TraineeAcademicQualificationn;
use App\Models\TraineeBatch;
use App\Models\TraineeCourseEnroll;
use App\Models\TraineeFamilyMemberInfo;
use App\Services\CertificateGenerator;
use App\Services\TraineeRegistrationService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class TraineeController extends Controller
{
    const VIEW_PATH = "frontend.";
    protected TraineeRegistrationService $traineeRegistrationService;

    public function __construct(TraineeRegistrationService $traineeRegistrationService)
    {
        $this->traineeRegistrationService = $traineeRegistrationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var Trainee $trainee */
        $trainee = Trainee::getTraineeByAuthUser();
        
        if (!$trainee) {
            return redirect()->route('frontend.trainee.login-form')->with([
                    'message' => 'You are not Auth user, Please login',
                    'alert-type' => 'error']
            );
        }

        $trainee->load([
            'traineeRegistration',
        ]);
        $academicQualifications = TraineeAcademicQualification::where(['trainee_id' => $trainee->id])
            ->orderBy('examination', 'desc')
            ->get();

        $academicQualificationns = TraineeAcademicQualificationn::where(['trainee_id' => $trainee->id])
            ->orderBy('id', 'asc')
            ->get();


        $guardians = $trainee->familyMemberInfo;

        return \view(self::VIEW_PATH . 'trainee.trainee-profile')->with(
            [
                'trainee' => $trainee,
                'guardians' => $guardians,
                'academicQualifications' => $academicQualifications,
                'academicQualificationns' => $academicQualificationns,
            ]);
    }


    public function traineeEnrolledCourses()
    {
        /** @var Trainee $trainee */
        $trainee = Trainee::getTraineeByAuthUser();

        if (!$trainee) {
            return redirect()->route('frontend.trainee.login-form')->with([
                    'message' => 'You are not logged In, Please login first',
                    'alert-type' => 'error']
            );
        }

        $trainee->load([
            'traineeRegistration',
        ]);

        $academicQualifications = TraineeAcademicQualification::where(['trainee_id' => $trainee->id])->get();
        $traineeSelfInfo = TraineeFamilyMemberInfo::where(['trainee_id' => $trainee->id, 'relation_with_trainee' => 'self'])->first();
        $traineeFamilyMembers = $this->traineeRegistrationService->getTraineeFamilyMemberInfo($trainee);

        return \view(self::VIEW_PATH . 'trainee.trainee-courses')->with(
            [
                'trainee' => $trainee,
                'haveTraineeFamilyMembersInfo' => !empty($traineeFamilyMembers['haveTraineeFamilyMembersInfo']) ? $traineeFamilyMembers['haveTraineeFamilyMembersInfo'] : [],
                'traineeSelfInfo' => $traineeSelfInfo,
                'academicQualifications' => $academicQualifications,
            ]);
    }

    public function traineeCertificateView(TraineeCourseEnroll $traineeCourseEnroll)
    {
        $trainee = Trainee::getTraineeByAuthUser();

        if (!$trainee) {
            return redirect()->route('frontend.trainee.login-form')->with([
                    'message' => 'You are not Auth user, Please login',
                    'alert-type' => 'error']
            );
        }

        $traineeBatch = TraineeBatch::where(['trainee_course_enroll_id' => $traineeCourseEnroll->id])->first();

        $familyInfo = TraineeFamilyMemberInfo::where("trainee_id", $traineeCourseEnroll->trainee_id)->where('relation_with_trainee', "father")->first();
        $institute = $traineeCourseEnroll->publishCourse->institute;
        $path = "trainee-certificates/" . date('Y/F/', strtotime($traineeBatch->batch->start_date)) . "course/" . Str::slug($traineeCourseEnroll->publishCourse->course->title) . "/batch/" . $traineeBatch->batch->title;

        $traineeInfo = [
            'trainee_id' => $traineeCourseEnroll->trainee_id,
            'trainee_name' => $traineeCourseEnroll->trainee->name,
            'trainee_father_name' => $familyInfo->member_name,
            'publish_course_id' => $traineeCourseEnroll->publish_course_id,
            'publish_course_name' => $traineeCourseEnroll->publishCourse->course->title,
            'path' => $path,
            "register_no" => $traineeCourseEnroll->trainee->trainee_registration_no,
            'institute_title' => $institute->title,
            'from_date' => $traineeBatch->batch->start_date,
            'to_date' => $traineeBatch->batch->end_date,
            'batch_name' => $traineeBatch->batch->title,
            'course_coordinator_signature' => "storage/{$traineeBatch->batch->trainingCenter->course_coordinator_signature}",
            'course_director_signature' => "storage/{$traineeBatch->batch->trainingCenter->course_director_signature}",
        ];

        $template = 'frontend.trainee/certificate/certificate-one';
        $pdf = app(CertificateGenerator::class);
        return redirect(asset("storage/" . $pdf->generateCertificate($template, $traineeInfo)));
    }

    public function videos(): View
    {
        $currentInstitute = app('currentInstitute');

        $traineeVideoCategories = VideoCategory::query();
        $traineeVideos = Video::query();

        if ($currentInstitute) {
            $traineeVideoCategories->where(['institute_id' => $currentInstitute->id]);
            $traineeVideos->where(['institute_id' => $currentInstitute->id]);
        }

        $traineeVideoCategories = $traineeVideoCategories->get();
        $traineeVideos = $traineeVideos->get();

        return \view(self::VIEW_PATH . 'skill-videos', compact('traineeVideos', 'traineeVideoCategories'));
    }

    public function singleVideo($videoId): View
    {
        $traineeVideos = Video::findOrFail($videoId);
        return \view(self::VIEW_PATH . 'skill-single-video', compact('traineeVideos'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function videoSearch(Request $request): JsonResponse
    {
        if ($request->json()) {

            $videos = Video::select([
                'videos.id as id',
                'videos.title',
                'videos.description',
                'videos.youtube_video_id',
                'videos.video_type',
                'videos.uploaded_video_path',
                'videos.institute_id',
                'videos.video_category_id',
                'videos.created_at',
                'videos.updated_at',
            ]);
            $videos->where('videos.row_status', Video::ROW_STATUS_ACTIVE);
            $videos->leftJoin('video_categories', 'video_category_id', '=', 'video_categories.id');

            if ($request->input('searchQuery')) {
                $videos->where('videos.title', 'LIKE', '%' . $request->input('searchQuery') . '%')
                    ->orWhere('videos.title', 'LIKE', '%' . $request->input('searchQuery') . '%')
                    ->orWhere('videos.description', 'LIKE', '%' . $request->input('searchQuery') . '%')
                    ->orWhere('video_categories.title', 'LIKE', '%' . $request->input('searchQuery') . '%');
            }

            if (!empty($request->input('institute_id'))) {
                $videos->where('videos.institute_id', $request->input('institute_id'));
            }
            if (!empty($request->input('video_category_id'))) {
                $videos->where('videos.video_category_id', $request->input('video_category_id'));
            }

            if (!empty($request->input('video_id'))) {
                $videos->where('videos.id', $request->input('video_id'));
            }
            $videos = $videos->paginate(15);


            return response()->json([
                'videos' => $videos,
                'links' => $videos->links()->render(),
            ]);
        }
    }

    public function advicePage(): View
    {
        $currentInstitute = app('currentInstitute');
        if (!$currentInstitute) {
            abort(404, 'Not found');
        }

        return \view(self::VIEW_PATH . 'static-contents.advice-page', compact('currentInstitute'));
    }

    public function generalAskPage(): View
    {
        /** @var Institute $currentInstitute */
        $currentInstitute = app('currentInstitute');

        /** @var QuestionAnswer|Builder $faqs */
        $faqs = QuestionAnswer::query();

        if ($currentInstitute) {
            $faqs->where('institute_id', $currentInstitute->id);
        } else {
            $faqs->withoutInstitute();
        }
        $faqs = $faqs->get();

        return \view(self::VIEW_PATH . 'static-contents.general-ask-page', compact('faqs'));
    }

    public function contactUsPage(): View
    {
        /** @var Institute $currentInstitute */
        $currentInstitute = app('currentInstitute');
        if (!$currentInstitute) {
            abort(404, 'Not found');
        }

        return \view(self::VIEW_PATH . 'static-contents.contact-us-page', compact('currentInstitute'));
    }

    public function sendMailToRecoverAccessKey(Request $request): RedirectResponse
    {
        $trainee = Trainee::where('email', $request->input('email'))
            ->first();

        if (empty($trainee)) {
            return back()->with([
                'message' => __('Email address not found!'),
                'alert-type' => 'error'
            ])->withInput();
        }

        $traineeEmailAddress = $trainee->email;
        $mailMsg = "Access Key Recovery Mail";
        $mailSubject = "Trainee - Account Recover Access Key";
        try {
            Mail::to($traineeEmailAddress)->send(new \App\Mail\TraineeRegistrationSuccessMail($mailSubject, $trainee->access_key, $mailMsg));
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('Sorry for the technical error, try again please'),
                'alert-type' => 'error'
            ])->withInput();
        }

        return back()->with([
            'message' => __('Your recovery email has been sent'),
            'alert-type' => 'success'
        ]);
    }


    public function checkTraineeEmailUniqueness(Request $request): JsonResponse
    {
        $trainee = Trainee::where('email', $request->email)->first();
        if ($trainee == null) {
            return response()->json(true);
        }
        return response()->json("This email id already is being used");
    }

    public function checkTraineeUniqueNID(Request $request): JsonResponse
    {
        $traineeNidNo = TraineeFamilyMemberInfo::where(['nid' => $request->nid, 'relation_with_trainee' => 'self'])->first();

        if ($traineeNidNo == null) {
            return response()->json(true);
        }
        return response()->json("A Trainee Registered by this NID, user another one");
    }

    public function checkTraineeUniqueBirthCertificateNo(Request $request): JsonResponse
    {
        $traineeBirthNo = TraineeFamilyMemberInfo::where(['birth_certificate_no' => $request->birth_certificate_no, 'relation_with_trainee' => 'self'])->first();
        if ($traineeBirthNo == null) {
            return response()->json(true);
        }
        return response()->json("This birth certificate has already been registered");
    }

    public function checkTraineeUniquePassportId(Request $request): JsonResponse
    {
        $traineePassportNo = TraineeFamilyMemberInfo::where(['passport_number' => $request->passport_number, 'relation_with_trainee' => 'self'])->first();
        if ($traineePassportNo == null) {
            return response()->json(true);
        }
        return response()->json("This passport has already been registered");
    }

    public function traineeCourseGetDatatable(Request $request): JsonResponse
    {
        return $this->traineeRegistrationService->getListDataForDatatable();
    }

    public function traineeCourseEnrollPayNow($traineeCourseEnroll)
    {
        $TraineeCourseEnroll = TraineeCourseEnroll::findOrFail($traineeCourseEnroll);
        $traineeId = $TraineeCourseEnroll->trainee_id;
        $userInfo['id'] = $TraineeCourseEnroll->id;
        $userInfo['trainee_id'] = $TraineeCourseEnroll->trainee_id;
        $userInfo['mobile'] = $TraineeCourseEnroll->trainee->mobile;
        $userInfo['email'] = $TraineeCourseEnroll->trainee->email;
        $userInfo['address'] = "Dhaka-1212";
        $userInfo['name'] = $TraineeCourseEnroll->trainee->name;

        $paymentInfo['trID'] = $traineeId . rand(100, 999);
        $paymentInfo['amount'] = $TraineeCourseEnroll->publishCourse->course->course_fee;
        $paymentInfo['orderID'] = $TraineeCourseEnroll->id;

        $activeDebug = true;

        $token = $this->ekPayPaymentGateway($userInfo, $paymentInfo, $activeDebug);
        if (!empty($token)) {
            $token = 'https://sandbox.ekpay.gov.bd/ekpaypg/v1?sToken=' . $token . '&trnsID=' . $paymentInfo['trID'];
        }

        return redirect($token);
    }

    public function ekPayPaymentGateway($userInfo, $paymentInfo, $activeDebug = false)
    {
        /*$marchantID = 'eporcha_test';
        $marchantKey = 'EprCsa@tST12';*/

        $merchantID = 'nise_test';
        $merchantKey = 'NiSe@TsT11';

        $macAddress = '1.1.1.1';
        $applicationURL = route('frontend.main');

        $time = Carbon::now()->format('Y-m-d H:i:s');

        $customerCleanName = preg_replace('/[^A-Za-z0-9 \-\.]/', '', $userInfo['name']);

        $data = '{
           "mer_info":{
              "mer_reg_id":"' . $merchantID . '",
              "mer_pas_key":"' . $merchantKey . '"
           },
           "req_timestamp":"' . $time . ' GMT+6",
           "feed_uri":{
              "s_uri":"' . $applicationURL . '/success",
              "f_uri":"' . $applicationURL . '/fail",
              "c_uri":"' . $applicationURL . '/cancel"
           },
           "cust_info":{
              "cust_id":"' . $userInfo['id'] . '",
              "cust_name":"' . $customerCleanName . '",
              "cust_mobo_no":"' . $userInfo['mobile'] . '",
              "cust_email":"' . $userInfo['email'] . '",
              "cust_mail_addr":"' . $userInfo['address'] . '"
           },
           "trns_info":{
              "trnx_id":"' . $paymentInfo['trID'] . '",
              "trnx_amt":"' . $paymentInfo['amount'] . '",
              "trnx_currency":"BDT",
              "ord_id":"' . $paymentInfo['orderID'] . '",
			  "ord_det":"course_fee"
           },
           "ipn_info":{
              "ipn_channel":"1",
              "ipn_email":"imiladul@gmail.com",
              "ipn_uri":"' . $applicationURL . '/api/ipn-handler"
           },
           "mac_addr":"' . $macAddress . '"
        }';

        $url = 'https://sandbox.ekpay.gov.bd/ekpaypg/v1/merchant-api';

        if ($activeDebug) {
            Log::debug("Trainee Name: " . $userInfo['name'] . ' , Trainee Enroll ID: ' . $paymentInfo['orderID']);
            Log::debug($data);
        }
        try {
            // Setup cURL
            $ch = \curl_init($url);
            \curl_setopt_array($ch, array(
                CURLOPT_POST => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_HTTPHEADER => array(
                    //'Authorization: '.$authToken,
                    'Content-Type: application/json'
                ),
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            ));

            // Send the request
            $response = \curl_exec($ch);
        } catch (\Exception $exception) {
            //ipnLog("Curl request failed." . $exception->getMessage());
        }

        // Decode the response
        $responseData = json_decode($response, TRUE);
        return $responseData['secure_token'];
    }

    public function ipnHandler(Request $request)
    {
        if (!empty($request)) {
            Log::debug("=========================================");

            Log::debug("SandBox Request: ");
            Log::debug($request);

            Log::debug("=========================================");
        }

        Log::debug("=============Debug=============");
        Log::debug($request->msg_code);
        Log::debug($request->cust_info['cust_id']);
        Log::debug("===============================");


        if ($request->msg_code == 1020) {
            $traineeCourseEnroll = TraineeCourseEnroll::findOrFail($request->cust_info['cust_id']);


            $newData['payment_status'] = TraineeCourseEnroll::PAYMENT_STATUS_PAID;

            if ($traineeCourseEnroll->enroll_status == TraineeCourseEnroll::ENROLL_STATUS_ACCEPT) {
                $traineeCourseEnroll->update($newData);
            }

            $mailSubject = "Your payment successfully complete";
            $traineeEmailAddress = $request->cust_info['cust_email'];
            $mailMsg = "Congratulation! Your payment successfully completed.";
            $traineeName = $traineeCourseEnroll->trainee->name;
            Mail::to($traineeEmailAddress)->send(new \App\Mail\TraineePaymentSuccessMail($mailSubject, $traineeCourseEnroll->trainee->access_key, $mailMsg, $traineeName));

            return 'Payment successful';
        }

        $data['trainee_course_enroll_id'] = $request->cust_info['cust_id'];
        $data['transaction_id'] = $request->trnx_info['trnx_id'];
        $data['amount'] = $request->trnx_info['trnx_amt'];
        $data['log'] = $request;
        $data['payment_type'] = $request->pi_det_info['pi_type'];
        $data['payment_date'] = Carbon::now();
        $data['payment_status'] = $request->msg_code == 1020 ? '1' : '2';

        $payment = new Payment();
        $payment->fill($data);
        $payment->save();
    }

    public function certificate(): View
    {
        return \view(self::VIEW_PATH . 'trainee/certificate/certificate');
    }

    public function certificateDownload()
    {
        $traineeInfo = [
            'name' => 'Miladul Islam',
            'father_name' => "Father's Name",
            "register_no" => time(),
            'institute_title' => "BITAC",
            'from_date' => "10/08/2021",
            'to_date' => "10/10/2021",
        ];

        $template = self::VIEW_PATH . 'trainee/certificate/certificate-two';
        $pdf = app(CertificateGenerator::class);
        return $pdf->generateCertificate($template, $traineeInfo);
    }

    public function certificateTwo()
    {
        return \view(self::VIEW_PATH . 'trainee/certificate/certificate-two');
    }
}

